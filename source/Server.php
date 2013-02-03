<?php
/* Copyright © 2013 Fabian Schuiki */

/**
 * Main server class that maintains information quanta and handles requests
 * and information conversion.
 */

class Server
{
	protected $socketPath;
	protected $socket;
	protected $clients;

	protected $quanta;
	protected $quantumId;
	protected $casters;
	protected $editorStack;

	public function __construct($socketPath)
	{
		$this->socketPath = $socketPath;
		$this->clients = array();
		$this->quanta = array();
		$this->quantumId = 1;
		$this->casters = array();
		$this->editorStack = array();

		//Register a default information quantum ID resolver.
		Information\Quantum::$resolveIdCallback = array($this, "resolveQuantumId");

		//Initialize the root information quantum.
		$root = $this->makeQuantum("Information\Container");
		$root->setType("root");

		//Create a simple file quantum that wraps around a file on disk.
		$file = $this->makeQuantum("Information\Container");
		$file->setType("file");
		$file->path = "world.txt";
		$content = $this->makeQuantum("Information\Raw");
		$file->setChild("content", $content);
		$root->setChild("world", $file);

		//Load the initial file contents.
		$this->notifyChangeDown($file, "");

		//Register the information quantum change callback.
		Information\Quantum::$changeCallback = array($this, "notifyQuantumChanged");
	}

	/** Returns the quantum with the given id. Throws an exception if the
	 * quantum does not exist. */
	public function resolveQuantumId($id)
	{
		$quantum = @$this->quanta[$id];
		if (!$quantum) {
			throw new \InvalidArgumentException("Quantum $id does not exist.");
		}
		return $quantum;
	}

	/** Returns a new and empty information quantum. */
	public function makeQuantum($class = "Information\Quantum")
	{
		$iq = new $class($this->quantumId++);
		if (isset($this->quanta[$iq->getId()])) {
			throw new \RuntimeException ("Information quantum {$iq->getId()} already exists. This shouldn't happen.");
		}
		$this->quanta[$iq->getId()] = $iq;
		return $iq;
	}

	/** Performs the server tasks forever. */
	public function run()
	{
		//Initialize the socket.
		@unlink($this->socketPath);
		$this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
		if (!$this->socket) {
			throw new \RuntimeException("Unable to create socket.");
		}

		if (!socket_bind($this->socket, $this->socketPath)) {
			throw new \RuntimeException("Unable to bind to {$this->socketPath}.");
		}
		socket_listen($this->socket);

		//Accept incoming connections.
		do {
			$changed = $this->clients;
			$changed[] = $this->socket;
			$w = null; $e = null;
			if (!socket_select($changed, $w, $e, null)) {
				throw new \RuntimeException("Error during socket_select: ".socket_last_error().".");
			}

			//New connection available.
			if (in_array($this->socket, $changed, true)) {
				$client = socket_accept($this->socket);
				if (!$client)
					throw new \RuntimeError("Unable to accept connection.");
				$this->clients[] = $client;
				echo "client connected\n";
			}

			//Handle client communication.
			foreach ($changed as $client) {
				if (!in_array($client, $this->clients, true)) continue;
				$received = socket_read($client, 1024*1024);
				if (!$received) {
					echo "client disconnected\n";
					$this->clients = array_diff($this->clients, array($client));
					continue;
				}
				$this->serveClient($client, unserialize($received));
			}
		} while (true);
	}

	public function serveClient($client, \stdClass $request)
	{
		switch ($request->type) {
			case "GET": {
				$root = $this->quanta[1];
				$child = $root;
				foreach (explode("/", $request->path) as $name) {
					$child = $child->getChild($name);
				}
				$response = new stdClass;
				if ($child) {
					$type = (isset($request->as) ? $request->as : $child->getType());
					if ($type !== $child->getType()) {
						$caster = $this->getCaster($child, $type);
						if (!$caster) {
							$response->type = "FAIL";
							$response->message = "Information quantum {$request->path} of type {$child->getType()} cannot be converted to {$request->as}.";
							$child = null;
						} else {
							$child = $caster->getOutput();
						}
					}
					if ($child) {
						$response->type = "QUANTUM";
						$response->payload = $child;
					}
				} else {
					$response->type = "FAIL";
					$response->message = "Information quantum {$request->path} doesn't exist.";
				}
				socket_write($client, serialize($response));
			} break;
			case "SET": {
				if (!$request->payload instanceof Information\Quantum) {
					echo "Payload is no information quantum.";
					print_r($request);
					break;
				}
				$this->quanta[$request->payload->getId()] = $request->payload;
				$this->pushEditor($client);
				$this->notifyChange($request->payload, "");
				$this->popEditor($client);
			} break;
			default: {
				echo "Request type \"{$request->type}\" is not supported.\n";
			} break;
		}
	}

	private function notifyChange(Information\Quantum $quantum, $path = "")
	{
		echo "Upward Change: $quantum, $path\n";

		/*if ($quantum->getType() === "string/base64" && $path === "decoded") {
			$quantum->getChild("encoded")->setString(base64_encode($quantum->getChild("decoded")->getString()));
			$this->notifyChange($quantum, "encoded");
			return;
		}*/

		if ($quantum->getType() === "file" && preg_match('/^content/', $path)) {
			$content = $quantum->getChild("content");
			file_put_contents($quantum->path, $content->getData());
			$this->notifyChange($quantum);
			return;
		}

		/*if ($quantum->getType() === "file/zip" && preg_match('/^content/', $path)) {
			$zip = new ZipArchive;
			$zip->open($quantum->path, ZIPARCHIVE::CREATE);
			$zip->addFromString("world.txt", $quantum->getChild("content")->getChild("encoded")->getString());
			$zip->close();
			$this->notifyChange($quantum, "");
			return;
		}*/

		//Check whether there is a caster that might be interested about this change.
		foreach ($this->casters as $name => $caster) {
			if (in_array($caster, $this->editorStack, true)) continue;
			if ($caster->getOutput()->getId() == $quantum->getId()) {
				$this->pushEditor($caster);
				$caster->notifyOutputChanged();
				$this->popEditor($caster);
			}
		}

		if ($parent = $quantum->getParent()) {
			$new_path = $quantum->getName();
			if ($path) $new_path .= "/".$path;
			$this->notifyChange($parent, $new_path);
		}
	}

	private function notifyChangeDown(Information\Quantum $quantum, $path = "")
	{
		if (strlen($path)) {
			$split = explode("/", $path, 2);
			$name = $split[0];
			$rest = (count($split) > 1 ? $split[1] : null);
			$this->notifyChangeDown($quantum->getChild($name), $rest);
		} else {
			echo "Downward Change: $quantum\n";
			switch ($quantum->getType()) {
				case "file": {
					$content = $quantum->getChild("content");
					$content->setData(@file_get_contents($quantum->path));
					$this->notifyChangeDown($content);
				} break;
				/*case "file/zip": {
					$zip = new ZipArchive;
					$zip->open($quantum->path);
					$data = $zip->getFromName("world.txt");
					$zip->close();
					$text = $this->makeQuantum("Information\String");
					$text->setString($data);
					$quantum->getChild("content")->setChild("encoded", $text);
					$this->notifyChangeDown($quantum, "content");
				} break;
				case "string/base64": {
					$quantum->getChild("decoded")->setString(base64_decode($quantum->getChild("encoded")->getString()));
					$this->notifyChangeDown($quantum, "decoded");
				} break;*/
				default: {
					//Look for casters that might be interested about this change.
					foreach ($this->casters as $name => $caster) {
						if (in_array($caster, $this->editorStack, true)) continue;
						if ($caster->getInput()->getId() == $quantum->getId()) {
							$this->pushEditor($caster);
							$caster->notifyInputChanged();
							$this->popEditor($caster);
						}
					}
				} break;
			}
		}
	}

	/**
	 * Returns a caster that converts the given quantum to the requested type.
	 * Returns null if the cast is not possible.
	 */
	private function getCaster(Information\Quantum $quantum, $type)
	{
		$name = "{$quantum->getId()} -> $type";
		$caster = @$this->casters[$name];
		if (!$caster) {
			$caster = $this->makeCaster($quantum, $type);
			if ($caster) {
				echo "Created Caster $name\n";
				$this->casters[$name] = $caster;
				$this->pushEditor($caster);
				$caster->notifyInputChanged();
				$this->popEditor($caster);
			}
		}
		return $caster;
	}

	/** Returns a caster object for that casts between the given quantum and
	 * the requested type. Returns null if the cast is impossible. */
	private function makeCaster(Information\Quantum $quantum, $type)
	{
		echo "Asked to make caster from $quantum to $type\n";
		if ($quantum->getType() === "raw" && $type === "string") {
			return new Caster\RawToString($this, $quantum);
		}
		return null;
	}

	public function notifyQuantumChanged(Information\Quantum $quantum)
	{
		$this->notifyChange($quantum);
	}

	public function pushEditor($editor) { array_push($this->editorStack, $editor); }
	public function popEditor($editor)
	{
		$e = array_pop($this->editorStack);
		if ($e !== $editor) {
			throw new \InvalidArgumentException("Trying to pop editor ".var_export($editor).", yet ".var_export($e)." was on the stack.");
		}
	}
}
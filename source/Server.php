<?php
/* Copyright © 2013 Fabian Schuiki */

/**
 * Main server class that maintains information quanta and handles requests
 * and information conversion.
 */

define("kStringFrameType", 1);
define("kIntegerFrameType", 2);
define("kRequestQuantumFrameType", 200);
define("kRequestQuantumResponseFrameType", 201);

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

		//Test the serializer.
		file_put_contents("/tmp/quantum-serializer.json", Information\Serializer::encode($content));

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
			$getSocketFunc = function($c){ return $c->getSocket(); };
			$r = array_map($getSocketFunc, $this->clients);
			$w = array_map($getSocketFunc, array_filter($this->clients, function($c){
				return $c->wantsToWrite();
			}));
			$e = null;
			$r[] = $this->socket;
			if (!socket_select($r, $w, $e, null)) {
				throw new \RuntimeException("Error during socket_select: ".socket_last_error().".");
			}

			//New connection available.
			if (in_array($this->socket, $r, true)) {
				$client = socket_accept($this->socket);
				if (!$client)
					throw new \RuntimeError("Unable to accept connection.");
				$this->clients[] = new FrameSocket ($client, array($this, "serveClient"));
				echo "Client connected.\n";
			}

			//Handle client communication.
			foreach ($this->clients as $client) {
				//Reading.
				if (in_array($client->getSocket(), $r, true)) {
					if (!$client->read()) {
						echo "Client disconnected.\n";
						$this->clients = array_filter($this->clients, function($c) use ($client) {
							return $c !== $client;
						});
						continue;
					}
				}

				//Writing.
				if (in_array($client->getSocket(), $w, true)) {
					$client->write();
				}
			}
		} while (true);
	}

	/** Handles frames received from a certain client. */
	public function serveClient(Frame $frame, FrameSocket $client)
	{
		if ($frame->getType() === 255) {
			echo "Client sent error: {$frame->getData()}\n";
			return;
		}
		if ($frame->getType() != 1) {
			echo "Client sent frame $frame, but only frames of type 1 and 255 are supported.\n";
			$this->respondWithError($client, "Frame type {$frame->getType()} not supported.");
			return;
		}

		//Decode the JSON data in the frame.
		$request = json_decode($frame->getData());
		if (!$request) {
			echo "Client sent invalid JSON data: {$frame->getData()}\n";
			return;
		}

		//Handle the request.
		print_r($request);
		switch ($request->type) {
			case "GET": {
				//Look up the requested child.
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
							$this->respondWithError($client, "Information quantum {$request->path} of type {$child->getType()} cannot be converted to {$request->as}.");
							$child = null;
						} else {
							$child = $caster->getOutput();
						}
					}
					if ($child) {
						$response = new \stdClass;
						$response->rid = $request->rid;
						$response->type = "SET";
						$response->iq = Information\Serializer::encode($child);
						$client->writeFrame(new Frame (1, json_encode($response)));
					}
				} else {
					$this->respondWithError($client, "Information quantum {$request->path} doesn't exist.");
				}
			} break;

			case "SET STRING": {
				$quantum = @$this->quanta[$request->id];
				if (!$quantum) {
					$this->respondWithError($client, "Information quantum #{$request->id} doesn't exist.");
				} else {
					$quantum->setString($request->string);
					$this->respondWithDone($client, $request->rid);
				}
			} break;

			default: {
				$this->respondWithError($client, "Request type {$request->type} is not supported.");
			} break;
		}
	}

	private function respondWithError(FrameSocket $client, $message)
	{
		$client->writeFrame(new Frame (255, $message));
	}

	private function respondWithDone(FrameSocket $client, $rid)
	{
		$response = new \stdClass;
		$response->rid = $rid;
		$response->type = "DONE";
		$client->writeFrame(new Frame (1, json_encode($response)));
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

	static public function encodeFrame($data)
	{
		if (is_string($data)) {
			return new Frame (kStringFrameType, $data);
		}
		if (is_integer($data)) {
			return new Frame (kIntegerFrameType, pack("l", $data));
		}
		throw new \InvalidArgumentException("Unable to encode ".var_export($data)." into a frame.");
	}

	static public function decodeFrame(Frame $frame)
	{
		switch ($frame->getType()) {
			case kStringFrameType: {
				return $frame->getData();
			} break;
			case kIntegerFrameType: {
				$a = unpack("li", $frame->getData());
				return $a["i"];
			} break;
			default: {
				echo "*** Unable to decode frame {$frame->getType()}\n";
			} break;
		}
		return null;
	}

	static public function consumeAndDecodeFrameData(&$input)
	{
		$data = static::decodeFrame(Frame::unserialize($input, $consumed));
		$input = substr($input, $consumed);
		return $data;
	}
}
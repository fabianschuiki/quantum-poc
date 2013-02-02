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

	public function __construct($socketPath)
	{
		$this->socketPath = $socketPath;
		$this->clients = array();
		$this->quanta = array();
		$this->quantumId = 1;

		//Register a default information quantum ID resolver.
		Information\Quantum::$resolveIdCallback = array($this, "resolveQuantumId");

		//Initialize the root information quantum.
		$root = $this->makeQuantum("Information\Container");

		//Create a simple text quantum for debugging.
		$text = $this->makeQuantum("Information\String");
		$text->setString("Hello World!");
		$root->setChild("world", $text);
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
		echo "server: created IQ {$iq->getId()}\n";
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
				$child = $root->getChild($request->path);
				$response = new stdClass;
				if ($child) {
					$response->type = "QUANTUM";
					$response->payload = $child;
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
				echo "Stored $request->payload\n";
			} break;
			default: {
				echo "Request type \"{$request->type}\" is not supported.\n";
			} break;
		}
	}
}
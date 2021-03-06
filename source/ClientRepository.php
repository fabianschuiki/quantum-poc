<?php
/* Copyright © 2013 Fabian Schuiki */
use Information\Quantum;
use Information\Serializer;

/**
 * An extension of the repository class that manages information quanta
 * exchanged with a server across a connection.
 */

class ClientRepository extends Repository
{
	protected $socketPath = "/tmp/quantum.sock";
	protected $socket = null;
	protected $expectedResponses = array();
	protected $requestId = 1;
	protected $observers = array();
	protected $respondedQuantumIds = array();

	/** Establishes a connection to the quantum server. */
	public function connect()
	{
		$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
		if (!$socket) {
			throw new \RuntimeException("Unable to create socket.");
		}
		if (!socket_connect($socket, $this->socketPath)) {
			throw new \RuntimeException("Unable to connect to {$this->socketPath}.");
		}
		$this->socket = new FrameSocket($socket, array($this, "receivedFrame"));
	}

	public function getQuantumWithId($id)
	{
		// Retrieve the quantum from the server if it doesn't exist.
		if ($id >= 0 && !isset($this->quanta[$id])) {
			$rid = $this->allocRequestId();
			$request = new \stdClass;
			$request->rid = $rid;
			$request->type = "GET";
			$request->id = $id;
			$this->socket->writeFrame(new Frame(1, json_encode($request)));
			$this->waitForResponse($rid);
		}

		// Return the quantum.
		return parent::getQuantumWithId($id);
	}

	/** Performs communication with the server. */
	public function communicate()
	{
		while (true) {
			$this->communicateOnce();
		}
	}

	/** Performs one communication cycle with the server. If there's anything
	 * to be read or written, this function immediately handles the transfer
	 * and returns. Otherwise it blocks indefinitely or until the timeout has
	 * been reached. */
	public function communicateOnce($timeout = null)
	{
		$r = array($this->socket->getSocket());
		$w = ($this->socket->wantsToWrite() ? array($this->socket->getSocket()) : array());
		$e = null;
		socket_select($r, $w, $e, $timeout);
		if (count($r)) {
			if (!$this->socket->read()) {
				throw new \RuntimeException("Connection to the server lost.");
			}
		}
		if (count($w)) {
			$this->socket->write();
		}
	}

	public function waitForResponse($rid)
	{
		$this->expectedResponses[] = $rid;
		do {
			$this->communicateOnce();
		} while (in_array($rid, $this->expectedResponses));
	}

	public function receivedFrame(Frame $frame, FrameSocket $socket)
	{
		if ($frame->getType() == 255) {
			die("Server Error: {$frame->getData()}\n");
		}
		if ($frame->getType() != 1) {
			throw new \RuntimeException("Invalid frame type {$frame->getType()}, only types 1 and 255 are supported.");
		}

		// Decode the message.
		$message = json_decode($frame->getData());
		$this->expectedResponses = array_diff($this->expectedResponses, array($message->rid));
		//print_r($message);

		// Handle new information quanta.
		if ($message->type == "SET") {
			$quantum = Information\Serializer::decode($message->iq, $this);
			$this->respondedQuantumIds[$message->rid] = $quantum->getId();
			echo "Received a SET message with rid = {$message->rid}\n";
		}

		// Handle changes to string quanta.
		else if ($message->type == "SET STRING") {
			$quantum = $this->getQuantumWithId($message->id);
			if (isset($message->range)) {
				$quantum->replaceString($message->string, $message->range[0], $message->range[1], false);
			} else {
				$quantum->setString($message->string, false);
			}
			echo "SET STRING: $quantum changed string to \"{$quantum->getString()}\"\n";
			$this->notifyObservers($quantum);
		}

		// Throw an exception for unsupported messages.
		else {
			print_r($message);
			throw new \RuntimeException("Received unsupported message {$message->type}.");
		}
	}

	/** Allocates a new request ID and returns it. */
	public function allocRequestId()
	{
		$id = $this->requestId++;
		if ($this->requestId >= (1 << 31)) {
			$this->requestId = 0;
			echo "Request ID wrapped around.\n";
		}
		return $id;
	}

	/** Returns an unused local quantum ID. */
	public function allocId()
	{
		for ($i = -1; $i > -1e6; $i--) {
			if (!isset($this->quanta[$i]))
				return $i;
		}
		throw new \RuntimeException("No more local IDs available");
	}

	/**
	 * @copydoc Repository::addQuantum()
	 * Additionally pushes the new quantum to the server.
	 */
	public function addQuantum(Quantum $quantum)
	{
		echo "Adding quantum $quantum\n";
		parent::addQuantum($quantum);

		//Inform the server about the new quantum.
		if ($quantum->getId() < 0) {
			$request = new \stdClass;
			$request->rid = $this->allocRequestId();
			$request->type = "SET";
			$request->iq = Serializer::encode($quantum);
			$this->socket->writeFrame(new Frame(1, json_encode($request)));
		}
	}

	/**
	 * @copydoc Repository::removeQuantum()
	 * Additionally informs the server about the removed quantum.
	 */
	public function removeQuantum(Quantum $quantum)
	{
		parent::removeQuantum($quantum);
		throw new \RuntimeException("Quantum removal server notifications not implemented.");
	}

	/** Handles changes in string quanta and forwards them to the server. */
	public function notifyStringChanged(Information\String $quantum, $range, $string)
	{
		echo "$quantum changed ".json_encode($range)." to \"$string\"\n";
		$request = new \stdClass;
		$request->rid = $this->allocRequestId();
		$request->type = "SET STRING";
		$request->id = $quantum->getId();
		if ($range) $request->range = $range;
		$request->string = $string;
		$this->socket->writeFrame(new Frame(1, json_encode($request)));
	}

	/** Handles changes in container quanta and forwards the mto the server. */
	public function notifyContainerAddedChild(Information\Container $quantum, $name)
	{
		echo "$quantum added child $name\n";
		$request = new \stdClass;
		$request->rid = $this->allocRequestId();
		$request->type = "SET CHILD";
		$request->id = $quantum->getId();
		$request->name = $name;
		$request->child = $quantum->getChildId($name);
		$this->socket->writeFrame(new Frame(1, json_encode($request)));
	}

	/** Adds the given observation callback to the quantum. The callback is
	 * called whenever the given path of the quantum, or the entire quantum if
	 * path is null, changes. */
	public function addObserver(Quantum $quantum, $callback, $path = null)
	{
		$obs = new \stdClass;
		$obs->id = $quantum->getId();
		$obs->path = $path;
		$obs->callback = $callback;
		$this->observers[] = $obs;
	}

	/** Notifies all observers of the given quantum and path about a change
	 * that occurred to the quantum. */
	protected function notifyObservers(Quantum $quantum, $path = null)
	{
		foreach ($this->observers as $obs) {
			if ($quantum->getId() === $obs->id && $path === $obs->path) {
				call_user_func($obs->callback, $quantum, $path);
			}
		}
	}

	/** Tries to get the quantum at the given path, possible creating casters
	 * that convert the quantum to an appropriate format. */
	public function getQuantumWithPath($path)
	{
		$rid = $this->allocRequestId();
		$request = new \stdClass;
		$request->rid = $rid;
		$request->type = "GET";
		$request->path = $path;
		$this->socket->writeFrame(new Frame(1, json_encode($request)));
		$this->waitForResponse($rid);
		return $this->quanta[$this->respondedQuantumIds[$rid]];
	}
}
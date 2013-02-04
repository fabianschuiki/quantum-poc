<?php
/* Copyright © 2013 Fabian Schuiki */

/**
 * Class that handles communication across a socket resource via data frames.
 * A callback may be provided to handle received frames.
 */

class FrameSocket
{
	protected $socket;
	protected $buffer;
	protected $queue;
	protected $callback;

	private $expectedType;
	private $expectedLength;

	public function __construct($socket, $callback = null)
	{
		$this->socket = $socket;
		$this->buffer = "";
		$this->queue = array();
		$this->callback = $callback;

		$this->expectedType = null;
		$this->expectedLength = null;
	}

	/** Returns the socket resource this object utilizes for communication.*/
	public function getSocket() { return $this->socket; }

	/** Returns the callback the object uses to report received frames. */
	public function getCallback() { return $this->callback; }

	/** Sets the function that is called whenever the socket receives a new
	 * data frame. */
	public function setCallback($callback) { $this->callback = $callback; }

	/** Causes the socket to read any available data, potentially calling the
	 * callback function with new frames. */
	public function read()
	{
		//Read some data into the buffer.
		$read = socket_read($this->socket, 1024*1024);
		if ($read) {
			$this->buffer .= $read;
		}

		//Only consider the buffer for further operations if it contains at
		//least enough data for the header.
		while (strlen($this->buffer) >= 5)
		{
			//Parse the header if not already done so for this frame.
			if (!$this->expectedType || !$this->expectedLength) {
				$a = unpack("Ct/Ll", $this->buffer);
				$this->expectedType = $a["t"];
				$this->expectedLength = $a["l"];
			}

			//If the buffer contains enough data for the entire frame, wrap it
			//up and truncate the buffer.
			if (strlen($this->buffer) >= $this->expectedLength + 5) {
				$f = new Frame($this->expectedType, substr($this->buffer, 5, $this->expectedLength));
				$this->buffer = substr($this->buffer, 5 + $this->expectedLength);
				$this->expectedType = null;
				$this->expectedLength = null;
				call_user_func($this->callback, $f, $this);
			} else {
				break;
			}
		}

		return $read != null;
	}

	/** Causes the socket to write any frames in the queue. */
	public function write()
	{
		//Pop the next frame to be written off the queue.
		if (!count($this->queue))
			return;
		$f = array_shift($this->queue);

		//Write the serialized version of the frame.
		socket_write($this->socket, $f->serialize());
	}

	/** Returns true if the socket has any frames in the queue that it wishes
	 * to write. Call this function to find out whether the socket's write
	 * state needs to be checked through select(). */
	public function wantsToWrite()
	{
		return count($this->queue) > 0;
	}

	/** Queues the given frame to be written. Note that the function returns
	 * immediately, without the frame actually having been written. The frames
	 * are sent in the order in which writeFrame() was called. */
	public function writeFrame(Frame $f)
	{
		$this->queue[] = $f;
	}
}
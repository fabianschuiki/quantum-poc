<?php
/* Copyright © 2013 Fabian Schuiki */

class Frame
{
	protected $type;
	protected $data;

	public function __construct($type, $data)
	{
		if (!is_integer($type)) {
			throw new \InvalidArgumentException("Type must be an integer, got ".var_export($type)." instead.");
		}
		if (!is_string($data)) {
			if (strlen($data) == 0) {
				$data = "";
			} else {
				throw new \InvalidArgumentException("Data must be a binary string, got ".var_export($data)." instead.");
			}
		}

		$this->type = $type;
		$this->data = $data;
	}

	/** Returns the type of data contained in this frame as an integer. */
	public function getType() { return $this->type; }

	/** Returns the data contained in this frame. */
	public function getData() { return $this->data; }

	/** Returns the raw serialized version of the frame, ready to be sent over
	 * a socket connection. */
	public function serialize()
	{
		return pack("CL", $this->type, strlen($this->data)) . $this->data;
	}

	static public function unserialize($data, &$consumed = null)
	{
		//Decode the header.
		if (strlen($data) < 5) {
			throw new \InvalidArgumentException("Data to unserialize must be at least 5 bytes long.");
		}
		$a = unpack("Ct/Ll", $data);
		$type   = $a["t"];
		$length = $a["l"];

		//Wrap up the new frame.
		if (strlen($data) < $length + 5) {
			throw new \InvalidArgumentException("Not enough data provided to unserialize frame; ".($length+5)." Bytes required, got ".strlen($data)." Bytes.");
		}
		$f = new self ($type, substr($data, 5, $length));
		$consumed = $length + 5;
		return $f;
	}
}
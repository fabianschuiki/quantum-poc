<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

class Raw extends Quantum
{
	protected $data;

	/** Returns the raw data encapsulated by this quantum. */
	public function getData() { return $this->data; }

	/** Sets the raw data encapsulated by this quantum. Issues an information
	 * change event. */
	public function setData($d, $notify = true)
	{
		if ($this->data != $d) {
			$this->data = $d;
			if ($notify) $this->notifyChange();
		}
	}

	/** Returns "raw". */
	public function getType() { return "raw"; }
}
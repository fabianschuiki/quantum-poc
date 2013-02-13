<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

class String extends Quantum
{
	protected $string;

	/** Returns the string encapsulated by this quantum. */
	public function getString() { return $this->string; }

	/** Sets the string encapsulated by this quantum. Issues an information
	 * change event. */
	public function setString($s, $notify = true)
	{
		if ($this->string != $s) {
			$this->string = $s;
			if ($notify) $this->notifyChange();
		}
	}

	/** Returns "string". */
	public function getType() { return "string"; }
}
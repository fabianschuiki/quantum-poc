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
	public function setString($s)
	{
		if ($this->string != $s) {
			$this->string = $s;
			$this->notifyChange();
		}
	}

	/** Returns "string". */
	public function getType() { return "string"; }
}
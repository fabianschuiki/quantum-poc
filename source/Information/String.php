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
			if ($notify) $this->repository->notifyStringChanged($this, null, $s);
		}
	}

	/** Replaces the given range of the string with $string. */
	public function replaceString($string, $start, $length = -1, $notify = true)
	{
		$s = ($start >= 0 ? $start : strlen($this->string) - $start);
		$l = ($length >= 0 ? $length : strlen($this->string) - $s);
		$this->string = substr($this->string, 0, $s).$string.substr($this->string, $s+$l);
		if ($notify) $this->repository->notifyStringChanged($this, array($s,$l), $string);
	}

	/** Returns "string". */
	public function getType() { return "string"; }
}
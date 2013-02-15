<?php
/* Copyright © 2013 Fabian Schuiki */
use Information\Quantum;

/**
 * Repository objects maintain a collection of information quanta.
 */

class Repository
{
	protected $quanta = array();

	/** Adds the given quantum to the repository. Throws an exception if a
	 * quantum with the same ID already exists. */
	public function addQuantum(Quantum $quantum)
	{
		$existing = @$this->quanta[$quantum->getId()];
		if ($existing) {
			throw new \InvalidArgumentException("Trying to add $quantum, but another quantum with the same ID already exists ($existing).");
		}
		$this->quanta[$quantum->getId()] = $quantum;
	}

	/** Removes the given quantum from the repository. Throws an exception if
	 * the quantum is not part of the given repository. */
	public function removeQuantum(Quantum $quantum)
	{
		if (!isset($this->quanta[$quantum->getId()])) {
			throw new \InvalidArgumentException("Trying to remove $quantum which is not part of the repository.");
		}
		unset($this->quanta[$quantum->getId()]);
	}

	/** Returns an unused quantum ID. */
	public function allocId()
	{
		for ($i = 0; $i < (1 << 31); $i++) {
			if (!isset($this->quanta[$i]))
				return $i;
		}
		throw new \RuntimeException("No more IDs available");
	}

	/** Returns the quantum with the given ID. Throws an exception if the quantum
	 * does not exist. */
	public function getQuantumWithId($id)
	{
		if (isset($this->quanta[$id])) {
			return $this->quanta[$id];
		}
		throw new \InvalidArgumentException("Quantum with ID $id doesn't exist.");
	}

	/** Returns the repository's root object. */
	public function getRoot()
	{
		return $this->getQuantumWithId(0);
	}

	/** Replaces all occurrences of the old ID with the new one. */
	public function changeId($old, $new)
	{
		if (isset($this->quanta[$new])) {
			throw new \InvalidArgumentException("Trying to change ID $old to $new, which already exists for quantum ".$this->quanta[$new].".");
		}
		$iq = @$this->quanta[$old];
		if ($iq) {
			$this->quanta[$new] = $iq;
			unset($this->quanta[$old]);
		}
	}

	public function notifyStringChanged(Information\String $quantum, $range, $string) {}
	public function notifyRawChanged(Information\Raw $quantum, $range, $data) {}
	public function notifyContainerAddedChild(Information\Container $quantum, $name) {}
	public function notifyContainerRemovedChild(Information\Container $quantum, $name) {}
}
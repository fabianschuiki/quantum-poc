<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

/**
 * Basic class representing the functionality and properties common to all
 * information quanta.
 */

class Quantum
{
	protected $id;
	protected $parentId;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function __toString()
	{
		return get_class($this)." #{$this->id}";
	}

	/** Returns the quantum's id. */
	public function getId() { return $this->id; }

	/** Returns the quantum's parentId. */
	public function getParentId() { return $this->parentId; }

	/** Changes the quantum's parentId, thus issuing an information change
	 * event. */
	public function setParentId($id)
	{
		if ($this->parentId !== $id) {
			$this->parentId = $id;
			$this->notifyChange();
		}
	}

	/** Returns the quantum's parent, or null if it has none. */
	public function getParent()
	{
		if ($this->parentId)
			return $this->resolveId($this->parentId);
		return null;
	}

	/** Changes the quantum's parent. This will in fact adjust this quantum's
	 * parentId and issue an information change event. */
	public function setParent(self $parent = null)
	{
		$this->setParentId($parent ? $parent->getId() : null);
	}

	/** Called by the class itself whenever the information of the quantum
	 * changes. */
	protected function notifyChange()
	{
		if (static::$changeCallback) {
			call_user_func(static::$changeCallback, $this);
		}
	}

	/** Callback function called whenever the information quantum changes. The
	 * Callback should take an Information\Quantum as an argument. */
	static public $changeCallback;

	/** Called by the class whenever it needs to resolve an information quantum
	 * ID. This will usually use the standard resolve callback of the class. */
	protected function resolveId($id)
	{
		return call_user_func(static::$resolveIdCallback, $id);
	}

	/** Callback function that takes an ID and returns the appropriate
	 * information quantum or throws an exception if none exists. */
	static public $resolveIdCallback;
}
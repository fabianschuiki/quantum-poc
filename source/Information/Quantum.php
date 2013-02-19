<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

/**
 * Basic class representing the functionality and properties common to all
 * information quanta.
 */

abstract class Quantum
{
	protected $repository;
	protected $id;
	protected $parentId;
	protected $name;

	/** Initializes a new information quantum inside the given repository. If
	 * no ID is provided, a new local ID is allocated from the repository. */
	public function __construct(\Repository $repo, $id = null)
	{
		$this->repository = $repo;
		if ($id === null) {
			$id = $repo->allocId($this);
		}
		$this->id = $id;
		$repo->addQuantum($this);
	}

	public function __toString()
	{
		return "<{$this->getType()} #{$this->getId()} \"{$this->getName()}\">";
	}

	abstract public function getType();

	/** Returns the quantum's id. Allocates a new ID for the quantum if none
	 * has been set yet. */
	public function getId() { return $this->id; }

	/** Alters this quantum's ID, informing the repository about the change.
	 * You should never have to call this function. */
	public function setId($id)
	{
		if ($this->id != $id) {
			echo "Changing $this ID to $id\n";
			$this->repository->changeId($this->id, $id);
			$this->id = $id;
		}
	}

	/** Returns the quantum's parentId. */
	public function getParentId() { return $this->parentId; }

	/** Changes the quantum's parentId, thus issuing an information change
	 * event. */
	public function setParentId($id, $notify = true)
	{
		if ($this->parentId !== $id) {
			$this->parentId = $id;
			if ($notify) $this->notifyChange();
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
	public function setParent(self $parent = null, $notify = true)
	{
		$this->setParentId($parent ? $parent->getId() : null, $notify);
	}

	/** Returns this information quantum's name. In general, if this quantum is
	 * a child of another quantum, the name corresponds to the child name. */
	public function getName() { return $this->name; }

	/** Sets the quantum's name. You should never have to call this yourself.
	 * Whenever the quantum is added to a container, its name is adjusted. */
	public function setName($name, $notify = true)
	{
		if ($this->name !== $name) {
			$this->name = $name;
			if ($notify) $this->notifyChange();
		}
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
		//return call_user_func(static::$resolveIdCallback, $id);
		return $this->repository->getQuantumWithId($id);
	}

	/** Callback function that takes an ID and returns the appropriate
	 * information quantum or throws an exception if none exists. */
	static public $resolveIdCallback;


	/** Adds the given observation callback to this quantum. The callback is
	 * called whenever the given path of the quantum, or the entire quantum if
	 * path is null, changes. */
	public function addObserver($callback, $path = null)
	{
		$this->repository->addObserver($this, $callback, $path);
	}
}
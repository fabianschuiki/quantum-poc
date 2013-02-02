<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

/**
 * Compound information quantum that structures multiple information quanta
 * together as key-value pairs.
 */

class Container extends Quantum
{
	protected $childrenIds;

	public function __construct($id)
	{
		parent::__construct($id);
		$this->childrenIds = array();
	}

	/** Returns the child id for the given name, or null if it doesn't exist. */
	public function getChildId($name)
	{
		return @$this->childrenIds[$name];
	}

	/** Adds the given information quantum id to this quantum's children for
	 * the given name. Resolves the id first before adding the child, since the
	 * child's parentId needs to be adjusted. Issues an information change
	 * event. */
	public function setChildId($name, $id)
	{
		$this->setChild($name, $this->resolveId($id));
	}

	/** Returns the child information quantum with the given name, or null if
	 * it doesn't exist. */
	public function getChild($name)
	{
		$id = $this->getChildId($name);
		return ($id ? $this->resolveId($id) : null);
	}

	/** Adds the given information quantum to this quantum's children for the
	 * given name. Issues an information change event. */
	public function setChild($name, Quantum $info)
	{
		//Remove the existing child.
		if ($childId = $this->getChildId($name)) {
			$child = $this->resolveId($childId);
			if ($child->getParentId() != $this->getId()) {
				throw new \RuntimeException("$this has child $child that has the wrong parent ID {$child->getParentId()}.");
			}
			$child->setParentId(null);
		}

		//Do nothing if the child already belongs to this container.
		if (in_array($info->getId(), $this->childrenIds, true)) {
			return;
		}

		//Set the new child.
		if ($info->getParentId() !== null) {
			throw new \RuntimeException("$this has been told to add child $info which already has parent {$info->getParentId()}.");
		}
		$info->setParent($this);
		$this->childrenIds[$name] = $info->getId();
		$this->notifyChange();
	}
}
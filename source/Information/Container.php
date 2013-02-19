<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

/**
 * Compound information quantum that structures multiple information quanta
 * together as key-value pairs.
 */

class Container extends Quantum
{
	protected $childrenIds = array();
	protected $type;

	/** Returns an associative array of name-id pairs of all children of this
	 * container. */
	public function getChildIds()
	{
		return $this->childrenIds;
	}

	/** Sets the associative array of name-id pairs of all children of this
	 * container. You should never have to call this method directly. */
	public function setChildIds(array $ids)
	{
		$this->childrenIds = $ids;
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
	public function setChildId($name, $id, $notify = true)
	{
		//Do nothing if the child already belongs to this container.
		if (in_array($id, $this->childrenIds, true)) {
			return;
		}

		//Remove the existing child.
		if ($childId = $this->getChildId($name)) {
			$child = $this->resolveId($childId);
			if ($child->getParentId() != $this->getId()) {
				throw new \RuntimeException("$this has child $child that has the wrong parent ID {$child->getParentId()}.");
			}
			$child->setParentId(null, $notify);
		}

		//Set the new child.
		$this->childrenIds[$name] = $id;
		if ($notify) $this->repository->notifyContainerAddedChild($this, $name);
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
	public function setChild($name, Quantum $info, $notify = true)
	{
		if ($info->getParentId() !== null) {
			throw new \RuntimeException("$this has been told to add child $info which already has parent {$info->getParentId()}.");
		}
		$info->setParent($this, $notify);
		$info->setName($name, $notify);
		$this->setChildId($name, $info->getId(), $notify);
	}

	/** Sets this container's type. */
	public function setType($type) { $this->type = $type; }

	/** Returns the type of this container. */
	public function getType() { return $this->type; }
}
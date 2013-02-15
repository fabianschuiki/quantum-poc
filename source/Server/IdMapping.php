<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Server;

/**
 * Maintains a mapping between local and global ids to be used for translation
 * in client communication. The server should maintain two instances of this
 * object for each client, one for upstream and one for downstream translation.
 */

class IdMapping
{
	public $localToGlobalIds = array();
	public $globalToLocalIds = array();

	/** Adds a mapping between the given local and the given global ID to this
	 * clients ID mapping table. */
	public function add($local, $global)
	{
		$this->localToGlobalIds[$local] = $global;
		$this->globalToLocalIds[$global] = $local;
	}

	/** Returns the local ID for the given global one. If no specific local ID
	 * exists, simply passes through the global id. */
	public function getLocalId($global)
	{
		if ($global < 0) return $global;
		$id = @$this->globalToLocalIds[$global];
		return $id !== null ? $id : $global;
	}

	/** Returns the global ID for the given local one. Throws an exception if
	 * the local ID is not known. */
	public function getGlobalId($local)
	{
		if ($local > 0) return $local;
		$id = @$this->localToGlobalIds[$local];
		if ($id) {
			throw new \RuntimeException("Local ID $local has no global correspondence.");
		}
		return $id;
	}
}
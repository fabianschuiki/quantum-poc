<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

use Server\IdMapping;

/**
 * Helper class that groups the functionality to encode and decode information
 * quanta.
 */

class Serializer
{
	/**
	 * Serializes the given information quantum into an XML representation.
	 */
	static public function encode(Quantum $quantum, IdMapping $mapping = null)
	{
		$encoded = array();

		//Common properties.
		$encoded["id"] = $quantum->getId();
		$encoded["type"] = $quantum->getType();
		if ($p = $quantum->getParentId()) $encoded["parent"] = ($mapping ? $mapping->getLocalId($p) : $p);
		if ($n = $quantum->getName()) $encoded["name"] = $n;

		//Subclasses.
		if ($quantum instanceof Container) {
			$ids = $quantum->getChildIds();
			if ($mapping) {
				$translated = array();
				foreach ($ids as $name => $id) {
					$translated[$name] = $mapping->getLocalId($id);
				}
			} else {
				$translated = $ids;
			}
			$encoded["children"] = $translated;
		}
		if ($quantum instanceof Raw) {
			$encoded["data"] = $quantum->getData();
		}
		if ($quantum instanceof String) {
			$encoded["string"] = $quantum->getString();
		}

		return json_encode($encoded);
	}

	/**
	 * Decodes an information quantum from the given XML representation.
	 */
	static public function decode($json, \Repository $repository, IdMapping $mapping = null)
	{
		$decoded = json_decode($json, true);
		if (!$decoded) {
			throw new \RuntimeException("Unable to decode $json.");
		}

		//Decode the different types.
		$quantum = null;
		switch ($decoded["type"]) {
			case "raw": {
				$quantum = new Raw($repository, $decoded["id"]);
				$quantum->setData($decoded["data"], false);
			} break;
			case "string": {
				$quantum = new String($repository, $decoded["id"]);
				$quantum->setString($decoded["string"], false);
			} break;
			default: {
				$quantum = new Container($repository, $decoded["id"]);
				$quantum->setType($decoded["type"], false);
				$ids = $decoded["children"];
				if ($mapping) {
					$translated = array();
					foreach ($ids as $name => $id) {
						$translated[$name] = $mapping->getGlobalId($id);
					}
				} else {
					$translated = $ids;
				}
				$quantum->setChildIds($translated, false);
			} break;
		}

		//Decode the common things.
		if ($quantum) {
			if (isset($decoded["parent"])) {
				$id = $decoded["parent"];
				$quantum->setParentId($mapping ? $mapping->getGlobalId($id) : $id, false);
			}
			if (isset($decoded["name"])) $quantum->setName($decoded["name"], false);
		}

		return $quantum;
	}
}
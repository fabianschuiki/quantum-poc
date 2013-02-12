<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Information;

/**
 * Helper class that groups the functionality to encode and decode information
 * quanta.
 */

class Serializer
{
	/**
	 * Serializes the given information quantum into an XML representation.
	 */
	static public function encode(Quantum $quantum)
	{
		$encoded = array();

		//Common properties.
		$encoded["id"] = $quantum->getId();
		$encoded["type"] = $quantum->getType();
		if ($p = $quantum->getParentId()) $encoded["parent"] = $p;
		if ($n = $quantum->getName()) $encoded["name"] = $n;

		//Subclasses.
		if ($quantum instanceof Container) {
			$encoded["children"] = $quantum->getChildIds();
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
	static public function decode($json)
	{
		$decoded = json_decode($json, true);
		if (!$decoded) {
			throw new \RuntimeException("Unable to decode $json.");
		}

		//Decode the different types.
		$quantum = null;
		switch ($decoded["type"]) {
			case "raw": {
				$quantum = new Raw($decoded["id"]);
				$quantum->setData($decoded["data"]);
			} break;
			case "string": {
				$quantum = new String($decoded["id"]);
				$quantum->setString($decoded["string"]);
			} break;
			default: {
				$quantum = new Container($decoded["id"], $decoded["type"]);
				$quantum->setChildrenIds($encoded["children"]);
			} break;
		}

		//Decode the common things.
		if ($quantum) {
			if (isset($decoded["parent"])) $quantum->setParentId($decoded["parent"]);
			if (isset($decoded["name"])) $quantum->setName($decoded["name"]);
		}

		return $quantum;
	}
}
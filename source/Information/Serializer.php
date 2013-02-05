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
			$encoded["data"] = base64_encode($quantum->getData());
		}
		if ($quantum instanceof String) {
			$encoded["string"] = $quantum->getString();
		}

		return json_encode($encoded);
	}

	/**
	 * Decodes an information quantum from the given XML representation.
	 */
	static public function decode()
	{

	}
}
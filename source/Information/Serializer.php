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
		if ($quantum instanceof Container) $name = "container";
		if ($quantum instanceof Raw) $name = "raw";
		if ($quantum instanceof String) $name = "string";

		//Common properties.
		$xml = new \SimpleXMLElement ("<$name/>");
		$xml->addAttribute("id", $quantum->getId());
		$xml->addAttribute("parent", $quantum->getParentId());
		$xml->addAttribute("name", $quantum->getName());

		//Subclasses.
		if ($quantum instanceof Container) {
			$xml->addAttribute("type", $quantum->getType());
			foreach ($quantum->getChildIds() as $childName => $childId) {
				$xml->addChild($childName, $childId);
			}
		}
		if ($quantum instanceof Raw) {
			$xml->addChild("data", $quantum->getData());
		}
		if ($quantum instanceof String) {
			$xml->addChild("string", $quantum->getString());
		}

		return $xml->asXML();
	}

	/**
	 * Decodes an information quantum from the given XML representation.
	 */
	static public function decode()
	{

	}
}
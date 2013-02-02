<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Caster;

/**
 * A very basic caster that casts quanta of type "raw" to quanta of type
 * "string" by interpreting the raw data as UTF8 encoded strings.
 */

class RawToString extends Caster
{
	static public function getInputType() { return "raw"; }
	static public function getOutputType() { return "string"; }
}
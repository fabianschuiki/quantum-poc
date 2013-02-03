<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Caster;

use Server;
use Information\Quantum;

/**
 * A very basic caster that casts quanta of type "raw" to quanta of type
 * "string" by interpreting the raw data as UTF8 encoded strings.
 */

class RawToString extends Caster
{
	public function __construct(Server $server, Quantum $input)
	{
		$output = $server->makeQuantum("Information\String");
		parent::__construct($server, $input, $output);
	}

	public function notifyInputChanged()
	{
		parent::notifyInputChanged();
		$this->getOutput()->setString(utf8_decode($this->getInput()->getData()));
	}

	public function notifyOutputChanged()
	{
		parent::notifyOutputChanged();
		$this->getInput()->setData(utf8_encode($this->getOutput()->getString()));
	}

	static public function getInputType() { return "raw"; }
	static public function getOutputType() { return "string"; }
}
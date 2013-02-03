<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Caster;

use Server;
use Information\Quantum;

/**
 * Casters are interfaces between two information quanta. They interpret a
 * given quantum and provide another quantum of a different type that tries to
 * maintain synchronization with the original quantum.
 */

abstract class Caster
{
	protected $server;
	protected $inputId;
	protected $outputId;

	public function __construct(Server $server, Quantum $input, Quantum $output)
	{
		$this->server = $server;
		$this->inputId = $input->getId();
		$this->outputId = $output->getId();
		$output->setName($input->getName());

		//Verify the input and output quantum types match.
		if ($input->getType() !== $this->getInputType()) {
			throw new \InvalidArgumentException("Caster $this requires input type {$this->getInputType()}, got $input instead.");
		}
		if ($output->getType() !== $this->getOutputType()) {
			throw new \InvalidArgumentException("Caster $this requires output type {$this->getOutputType()}, got $output instead.");
		}
	}

	public function __toString()
	{
		return "{$this->getInput()} -> {$this->getOutput()}";
	}

	/** Returns the input information quantum ID. */
	public function getInputId() { return $this->inputId; }

	/** Returns the output information quantum ID. */
	public function getOutputId() { return $this->outputId; }

	/** Returns the input information quantum. */
	public function getInput()
	{
		return $this->inputId ? $this->server->resolveQuantumId($this->inputId) : null;
	}

	/** Returns the output information quantum that the caster obtained by
	 * casting the input to the requested type. */
	public function getOutput()
	{
		return $this->outputId ? $this->server->resolveQuantumId($this->outputId) : null;
	}

	/**
	 * Notifies the caster that the input information quantum has changed and
	 * the cast output is likely to need recalculation.
	 */
	public function notifyInputChanged()
	{
		echo "Caster $this: Input changed.\n";
	}

	/**
	 * Notifies the caster that the output information was changed and the
	 * original input information is likely to need adjusting.
	 */
	public function notifyOutputChanged()
	{
		echo "Caster $this: Output changed.\n";
	}

	/** Returns the type of the input information quantum this caster requires. */
	abstract static public function getInputType();

	/** Returns the type of the output information quantum this caster provides. */
	abstract static public function getOutputType();
}
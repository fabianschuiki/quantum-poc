<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Caster;

use Information\Quantum;

/**
 * Casters are interfaces between two information quanta. They interpret a
 * given quantum and provide another quantum of a different type that tries to
 * maintain synchronization with the original quantum.
 */

abstract class Caster
{
	protected $input;
	protected $output;

	public function __construct(Quantum $input, Quantum $outpu)
	{
		$this->input = $input;
		$this->output = $output;

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
		return "{$this->input->getId()} -> {$this->getCastType()}";
	}

	/** Returns the input information quantum. */
	public function getInput() { return $this->input; }

	/** Returns the output information quantum that the caster obtained by
	 * casting the input to the requested type. */
	public function getOutput() { return $this->output; }

	/**
	 * Notifies the caster that the input information quantum has changed and
	 * the cast output is likely to need recalculation.
	 */
	public function notifyInputChanged()
	{
		echo "Caster: Input {$this->input} changed.";
	}

	/**
	 * Notifies the caster that the output information was changed and the
	 * original input information is likely to need adjusting.
	 */
	public function notifyOutputChanged()
	{
		echo "Caster: Output {$this->output} changed.";
	}

	/** Returns the type of the input information quantum this caster requires. */
	abstract static public function getInputType();

	/** Returns the type of the output information quantum this caster provides. */
	abstract static public function getOutputType();
}
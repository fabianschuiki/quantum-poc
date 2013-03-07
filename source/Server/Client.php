<?php
/* Copyright © 2013 Fabian Schuiki */
namespace Server;

/**
 * Maintains the information associated with a particular client connection
 * that the server needs to keep track of.
 */

class Client
{
	public $socket;
	public $upstreamMapping;
	public $downstreamMapping;
	public $knownIds = array();

	public function __construct()
	{
		$this->upstreamMapping = new IdMapping;
		$this->downstreamMapping = new IdMapping;
	}
}
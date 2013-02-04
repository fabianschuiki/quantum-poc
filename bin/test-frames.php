#!/usr/bin/php
<?php

/**
 * This script acts as a debugging server that accepts random communication
 * frames and returns a response to them.
 */

require_once __DIR__."/../source/autoload.php";

//Initialize the socket.
$socketPath = "/tmp/quantum-frames.sock";
@unlink($socketPath);
$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
if (!$socket) {
	throw new \RuntimeException("Unable to create socket.");
}
if (!socket_bind($socket, $socketPath)) {
	throw new \RuntimeException("Unable to bind to $socketPath.");
}
socket_listen($socket);

//Accept connections and perform the frame communication.
while (true) {
	$client = socket_accept($socket);
	echo "Client connected\n";

	/*//Read the header.
	$header = socket_read($client, 5);
	$header = unpack("Ctype/Llength", $header);
	$type = $header["type"];
	$length = $header["length"];
	echo "Expecting $length Bytes of data type $type\n";

	//Read the data.
	$data = socket_read($client, $length);
	echo "Received \"$data\"\n";*/

	//Create a new FrameSocket for this client.
	$done = false;
	$fs = new FrameSocket ($client, function(Frame $frame) use (&$done) {
	});

	//Send the welcome frame.
	$fs->writeFrame(new Frame (1, "Welcome, Client!"));

	while (true) {
		$r = array($fs->getSocket());
		$w = ($fs->wantsToWrite() ? $r : array());
		$e = null;
		socket_select($r, $w, $e, null);
		if (count($r)) {
			echo " <- reading\n";
			if (!$fs->read()) break;
		}
		if (count($w)) {
			echo " -> writing\n";
			$fs->write();
		}
	}

	echo "Client disconnected\n";
	socket_close($client);
}

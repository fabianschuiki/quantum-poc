#!/usr/bin/php
<?php
require_once __DIR__."/config.php";
require_once __DIR__."/iq.php";

//Wait for connections.
unlink(IQ_SOCKET);
$incoming = socket_create(AF_UNIX, SOCK_STREAM, 0) or die ("Unable to create socket.\n");
socket_bind($incoming, IQ_SOCKET) or die ("Unable to bind to ".IQ_SOCKET.".\n");
socket_listen($incoming);

while ($socket = socket_accept($incoming)) {
	echo "connection accepted\n";
	while (true) {
		//Receive a request object.
		$request_raw = socket_read($socket, 100000);
		if (!$request_raw) break;
		$request = unserialize($request_raw);

		echo "received request ".print_r($request, true)."\n";
	}
	echo "connection closed\n";
	socket_close($socket);
}

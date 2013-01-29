#!/usr/bin/php
<?php
require_once __DIR__."/config.php";
require_once __DIR__."/iq.php";

//Connect to the server.
$socket = fsockopen("unix://".IQ_SOCKET) or die ("Unable to connect to ".IQ_SOCKET.".\n");

//Request the root IQ object.
$request = new stdClass;
$request->type = "get-root-iq";
fwrite($socket, serialize($request));
fclose($socket);

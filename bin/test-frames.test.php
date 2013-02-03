#!/usr/bin/php
<?php

$socket = fsockopen("unix:///tmp/quantum-frames.sock") or die ("Unable to connect to socket.\n");
$string = "Hello World";
$data = pack("CL", 2, strlen($string)).$string;
fwrite($socket, pack("CL", 1, strlen($data)));
fwrite($socket, $data);

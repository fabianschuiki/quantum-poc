#!/usr/bin/php
<?php
/* Copyright © 2013 Fabian Schuiki */
require_once __DIR__."/../source/autoload.php";

// Create a new quantum repository.
$repo = new ClientRepository;
$repo->connect();

// Create a new string quantum.
$str = new Information\String ($repo);
$str->_path = "world.txt";
$str->setString(@file_get_contents($str->_path));
$str->addObserver(function(Information\Quantum $iq, $path) {
	echo "$iq changed '$path'\n";
	file_put_contents($iq->_path, $iq->getString());
});

// Get the root information object and set the string as one of its children.
$root = $repo->getRoot();
$root->setChild("local", $str);

// Enter the communication loop.
$repo->communicate();
#!/usr/bin/php
<?php
/* Copyright © 2013 Fabian Schuiki */
require_once __DIR__."/../source/autoload.php";

// Create a new quantum repository.
$repo = new ClientRepository;
$repo->connect();

// Get the root information object and get the debug string.
$root = $repo->getRoot();
$str = $root->getChild("debug");

// Alter the string!
$str->replaceString("Hallo", 0, 5);

// Enter the communication loop.
$repo->communicate();
#!/usr/bin/php
<?php
/* Copyright © 2013 Fabian Schuiki */
require_once __DIR__."/../source/autoload.php";

// Create a new quantum repository.
$repo = new ClientRepository;
$repo->connect();

// Get the root information object and get the debug string.
$root = $repo->getRoot();
$str = $root->getChild("local");

// Alter the string!
$str->setString(readline());

// Enter the communication loop.
$repo->communicate();
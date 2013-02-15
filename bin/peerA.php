#!/usr/bin/php
<?php
/* Copyright © 2013 Fabian Schuiki */
require_once __DIR__."/../source/autoload.php";

// Create a new quantum repository.
$repo = new ClientRepository;
$repo->connect();

// Create a new string quantum and add some data.
$str = new Information\String ($repo);
$str->setString("Hello World");
echo "Created IQ $str\n";

// Get the root information object and set the string as one of its children.
$root = $repo->getRoot();
echo "Done getting root.\n";
$root->setChild("debug", $str);
echo "Done setting child.\n";

// Enter the communication loop.
$repo->communicate();
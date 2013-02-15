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
$str->replaceString("Fo", 6, 1);
echo "Created IQ $str\n";

// Get the root information object and set the string as one of its children.
$root = $repo->getRoot();
$root->setChild("debug", $str);

// Enter the communication loop.
$repo->communicate();
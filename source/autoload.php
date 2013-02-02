<?php
/* Copyright © 2013 Fabian Schuiki */

//Interpret the namespace as directory hierarchy an load automatically.
spl_autoload_register(function($class) {
	$path = __DIR__.'/'.trim(str_replace('\\', '/', $class), '/').'.php';
	if (!file_exists($path)) return false;
	require_once $path;
});
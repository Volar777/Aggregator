<?php

function autoload($class)
{
	$filename = __DIR__ . '/classes/' .  str_replace('\\', '/', $class) . '.php';
	if (file_exists($filename)) {
		include $filename;
	}
}
spl_autoload_register('autoload');

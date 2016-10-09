<?php
//Autoloading
function autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$fileName;
}
spl_autoload_register('autoload');

//Load composer modules
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

//Load library
require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'getid3'.DIRECTORY_SEPARATOR.'getid3.php';

//Load environment
$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();
<?php
function autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	require $fileName;
}
spl_autoload_register('autoload');

use MusicManager\Discogs;
require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

//Read directories
$path = getenv('MUSIC_LIBRARY');
$handle = opendir($path);
$dirs = [];
while($entry = readdir($handle)) {
	if(is_dir($path.DIRECTORY_SEPARATOR.$entry) && substr($entry, 0, 1) !== '.') {
		$dirs[] = $entry;
	}
	echo $entry;
}
asort($dirs);

?>
<html>
	<head>
		<title>Music Manager</title>
	</head>
	<body>
		<ul>
			<? foreach($dirs as $dir) { ?>
				<li><?=$dir?> <input type="text" value=""></li>
			<? } ?>
		</ul>
	</body>
</html>

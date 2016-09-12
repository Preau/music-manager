<?php
require 'partials/head.php';

//Read directories
$path = getenv('MUSIC_LIBRARY');
$handle = opendir($path);
$dirs = [];
while($entry = readdir($handle)) {
	if(is_dir($path.DIRECTORY_SEPARATOR.$entry) && substr($entry, 0, 1) !== '.') {
		$dirs[] = utf8_encode($entry);
	}
}
natcasesort($dirs);
$dirs = array_values($dirs);

//Read library file
$library = \MusicManager\Library::readFile();
if($library == null) {
	//Create the library
	\MusicManager\Library::initFile($dirs);
}

?>
<html>
	<head>
		<title>Music Manager</title>
	</head>
	<body>
		<form method="post" action="">
			<button type="submit">Save everything</button>
			<? for($i = 0; $i < sizeof($dirs); $i++) {
				$dir = $dirs[$i]; ?>
				<div class="directory">
					<?=$dir?>
					<input type="hidden" name="dir[<?=$i?>]" value="<?=$dir?>">
					<input type="text" name="master[<?=$i?>]" value="">
				</div>
			<? } ?>
		</form>
	</body>
</html>

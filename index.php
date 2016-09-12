<?php
use MusicManager\Library;

require 'partials/head.php';

//Load the library
$library = Library::loadLibrary();

?>
<html>
	<head>
		<title>Music Manager</title>
	</head>
	<body>
		<form method="post" action="">
			<button type="submit">Save everything</button>
			<? $i = 1;
			foreach($library as $dir => $data) { ?>
				<div class="directory">
					<?=$dir?>
					<input type="hidden" name="dir[<?=$i?>]" value="<?=$dir?>">
					<input type="text" name="master[<?=$i?>]" value="">
					<a href="https://www.discogs.com/search?q=<?=urlencode($dir)?>&type=master" target="_blank">Search on Discogs</a>
				</div>
				<? $i++;
			} ?>
		</form>
	</body>
</html>

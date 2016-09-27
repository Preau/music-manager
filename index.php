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
		<form method="post" action="post.php">
			<button type="submit" name="request" value="savemasterids">Save everything</button>
			<? foreach($library as $number => $data) {
				if(!isset($data['deleted'])) { ?>
					<div class="directory">
						<?=$data['dir']?>
						<input type="text" name="master[<?=$number?>]" value="<?=(isset($data['master']) ? $data['master'] : '')?>">
						<a href="https://www.discogs.com/search?q=<?=urlencode($dir)?>&type=master" target="_blank">Search on Discogs</a>
					</div>
				<? }
			} ?>
		</form>
	</body>
</html>

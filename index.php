<?php
use MusicManager\Library;

require 'partials/head.php';

//Load the library
$library = Library::loadLibrary();

?>
<html>
	<head>
		<title>Music Manager</title>
		<script src="build/libraries.js" type="text/javascript"></script>
		<script src="build/script.js" type="text/javascript"></script>
	</head>
	<body>
		<form method="post" action="post.php">
			<button type="submit" name="request" value="savemasterids">Save everything</button>
			<? foreach($library as $number => $data) {
				if(!isset($data['deleted'])) { ?>
					<div class="directory">
						<?=$data['dir']?>
						<br>
						<input type="text" id="master-<?=$number?>" name="master[<?=$number?>]" value="<?=(isset($data['master']) ? $data['master'] : '')?>">
						<br>
						File genre: <span id="genre-file-<?=$number?>"></span>
						<br>
						Discogs genre: <span id="genre-discogs-<?=$number?>"></span>
						<br>
						<button type="button" onclick="checkGenres(<?=$number?>)">Find genre</button>
						<button type="button" id="save-genre-<?=$number?>" onclick="saveGenre(<?=$number?>)" disabled="disabled">Save genre</button>
						<br>
						<a href="https://www.discogs.com/search?q=<?=urlencode($data['dir'])?>&type=master" target="_blank">Search on Discogs</a>

						<br><br><br>
					</div>
				<? }
			} ?>
		</form>
	</body>
</html>

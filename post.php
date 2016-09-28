<?php
use MusicManager\Discogs;
use MusicManager\Library;

require 'partials/head.php';

switch($_POST['request']) {
	case 'savemasterids':
		saveMasterIds();
		break;
	case 'findgenre':
		findGenre();
		break;
	default:
		header('Location: index.php');
}
die();

function saveMasterIds() {
	Library::saveMasterIds($_POST);
	header('Location: index.php');
}

function findGenre() {
	$number = $_POST['number'];
	$library = Library::loadLibrary();

	if(isset($library[$number]) && isset($library[$number]['master'])) {
		$master = Discogs::getMaster($library[$number]['master']);
		echo json_encode($master);
		die();
	}

	echo json_encode(['message' => 'Master id not saved']);
}
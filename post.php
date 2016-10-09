<?php
use MusicManager\Discogs;
use MusicManager\Library;
use MusicManager\File;

require 'partials/head.php';

switch($_POST['request']) {
	case 'savemasterids':
		saveMasterIds();
		break;
	case 'findgenre':
		findGenre();
		break;
	case 'savegenre':
		saveGenre();
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

	try {
		if(!isset($library[$number])) {
			throw new Exception('Library item not found');
		}
		if(!isset($library[$number]['master'])) {
			throw new Exception('Master ID not set');
		}

		$master = Discogs::getMaster($library[$number]['master']);
		echo json_encode($master);

	} catch(Exception $e) {
		echo json_encode(['message' => $e->getMessage()]);
	}
}

function saveGenre() {
	$number = $_POST['number'];
	$library = Library::loadLibrary();

	try {
		if(!isset($library[$number])) {
			throw new Exception('Library item not found');
		}

		File::getGenre($library[$number]['dir']);

	} catch(Exception $e) {
		echo json_encode(['message' => $e->getMessage()]);
	}
}
<?php
ini_set('max_execution_time', 0);

use MusicManager\Discogs;
use MusicManager\Library;
use MusicManager\File;

require 'partials/head.php';

switch($_POST['request']) {
	case 'savemasterids':
		saveMasterIds();
		break;
	case 'checkgenres':
		checkGenres();
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

function checkGenres() {
	$number = $_POST['number'];
	$library = Library::loadLibrary();

	try {
		if(!isset($library[$number])) {
			throw new Exception('Library item not found');
		}
		if(!isset($library[$number]['master'])) {
			throw new Exception('Master ID not set');
		}

		//Retrieve from Discogs
		$master = Discogs::getMaster($library[$number]['master']);
		$discogs = array_unique(array_merge($master['genres'], $master['styles']));

		//Retrieve from files
		$file = File::readGenre($library[$number]['dir']);

		echo json_encode([
			'discogs' => $discogs,
			'file' => $file
		]);

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
		if(!$_POST['genres'] || !is_array($_POST['genres'])) {
			throw new Exception('Invalid request');
		}

		///Write the genres
		File::writeGenre($library[$number]['dir'], $_POST['genres']);

		echo json_encode(['success' => 1]);
	} catch(Exception $e) {
		echo json_encode(['message' => $e->getMessage()]);
	}
}
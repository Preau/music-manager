<?php
use MusicManager\Library;

require 'partials/head.php';

switch($_POST['request']) {
	case 'savemasterids':
		saveMasterIds();
		break;
	default:
		header('Location: index.php');
}
die();

function saveMasterIds() {
	Library::saveMasterIds($_POST);
	header('Location: index.php');
}
<?php
use MusicManager\Library;

require 'partials/head.php';

//Init twig
$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader, array(
	'cache' => __DIR__.'/_cache',
));

//Load the library
$library = Library::loadLibrary();

echo $twig->render('index.twig', [
	'library' => $library
]);
?>

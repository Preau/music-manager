<?php
namespace MusicManager;

class Library {
	const FILEPATH = __DIR__.'/../library.json';

	/**
	 * Load the library for usage
	 * @return array
	 */
	public static function loadLibrary() {
		$dirs = self::readDirectories();
		$library = self::readFile();

		if($library == null) {
			return self::initFile($dirs);
		}

		return self::compareLibraryAndDirectories($library, $dirs);
	}

	/**
	 * Read the library.json file if it exists
	 * @return array|null
	 */
	private static function readFile() {
		if(file_exists(self::FILEPATH)) {
			return json_decode(file_get_contents(self::FILEPATH), JSON_OBJECT_AS_ARRAY);
		} else {
			return null;
		}
	}

	/**
	 * Write data to the library.json file
	 * @param array $data
	 */
	private static function writeFile($data) {
		file_put_contents(self::FILEPATH, json_encode($data));
	}

	/**
	 * Initialise the library.json file
	 * @param array $dirs
	 * @return array
	 */
	private static function initFile($dirs) {
		$library = [];
		foreach($dirs as $dir) {
			$library[$dir] = [];
		}
		self::writeFile($library);
		return $library;
	}

	/**
	 * Read and return a list of directories from the music library
	 * @return array
	 */
	private static function readDirectories() {
		$path = getenv('MUSIC_LIBRARY');
		$handle = opendir($path);
		$dirs = [];
		while($entry = readdir($handle)) {
			if(is_dir($path.DIRECTORY_SEPARATOR.$entry) && substr($entry, 0, 1) !== '.') {
				$dirs[] = utf8_encode($entry);
			}
		}
		natcasesort($dirs);
		return array_values($dirs);
	}

	/**
	 * Compare the current library file with the library and change it accordingly
	 * @param array $library
	 * @param array $dirs
	 * @return array
	 */
	private static function compareLibraryAndDirectories($library, $dirs) {
		$libraryClone = $library;

		foreach($dirs as $dir) {
			if(!isset($library[$dir])) {
				//Doesn't exist in file add it
				$library[$dir] = [];
			} else {
				//Already exists in file, remove it from clone, remove possible deleted flag
				unset($libraryClone[$dir]);
				unset($library[$dir]['deleted']);
			}
		}

		//Everything remained in clone is deleted from library, add deleted flag
		foreach(array_keys($libraryClone) as $dir) {
			$library[$dir]['deleted'] = 1;
		}

		array_multisort(array_keys($library), SORT_NATURAL|SORT_FLAG_CASE, $library);
		self::writeFile($library);
		return $library;
	}

	/**
	 * Save master id for each item to library file
	 * @param array $post
	 */
	public static function saveMasterIds($post) {
		if(isset($post['dir']) && isset($post['master'])) {
			$library = Library::loadLibrary();

			foreach($post['dir'] as $number => $dir) {
				if(!empty($post['master'][$number])) {
					$library[$dir]['master'] = $post['master'][$number];
				}
			}

			self::writeFile($library);
		}
	}
}
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
		$count = 1;
		foreach($dirs as $dir) {
			$library[$count]['dir'] = $dir;
			$count++;
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
				$dirs[] = $entry;
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
		$dirsInLibrary = [];
		$maxnumber = 1;

		foreach($library as $number => $data) {
			if(!in_array($data['dir'], $dirs)) {
				//No folder but is in library, mark as deleted
				$library[$number]['deleted'] = 1;
			} else {
				//Both in folder and library
				unset($library[$number]['deleted']);
			}
			$dirsInLibrary[] = $data['dir'];

			if($number > $maxnumber) {
				$maxnumber = $number;
			}
		}

		foreach($dirs as $dir) {
			if(!in_array($dir, $dirsInLibrary)) {
				//New folder not yet in library, add it
				$maxnumber++;
				$library[$maxnumber]['dir'] = $dir;
			}
		}

		//Sort by folder name
		uasort($library, function($a, $b) {
			return strnatcasecmp($a['dir'], $b['dir']);
		});

		self::writeFile($library);
		return $library;
	}

	/**
	 * Save master id for each item to library file
	 * @param array $post
	 */
	public static function saveIds($post) {
		$library = Library::loadLibrary();

		if(isset($post['master'])) {
			foreach($post['master'] as $number => $masterId) {
				if(isset($library[$number])) {
					if(empty($masterId)) {
						unset($library[$number]['master']);
					} else {
						$library[$number]['master'] = $masterId;
					}
				}
			}
		}

		if(isset($post['release'])) {
			foreach($post['release'] as $number => $releaseId) {
				if(isset($library[$number])) {
					if(empty($releaseId)) {
						unset($library[$number]['release']);
					} else {
						$library[$number]['release'] = $releaseId;
					}
				}
			}
		}

		self::writeFile($library);
	}
}
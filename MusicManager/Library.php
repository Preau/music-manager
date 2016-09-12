<?php
namespace MusicManager;

class Library {
	const FILEPATH = __DIR__.'/../library.json';

	/**
	 * @return array|null
	 */
	public static function readFile() {
		if(file_exists(self::FILEPATH)) {
			return json_decode(file_get_contents(self::FILEPATH), JSON_OBJECT_AS_ARRAY);
		} else {
			return null;
		}
	}

	public static function writeFile($data) {
		file_put_contents(self::FILEPATH, json_encode($data));
	}

	/**
	 * @param array $dirs
	 */
	public static function initFile($dirs) {
		$library = [];
		foreach($dirs as $dir) {
			$library[$dir] = [];
		}
		self::writeFile($library);
	}
}
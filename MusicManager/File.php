<?php
namespace MusicManager;

class File {
	/**
	 * Read all tag info from a file
	 * @param string $path
	 * @return array
	 */
	private static function readTags($path) {
		$getId3 = new \getID3();
		$info = $getId3->analyze($path);
		return $info;
	}

	/**
	 * Get the genre for files in the specified path
	 * Only checks one .mp3 file
	 * @param string $path
	 * @return array
	 */
	public static function readGenre($path) {
		$dir    = getenv('MUSIC_LIBRARY') . DIRECTORY_SEPARATOR . $path;
		$handle = opendir($dir);
		while($entry = readdir($handle)) {
			if(substr($entry, -4) == '.mp3') {
				//Get current tags
				$filepath = $dir . DIRECTORY_SEPARATOR . $entry;
				$info     = self::readTags($filepath);

				//Get genre from tags
				$genre = [];
				if(isset($info['tags']['id3v2']['genre'])) {
					$genre = $info['tags']['id3v2']['genre'];
				}
				return $genre;
			}
		}

		return [];
	}

	/**
	 * Write genres to all .mp3 files in the path
	 * @param string $path
	 * @param array $genres
	 */
	public static function writeGenre($path, $genres) {
		$dir    = getenv('MUSIC_LIBRARY') . DIRECTORY_SEPARATOR . $path;
		$handle = opendir($dir);
		while($entry = readdir($handle)) {
			if(substr($entry, -4) == '.mp3') {
				//Get current tags
				$filepath = $dir . DIRECTORY_SEPARATOR . $entry;
				$info     = self::readTags($filepath);

				//Append new genres
				$tags          = $info['tags']['id3v2'];
				$tags['genre'] = [
					implode("\x00", $genres)
				];

				//Write to file
				$getId3 = new \getID3();
				\getid3_lib::IncludeDependency(GETID3_INCLUDEPATH . 'write.php', __FILE__, true);
				$tagwriter                    = new \getid3_writetags();
				$tagwriter->filename          = $filepath;
				$tagwriter->tag_encoding      = 'UTF-8';
				$tagwriter->tagformats        = ['id3v2.3'];
				$tagwriter->overwrite_tags    = true;
				$tagwriter->remove_other_tags = false;
				$tagwriter->tag_data          = $tags;
				$tagwriter->WriteTags();
			}
		}
	}
}
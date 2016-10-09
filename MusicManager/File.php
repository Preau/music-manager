<?php
namespace MusicManager;

class File {
	/**
	 * Get the genre for files in the specified path
	 * Only checks first .mp3 file
	 * @param $path
	 */
	public static function getGenre($path) {
		$dir = getenv('MUSIC_LIBRARY').DIRECTORY_SEPARATOR.$path;
		$handle = opendir($dir);
		$dirs = [];
		while($entry = readdir($handle)) {
			if(substr($entry, -4) == '.mp3') {
				$filepath = $dir.DIRECTORY_SEPARATOR.$entry;

				$getId3                       = new \getID3();
				$info = $getId3->analyze($filepath);

				$tagData = array_merge($info['tags']['id3v2'], $info['tags']['id3v1']);
				$tagData['genre'] = [implode("\x00", [
					'Electronic',
					'Hip Hop',
					'Electro'
				])];

				print_r($tagData);

				\getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);
				$tagwriter                    = new \getid3_writetags();
				$tagwriter->filename          = $filepath;
				$tagwriter->tag_encoding      = 'UTF-8';
				$tagwriter->tagformats        = ['id3v2.3'];
				$tagwriter->overwrite_tags    = true;
				$tagwriter->remove_other_tags = false;
				$tagwriter->tag_data          = $tagData;
				$tagwriter->WriteTags();
				print_r($tagwriter->errors);
				print_r($tagwriter->warnings);

//				echo json_encode($info['tags']);

				break;
			}
		}
	}
}
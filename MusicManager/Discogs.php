<?php
namespace MusicManager;

class Discogs {
	const BASE_URL = 'https://api.discogs.com/';

	/**
	 * Do a request to Discogs API
	 * @param string $url
	 * @return array
	 */
	private static function request($url) {
		$ch = curl_init(self::BASE_URL . $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'User-Agent: music-manager/1.0.0',
			'Authorization: Discogs token=' . getenv('DISCOGS_TOKEN')
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);

		//Check if an error occured
		if(curl_errno($ch) !== 0) {
			return ['error' => curl_errno($ch) . ': ' . curl_error($ch)];
		}

		return json_decode($response, JSON_OBJECT_AS_ARRAY);
	}

	/**
	 * Get the data for a master release
	 * @param int $identifier
	 * @return array
	 */
	public static function getMaster($identifier) {
		return self::request('masters/'.$identifier);
	}
}
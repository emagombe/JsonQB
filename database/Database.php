<?php 


namespace database;

use database\Settings;
use PDO;

class Database {
	
	public static $connection_object = [];

	public static function get_connection_object($connection_object) {
		self::$connection_object = $connection_object;
	}

	public function conn() {
		$config = (object) self::$connection_object;

		$host = $config->host;
		$database = $config->database;
		$port = $config->port;
		$charset = $config->charset;
		$username = $config->username;
		$password = $config->password;

		try {
			return new PDO("mysql:host=$host;dbname=$database;port=$port;charset=$charset", $username, $password);
		} catch(Exception $ex) {
			throw new Exception($ex->getMessage());
		}
	}
}
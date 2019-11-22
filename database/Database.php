<?php 


namespace database;

use database\Settings;
use PDO;

class Database
{
	
	function __construct() {
		return $this;
	}

	public function conn() {
		$config = $this->read_conf();

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

	private function read_conf() {
		$content = file_get_contents(dirname(__FILE__)."/../app.conf");
		$content = explode("\n", $content);
		$config = [];
		foreach($content as $item) {
			$items = explode("=", $item);
			$config[strtolower($items[0])] = $items[1];
		}
		$settings = new Settings();
		$settings->database = str_replace("\r", '', $config['database']);
		$settings->username = str_replace("\r", '', $config['username']);
		$settings->password = str_replace("\r", '', $config['password']);
		$settings->charset = str_replace("\r", '', $config['charset']);
		$settings->host = str_replace("\r", '', $config['host']);
		$settings->port = str_replace("\r", '', $config['port']);
		return $settings;
	}
}
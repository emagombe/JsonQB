<?php 

namespace security;

use database\Database;

class Security extends Database {

	public static function escape_string($string) {
		$db = Database::conn();
		$str = $db->quote($string);
		$str = rtrim($str, "'");
		$str = substr($str, 1);
		return $str;
	}
	public static function escape($string) {
		$db = Database::conn();
		$str = $db->quote($string);
		$str = ltrim(rtrim($str, "'"), "'");
		return $str;
	}
}
<?php 

namespace queryBuilder\query;

use database\Database;
use security\Security;

class Insert {

	private $sql = "";

	function __construct($table, $request) {
		$sql = "INSERT INTO $table(";
		foreach ($request['value'] as $key => $value) {
			$key = Security::escape($key);
			$value = Security::escape($value);
			$sql = $sql."$key, ";
		}
		$sql = rtrim($sql,", ");
		$sql = $sql.") VALUES (";
		foreach ($request['value'] as $key => $value) {
			$key = Security::escape($key);
			$value = Security::escape($value);
			$sql = $sql."'$value',";
		}
		$sql = rtrim($sql,",");
		$sql = $sql.");";
		$this->sql = $sql;
		return $this;
	}

	public function sql() {
		return $this->sql;
	}
}
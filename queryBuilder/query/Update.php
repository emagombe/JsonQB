<?php 

namespace queryBuilder\query;

use database\Database;
use security\Security;

class UPDATE {

	private $sql = "";

	function __construct($table, $request) {
		$sql = "UPDATE $table SET ";
		foreach ($request['value'] as $key => $value) {
			$key = Security::escape($key);
			$value = Security::escape($value);

			$sql = $sql."$key = '$value', ";
		}
		$sql = rtrim($sql, ", ");
		if(isset($request["where"])) {
			$sql = $sql." WHERE ";
			foreach ($request["where"] as $key => $value) {
				$key = Security::escape($key);
				$value = Security::escape($value);
				$sql = $sql."$key = '$value' AND ";
			}
			$sql = rtrim($sql,"AND ");
			$sql = $sql.";";
			$this->sql = $sql;
			return $this;
		}
		$sql = $sql.";";
		$this->sql = $sql;
		return $this;
	}

	public function sql() {
		return $this->sql;
	}
}
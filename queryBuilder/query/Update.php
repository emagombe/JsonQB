<?php 

namespace queryBuilder\query;

use database\Database;
use security\Security;
use queryBuilder\query\Where;

class Update {

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

			/* Attaching where condition from Where class */
			$where = new Where($request["where"]);
			$sql .= $where->sql();
		}
		$sql = $sql.";";
		$this->sql = $sql;
		return $this;
	}

	public function sql() {
		return $this->sql;
	}
}
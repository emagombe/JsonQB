<?php 

namespace queryBuilder\query;

use database\Database;
use security\Security;
use queryBuilder\query\Where;

class Delete {

	private $sql = "";

	function __construct($table, $request) {
		$sql = "DELETE FROM $table";
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
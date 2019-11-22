<?php 

namespace queryBuilder\query;

use database\Database;
use security\Security;

class Truncate {

	private $sql = "";

	function __construct($table) {
		$table = Security::escape($table);
		$sql = "TRUNCATE $table";
		$sql = $sql.";";
		$this->sql = $sql;
		return $this;
	}

	public function sql() {
		return $this->sql;
	}
}
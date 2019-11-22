<?php 

namespace queryBuilder;

use security\Security;
use queryBuilder\query\Insert;
use queryBuilder\query\Update;
use queryBuilder\query\Delete;
use queryBuilder\query\Select;
use queryBuilder\query\Truncate;

class JsonQB {

	public static function Insert($table, $request) {
		return new Insert($table, $request);
	}

	public static function Update($table, $request) {
		return new Update($table, $request);
	}

	public static function Delete($table, $request) {
		return new Delete($table, $request);
	}

	// Delete all data from table and reset the auto increments
	public static function Truncate($table) {
		return new Truncate($table);
	}

	public static function Select($request) {
		return new Select($request);
	}
}
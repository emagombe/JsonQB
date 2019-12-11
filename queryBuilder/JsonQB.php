<?php 

namespace queryBuilder;

use database\Database;
use security\Security;
use queryBuilder\query\Insert;
use queryBuilder\query\Update;
use queryBuilder\query\Delete;
use queryBuilder\query\Select;
use queryBuilder\query\Truncate;

use queryBuilder\QueryProcessor;

use Exception;

class JsonQB extends QueryProcessor {

	public static function connect($connection_object) {
		Database::get_connection_object($connection_object);
	}

	public static function begin() {
		try {
			return parent::getConn()->beginTransaction();
		} catch(Exception $ex) {
			throw new Exception("Failed to begin transaction: ".$ex->getMessage(), 1);
		}
	}

	public static function rollback() {
		try {
			return parent::getConn()->rollBack();
		} catch(Exception $ex) {
			throw new Exception("Failed to roll back: ".$ex->getMessage(), 1);
		}
	}

	public static function commit() {
		try {
			return parent::getConn()->commit();
		} catch(Exception $ex) {
			throw new Exception("Failed to commit: ".$ex->getMessage(), 1);
		}
	}

	public static function Insert($table, $request) {
		return parent::process('insert', new Insert($table, $request));
	}

	public static function Update($table, $request) {
		return parent::process('update', new Update($table, $request));
	}

	public static function Delete($table, $request) {
		return parent::process('delete', new Delete($table, $request));
	}

	// Delete all data from table and reset the auto increments
	public static function Truncate($table) {
		return parent::process('truncate', new Truncate($table));
	}

	public static function Select($request) {
		return parent::process('select', new Select($request));
	}
}
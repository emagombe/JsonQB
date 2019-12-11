<?php

namespace queryBuilder;

use database\Database;
use StdClass;

class QueryProcessor extends Database {

	protected static $conn = null;
	protected static $process_type;
	protected static $build_result;

	protected static function getConn() {
		/* return create new connection if it does not exist */
		return (self::$conn != null) ? self::$conn : (function() {
			self::$conn = parent::conn();
			return self::$conn;
		})();
	}

	protected static function process($process_type, $build_result) {
		self::$process_type = $process_type;
		self::$build_result = $build_result;

		$process = new class extends QueryProcessor {
			public $sql;
			public function execute() {
				return parent::filter(parent::$process_type, parent::$build_result);
			}
		};
		$process->sql = $build_result->sql();
		return $process;
	}

	protected static function filter($process_type, $build_result) {
		switch($process_type) {
			case 'insert':
				return self::insert($build_result->sql());
				break;
			case 'select':
				return self::select($build_result->sql());
				break;
			case 'update':
				return self::default($build_result->sql());
				break;
			case 'delete':
				return self::default($build_result->sql());
				break;
			case 'truncate':
				return self::default($build_result->sql());
				break;
			default :
				return self::default($build_result->sql());
				break;
		}
	}

	private static function default($sql) {
		$conn = self::getConn();
		$stmt = $conn->prepare($sql);

		$result = new StdClass();
		$result->success = $stmt->execute();
		$result->sql = $sql;
		return $result;
	}

	private static function insert($sql) {
		$conn = self::getConn();
		$stmt = $conn->prepare($sql);

		$result = new StdClass();
		$result->success = $stmt->execute();
		$result->sql = $sql;
		$result->id = $conn->lastInsertId();
		return $result;
	}

	private static function select($sql) {
		$stmt = self::getConn()->prepare($sql);
		$result = new StdClass();

		if(!$stmt->execute()) { return $result; }

		$data = [];
		foreach($stmt as $key => $value) {
			
			$filtered_value = [];
			/* Removing numeric indexes from result */
			foreach((array) $value as $_key => $_value) {

				if(is_int($_key)) { continue; }
				$filtered_value[$_key] = $_value;
			}
			$data[$key] = $filtered_value;
		}

		$result->sql = $sql;
		$result->data = $data;
		$result->json = json_encode($data);
		$result->object = (object) $data;
		return $result;
	}
}
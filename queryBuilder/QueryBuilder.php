<?php 

namespace queryBuilder;

use security\Security;
use queryBuilder\query\Insert;
use queryBuilder\query\Update;

class QueryBuilder {

	public static function Insert($table, $request) {
		return new Insert($table, $request);
	}

	public static function Update($table, $request) {
		return new Update($table, $request);
	}


	public static function Delete($table, $request) {
		$table = Security::escape_string($table);

		$sql = "DELETE FROM `$table` ";

		if(isset($request["where"])) {
			$sql = $sql." WHERE ";
			foreach ($request["where"] as $key => $value) {
				$key = Security::escape_string($key);
				$value = Security::escape_string($value);
				
				$sql = $sql."`$key`='$value' AND ";
			}
		} else {
			$sql = rtrim($sql," ");
			$sql = $sql.";";
			return $sql;
		}
		$sql = rtrim($sql,"AND ");
		$sql = $sql.";";
		return new QueryBuilderHelper($sql);
	}

	// Delete all data from table and reset the auto increments
	public static function Truncate($table) {
		$table = Security::escape_string($table);
		$sql = "TRUNCATE `$table`;";
		return $sql;
	}

	private function _Join($array) {

	}

	public static function Select($request) {
		$sql = "SELECT ";
		foreach($request['columns'] as $value) {
			$value = Security::escape_string($value);
			if(Helper::contains($value, " as ")) {
				$array = explode(' as ', $value);
				$key = $array[0];
				$value = $array[1];
				$sql .= (Helper::contains($key, ".") and !Helper::contains($key, "(") and !Helper::contains($value, "*")) ? "`".explode('.', $key)[0]."`"."."."`".explode('.', $key)[1]."` as `$value`, " : "$key as $value, ";
			} else {
				$sql .= (Helper::contains($value, ".") and !Helper::contains($value, "(") and !Helper::contains($value, "*")) ? "`".explode('.', $value)[0]."`"."."."`".explode('.', $value)[1]."`, " : "$value, ";
			}
		}
		$sql = rtrim($sql,", ");

		if(isset($request['from'])) {
			$sql = $sql." FROM ";
			foreach($request['from'] as $value) {
				$value = Security::escape_string($value);
				$sql = $sql."`$value`, ";
			}
			$sql = rtrim($sql,", ");
		}

		if(isset($request["join"])) {

			foreach ($request["join"] as $key_join => $value_join) {
				/* Only if table is set */
				if(isset($value_join['table'])) {

					$table = Security::escape_string($value_join['table']);

					if(isset($value_join['on'])) {
						$sql .= " ".strtoupper($key_join)." JOIN `$table` ON ";

						/* Running the on array */
						foreach ($value_join['on'] as $items) {
							/* Setting the operator */
							$operator = (isset($items['operator'])) ? Security::escape_string($items['operator']) : '=';

							/* Attaching between */
							if((isset($settings['between']) or isset($settings['not between'])) and isset($settings['column'])) {
								$between = [];
								$column = Security::escape_string($settings['column']);
								$between[0] = Security::escape_string((isset($settings['not between'])) ? $settings['not between'][0] : $settings['between'][0]);
								$between[1] = Security::escape_string((isset($settings['not between'])) ? $settings['not between'][1] : $settings['between'][1]);

								$sql .= (Helper::contains($column, ".")) ? "`".explode('.', $column)[0]."`"."."."`".explode('.', $column)[1]."` " : "`$column` ";
								$sql .= ((isset($settings['not between'])) ? "NOT BETWEEN" : "BETWEEN")." ".$between[0]." AND ".$between[1]." AND ";
							} else {

								/* Setting The columns of the where clouse */
								foreach ($items['columns'] as $key => $value) {
									$key = Security::escape_string($key);
									$value = Security::escape_string($value);

									$sql .= (Helper::contains($key, ".") and !Helper::contains($key, "(")) ? "`".explode('.', $key)[0]."`"."."."`".explode('.', $key)[1]."`" : "`$key`";
									$sql .= (Helper::contains($value, ".") and !Helper::contains($value, "(")) ? " $operator `".explode('.', $value)[0]."`"."."."`".explode('.', $value)[1]."` AND " : " $operator $value AND ";
								}
							}
						}
					}
					//print_r($value_join); die();
					if(isset($value_join['in'])) {
						$sub_query = $value_join['in'];
						$sql .= "`$table` IN ($sub_query) ";
					}
					if(isset($value_join['not in'])) {
						$sub_query = $value_join['in'];
						$sql .= "$table NOT IN ($sub_query) ";
					}
					$sql = rtrim($sql,"AND ");
				}
				$sql = rtrim($sql,"AND ");
			}
			$sql = rtrim($sql,"AND ");
		}

		if(isset($request["where"])) {
			$sql = $sql." WHERE ";
			foreach ($request["where"] as $settings) {
				/* Setting the operator */
				$operator = (isset($settings['operator'])) ? Security::escape_string($settings['operator']) : '=';

				/* Attaching the IN clause to the select */
				if((isset($settings['in']) or isset($settings['not in'])) and isset($settings['column'])) {
					$sql .= (isset($settings['in'])) ? Security::escape_string($settings['column'])." IN (".rtrim(Security::escape_string($settings['in']), ";").")" : "";
					$sql .= (isset($settings['not in'])) ? Security::escape_string($settings['column'])." IN (".rtrim(Security::escape_string($settings['in']), ";").")" : "";
				} else

				/* Attaching between */
				if((isset($settings['between']) or isset($settings['not between'])) and isset($settings['column'])) {
					$between = [];
					$column = Security::escape_string($settings['column']);
					$between[0] = Security::escape_string((isset($settings['not between'])) ? $settings['not between'][0] : $settings['between'][0]);
					$between[1] = Security::escape_string((isset($settings['not between'])) ? $settings['not between'][1] : $settings['between'][1]);

					$sql .= (Helper::contains($column, ".")) ? "`".explode('.', $column)[0]."`"."."."`".explode('.', $column)[1]."` " : "`$column` ";
					$sql .= ((isset($settings['not between'])) ? "NOT BETWEEN" : "BETWEEN")." ".$between[0]." AND ".$between[1]." AND ";
				} else {

					/* Setting The columns of the where clause */
					foreach ($settings['columns'] as $key => $value) {
						$key = Security::escape_string($key);
						$value = Security::escape_string($value);

						$sql .= (Helper::contains($key, ".") and !Helper::contains($key, "(")) ? "`".explode('.', $key)[0]."`"."."."`".explode('.', $key)[1]."`" : "`$key`";
						$sql .= (Helper::contains($value, ".") and !Helper::contains($value, "(")) ? " $operator `".explode('.', $value)[0]."`"."."."`".explode('.', $value)[1]."` AND " : " $operator $value AND ";
					}
				}
			}
			$sql = rtrim($sql,"AND ");

			// Insert group by creteria to the select query
			if(isset($request['group'])) {
				if(isset($request['group']['by'])) {
					$by = Security::escape_string($request['group']['by']);
					$by = (Helper::contains($by, ".")) ? "`".explode('.', $by)[0]."`"."."."`".explode('.', $by)[1]."`" : "`$by`";
					$sql = $sql." GROUP BY ";
					$sql .= $by;
				}
			}

			// Insert order by creteria to the select query
			if(isset($request['order'])) {
				$order = Security::escape_string($request['order']['order']);

				if(isset($request['order']['by'])) {

					$by = Security::escape_string($request['order']['by']);
					$by = (Helper::contains($by, ".")) ? "`".explode('.', $by)[0]."`"."."."`".explode('.', $by)[1]."`" : "`$by`";

					$sql = $sql." ORDER BY ";
					$sql .= $by." ".((isset($request["order"]["order"])) ? $order : "ASC");
				}
			}
		}
		$sql = $sql.";";
		return $sql;
	}
}

// print_r(QueryBuilder::Select([
// 	'columns' => ['user.first', 'user.username', 'user.*'],
// 	'from' => ['user'],
// 	'where' => [
// 		[
// 			'column' => 'user.id',
// 			'between' => ['1', '7'] 
// 		],
// 		[
// 			'operator' => 'like',
// 			'columns' => [
// 				'user.first' => "'%e%'",
// 			],
// 		],
// 		[
// 			'columns' => [
// 				'user.id' => '7'
// 			]
// 		],
// 		[
// 			'column' => 'user.id',
// 			'in' => QueryBuilder::select(array(
// 				'columns' => ['user.id'],
// 				'from' => ['user']
// 			)),
// 		],
// 	],
// 	'order' => ['by' => 'user.id', 'order' => 'asc'],
// 	'group' => ['by' => 'user.id'],
// 	'join' => [
// 		'INNER' => array(
// 			'table' => 'warehouse',
// 			'on' => array(
// 				array(
// 					'columns' => array(
// 						'warehouse.user_created' => 'user.id'
// 					),
// 				)
// 			),
// 		),
// 		'LEFT' => [
// 			'table' => 'bank',
// 			'on' => [
// 				[
// 					'columns' => [
// 						'bank.user_created' => 'user.id'
// 					],
// 				],
// 			],
// 		],
// 		'INNER' => [
// 			'table' => 'cash',
// 			'on' => [
// 				[
// 					'operator' => 'like',
// 					'columns' => [
// 						'cash.user_created' => 'user.id'
// 					],
// 				]
// 			],
// 		],
// 	]
// ]));

// print_r(QueryBuilder::Truncate("user"));

/*print_r(QueryBuilder::Delete("user", 
	array(
		"value" => array('first' => 'Edson'),
		#"where" => array("id" => "7")
	)
));*/
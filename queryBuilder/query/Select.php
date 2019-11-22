<?php 

namespace queryBuilder\query;

use database\Database;
use security\Security;
use queryBuilder\query\Where;

class Select {

	private $sql = "";

	function __construct($request) {

		$sql = "SELECT ";
		/* Mapping the selected columns */
		foreach($request['columns'] as $value) {
			$sql .= Security::escape($value).", ";
		}
		$sql = rtrim($sql,", ");

		/* Mapping the tables from the "from" clause */
		if(isset($request['from'])) {
			$sql = $sql." FROM ";
			foreach($request['from'] as $value) {
				$sql .= Security::escape($value).", ";
			}
			$sql = rtrim($sql,", ");
		}
		
		if(isset($request["join"])) {

			foreach ($request["join"] as $key_join => $value_join) {
				/* Only if table is set */
				if(isset($value_join['table'])) {

					$table = Security::escape($value_join['table']);

					if(isset($value_join['on'])) {
						$sql .= " ".strtoupper($key_join)." JOIN `$table` ON ";

						/* Running the on array */
						foreach ($value_join['on'] as $items) {
							/* Setting the operator */
							$operator = (isset($items['operator'])) ? Security::escape($items['operator']) : '=';

							/* Attaching between */
							if((isset($settings['between']) or isset($settings['not between'])) and isset($settings['column'])) {
								$between = [];
								$column = Security::escape($settings['column']);
								$between[0] = Security::escape((isset($settings['not between'])) ? $settings['not between'][0] : $settings['between'][0]);
								$between[1] = Security::escape((isset($settings['not between'])) ? $settings['not between'][1] : $settings['between'][1]);

								$sql .= "$column ";
								$sql .= ((isset($settings['not between'])) ? "NOT BETWEEN" : "BETWEEN")." ".$between[0]." AND ".$between[1]." AND ";
							} else {

								/* Setting The columns of the where clouse */
								foreach ($items['columns'] as $key => $value) {
									$key = Security::escape($key);
									$value = Security::escape($value);

									$sql .= $key;
									$sql .= " $operator $value AND ";
								}
							}
						}
					}

					/* In clause => attaching subquery */
					if(isset($value_join['in'])) {
						$sub_query = $value_join['in'];
						$sql .= "$table IN ($sub_query) ";
					}
					/* Not In clause => attaching subquery */
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

			/* Attaching where condition */
			$where = new Where($request["where"]);
			$sql .= $where->sql();
		}
		$this->sql = $sql;
		return $this;
	}

	public function sql() {
		return $this->sql;
	}
}
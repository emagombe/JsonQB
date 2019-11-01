<?php

namespace queryBuilder\query;

use database\Database;
use security\Security;

class Where {

	private $sql = "";

	function __construct($request) {
		$sql = "";
		$sql = $sql." WHERE ";
		foreach ($request as $settings) {
			/* Setting the operator */
			$operator = (isset($settings['operator'])) ? Security::escape($settings['operator']) : '=';

			/* Attaching the IN clause to the select */
			if((isset($settings['in']) or isset($settings['not in'])) and isset($settings['column'])) {
				$sql .= (isset($settings['in'])) ? Security::escape($settings['column'])." IN (".rtrim(Security::escape($settings['in']), ";").")" : "";
				$sql .= (isset($settings['not in'])) ? Security::escape($settings['column'])." IN (".rtrim(Security::escape($settings['in']), ";").")" : "";
			} else {
				/* Attaching between */
				if((isset($settings['between']) or isset($settings['not between'])) and isset($settings['column'])) {
					$between = [];
					$column = Security::escape($settings['column']);
					$between[0] = Security::escape((isset($settings['not between'])) ? $settings['not between'][0] : $settings['between'][0]);
					$between[1] = Security::escape((isset($settings['not between'])) ? $settings['not between'][1] : $settings['between'][1]);

					$sql .= "$column ";
					$sql .= ((isset($settings['not between'])) ? "NOT BETWEEN" : "BETWEEN")." ".$between[0]." AND ".$between[1]." AND ";
				} else {
					/* Setting The columns of the where clause */
					foreach ($settings['columns'] as $key => $value) {
						$key = Security::escape($key);
						$value = Security::escape($value);

						$sql = $sql."$key $operator '$value' AND ";
					}
				}
			}
		}
		$sql = rtrim($sql,"AND ");

		// Insert group by creteria to the select query
		if(isset($request['group'])) {
			if(isset($request['group']['by'])) {
				$by = Security::escape($request['group']['by']);
				$by = "$by";
				$sql = $sql." GROUP BY ";
				$sql .= $by;
			}
		}

		// Insert order by creteria to the select query
		if(isset($request['order'])) {
			$order = Security::escape($request['order']['order']);

			if(isset($request['order']['by'])) {

				$by = Security::escape($request['order']['by']);
				$by = "$by";

				$sql = $sql." ORDER BY ";
				$sql .= $by." ".((isset($request["order"]["order"])) ? $order : "ASC");
			}
		}

		$this->sql = $sql;
		return $this;
	}

	public function sql() {
		return $this->sql;
	}
}
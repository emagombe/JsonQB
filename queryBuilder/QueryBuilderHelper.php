<?php

class QueryBuilderHelper {
	
	var $state = "";

	function __construct($first_statement)
	{
		$this->state = $first_statement;
	}

	function attach_where($condition_statement) {
		if(!Helper::contains(strtolower($this->state), "where")) {
			$condition_statement = Helper::endsWith($condition_statement, ";") ? $condition_statement : $condition_statement . ";";
			$str = rtrim($this->state, ";") . " WHERE " . $condition_statement;
			return $str;
		} else {
			return $this->state;
		}
	}
}
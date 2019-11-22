<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

$sql = JQB::Select(array(
	"columns" => array("user.*", "user_type.*"),
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"columns" => array(
				"user.id" => "1"
			)
		),
		array(
			"column" => "user.id",
			"between" => array(1, 7) 
		)
	)
))->sql();

print_r($sql);

# Returns
# SELECT user.*, user_type.* FROM user, user_type WHERE user.id = '1' AND user.id BETWEEN 1 AND 7
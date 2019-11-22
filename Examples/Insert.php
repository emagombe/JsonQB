<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

$sql = JQB::Insert('user',
	array(
		"value" => array(
			"username" => "JsonQB",
			"password" => "123",
			"email" => "example@example.net"
		)
	)
)->sql();

print_r($sql);

# Returns
# INSERT INTO user(username, password, email) VALUES ('JsonQB','123','example@example.net');
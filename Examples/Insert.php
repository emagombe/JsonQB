<?php

require __DIR__.'/../autoload.php';

use queryBuilder\JsonQB as JQB;

JQB::connect([
	'database' => '',
	'host' => '',
	'port' => '',
	'username' => '',
	'password' => '',
	'charset' => '',
]);

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
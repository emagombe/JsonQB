<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

JQB::connect([
	'database' => '',
	'host' => '',
	'port' => '',
	'username' => '',
	'password' => '',
	'charset' => '',
]);


$sql = JQB::Truncate('user')->sql();

print_r($sql);

# Returns
# TRUNCATE user;
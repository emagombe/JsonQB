<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

$sql = JQB::Update('user', [
	'value' => array(
		'username' => 'example'
	), 
	'where' => array(
		array(
			'columns' => array('user.id' => 1)
		)
	)
])->sql();

print_r($sql);

# Returns
# UPDATE user SET username = 'example' WHERE id = '1';
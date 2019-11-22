<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

$sql = JQB::Update('user', [
	'value' => array(
		'username' => 'emagombe'
	), 
	'where' => array(
		array(
			'columns' => array('user.id' => 1)
		)
	)
])->sql();

print_r($sql);

# Returns
# UPDATE user SET username = 'emagombe' WHERE id = '1';
<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

$sql = JQB::Delete('user', [
	'where' => array(
		array(
			'columns' => array('id' => 1)
		),
		array(
			'column' => 'user.id',
			'between' => array(1, 7)
		)
	)
])->sql();

print_r($sql);

# Returns
# DELETE FROM user WHERE id = '1' AND user.id BETWEEN 1 AND 7;
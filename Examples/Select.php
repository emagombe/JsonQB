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
))->sql;

print_r($sql);

# Returns
# SELECT user.*, user_type.* FROM user, user_type WHERE user.id = '1' AND user.id BETWEEN 1 AND 7

print_r("\n");

/* Order by */
$sql = JQB::Select(array(
	"columns" => array("*"),
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"operator" => "like", # It may be =, !=, <>, >= or <=
			"columns" => array(
				"user.id" => "1"
			)
		),
	),
	"order" => array("by" => "user.id", "order" => "asc"),
))->sql;

print_r($sql);


print_r("\n");

/* Group By */
$sql = JQB::Select(array(
	"columns" => array("*"),
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"operator" => "like", # It may be =, !=, <>, >= or <=
			"columns" => array(
				"user.id" => "1"
			)
		),
	),
	"group" => array('by' => 'user.id'),
))->sql;

print_r($sql);

print_r("\n");

/* JOIN */

$sql = JQB::Select(array(
	"columns" => array("*"),
	"from" => array("user", "user_type"),
	'join' => array(
		'INNER' => array(
			'table' => 'warehouse',
			'on' => array(
				array(
					'columns' => array(
						'warehouse.user_created' => 'user.id'
					),
				)
			),
		),
		'LEFT' => array(
			'table' => 'bank',
			'on' => array(
				array(
					'columns' => array(
						'bank.user_created' => 'user.id'
					),
				),
			),
		),
		'INNER' => array(
			'table' => 'cash',
			'on' => array(
				array(
					'operator' => 'like',
					'columns' => array(
						'cash.user_created' => 'user.id'
					),
				)
			),
		),
	),
	"where" => array(
		array(
			"operator" => "=", # It may be !=, like, <>, >= or <=
			"columns" => array(
				"user.id" => "1"
			)
		),
	)
))->sql;

print_r($sql);


print_r("\n");

/* IN */

$sql = JQB::Select(array(
	"columns" => array("*"),
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"operator" => "like", # It may be =, !=, <>, >= or <=
			"columns" => array(
				"user.id" => "1"
			)
		),
		array(
			'column' => 'user.id',
			'in' => JQB::Select(array(
				'columns' => ['user.id'],
				'from' => ['user']
			))->sql,
		)
	),
	"group" => array('by' => 'user.id'),
))->sql;

print_r($sql);
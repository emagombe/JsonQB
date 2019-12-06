<?php

require '../autoload.php';


JQB::connect([
	'database' => '',
	'host' => '',
	'port' => '',
	'username' => '',
	'password' => '',
	'charset' => '',
]);


// $qb = JsonQB::Insert('user', [
// 	'value' => [
// 		'username' => 'emagombe'
// 	]
// ])->sql();
// print_r($qb);

// $qb = QueryBuilder::Update('user', [
// 	'value' => [
// 		'username' => 'emagombe'
// 	], 
// 	'where' => [
// 		[
// 			'operator' => 'like',
// 			'columns' => [
// 				'status' => 0
// 			]
// 		]
// 	]
// ])->sql();
// print_r($qb);

$sql = JsonQB::Delete('user', [
	'where' => array(
		array(
			'columns' => array('id' => 1)
		),
		array(
			'column' => 'user.id',
			'between' => [1, 7] 
		)
	)
])->sql();

print_r($sql);


// print_r(JsonQB::Select([
// 	'columns' => ['user.first', 'user.username', 'user.*'],
// 	'from' => ['user'],
// 	'where' => [
// 		[
// 			'column' => 'user.id',
// 			'between' => ['1', '7'] 
// 		],
// 		[
// 			'operator' => 'like',
// 			'columns' => [
// 				'user.first' => "'%e%'",
// 			],
// 		],
// 		[
// 			'columns' => [
// 				'user.id' => '7'
// 			]
// 		],
// 		[
// 			'column' => 'user.id',
// 			'in' => JsonQB::select(array(
// 				'columns' => ['user.id'],
// 				'from' => ['user']
// 			)),
// 		],
// 	],
// 	'order' => ['by' => 'user.id', 'order' => 'asc'],
// 	'group' => ['by' => 'user.id'],
// 	'join' => [
// 		'INNER' => array(
// 			'table' => 'warehouse',
// 			'on' => array(
// 				array(
// 					'columns' => array(
// 						'warehouse.user_created' => 'user.id'
// 					),
// 				)
// 			),
// 		),
// 		'LEFT' => [
// 			'table' => 'bank',
// 			'on' => [
// 				[
// 					'columns' => [
// 						'bank.user_created' => 'user.id'
// 					],
// 				],
// 			],
// 		],
// 		'INNER' => [
// 			'table' => 'cash',
// 			'on' => [
// 				[
// 					'operator' => 'like',
// 					'columns' => [
// 						'cash.user_created' => 'user.id'
// 					],
// 				]
// 			],
// 		],
// 	]
// ]));

// print_r(JsonQB::Truncate("user"));

/*print_r(JsonQB::Delete("user", 
	array(
		"value" => array('first' => 'Edson'),
		#"where" => array("id" => "7")
	)
));*/
<?php 
spl_autoload_register(function ($class_name) {
	if(file_exists($class_name.'.php')) {
		require $class_name.'.php';
	}
});

use queryBuilder\QueryBuilder;

// $qb = QueryBuilder::Insert('user', [
// 	'value' => [
// 		'username' => 'emagombe'
// 	]
// ])->sql();
// print_r($qb);

$qb = QueryBuilder::Update('user', [
	'value' => [
		'username' => 'emagombe'
	], 
	'where' => [
		'id' => 1,
		'status' => 0
	]
])->sql();
print_r($qb);
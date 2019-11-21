<?php 

require './database/Settings.php';
require './database/Database.php';
require './security/Security.php';
require './queryBuilder/query/Insert.php';
require './queryBuilder/query/Update.php';
require './queryBuilder/query/Delete.php';
require './queryBuilder/query/Where.php';
require './queryBuilder/QueryBuilder.php';

use queryBuilder\QueryBuilder;

// $qb = QueryBuilder::Insert('user', [
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

$sql = QueryBuilder::Delete('user', [
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
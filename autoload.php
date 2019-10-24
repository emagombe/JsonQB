<?php 



spl_autoload_register(function ($class_name) {
	if(file_exists($class_name.'.php')) {
		include $class_name.'.php';
	}
});


use queryBuilder\QueryBuilder;

$qb = QueryBuilder::Insert('user', [ 'value' => [ 'username' => 'emagombe' ] ])->sql();
print_r($qb);
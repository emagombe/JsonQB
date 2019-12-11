# JSON Query Builder

## Description
Generate sql query from JSON request

## Instalation

### Using composer
```bash
composer require emagombe/json-qb
```

### Without composer

**Clone** the project or **download** a release from https://github.com/emagombe/JsonQB/releases
And import autoload.php file to you project
```php
require_once 'JsonQB/autoload.php';
```

## Database settings

```php
use queryBuilder\JsonQB as JQB;

JQB::connect([
	'database' => '',	# Database name
	'host' => '',		# Host name
	'port' => '',		# Connection port
	'username' => '',	# Username
	'password' => '',	# Password
	'charset' => '',	# Charset
]);
```

## Generating queries

### Insert
```php
use queryBuilder\JsonQB as JQB;

$sql = JQB::Insert('user',
	array(
		"value" => array(
			"username" => "JsonQB",
			"password" => "123",
			"email" => "example@example.net"
		)
	)
)->sql;

print_r($sql);
```
Returns
```SQL
INSERT INTO user(username, password, email) VALUES ('JsonQB','123','example@example.net');
```

#### Inserting from html form
```html
<form method="POST" action="myfile.php">
	<input type="email" name="value[email]" value="example@example.net">
	<input type="password" name="value[password]" value="123">
</form>
```
On myfile.php

```php
$sql = JQB::Insert('user', $_POST)->sql;

print_r($sql);
```
Returns
```SQL
INSERT INTO user(email, password) VALUES ('example@example.net', '123');
```

#### Executing query

To execute the SQL query you only need to call the **execute** function from the JQB::Insert function response

```php
$result = JQB::Insert('user', $_POST)->execute();

if($result->success) { echo "success"; } else { echo "failure" }

print_r($result->sql);		# Returns the executed sql query
print_r($result->id);		# Returns the last insert id
print_r($result->success);	# Returns 1 if success
```

### Update
```php
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
])->sql;

print_r($sql);
```
Returns
```SQL
UPDATE user SET username = 'example' WHERE id = '1';
```

#### Executing query

To execute the SQL query you only need to call the **execute** function from the JQB::Update function response

```php
$result = JQB::Update('user', [
	'value' => array(
		'username' => 'example'
	), 
	'where' => array(
		array(
			'columns' => array('user.id' => 1)
		)
	)
])->execute();

if($result->success) { echo "success"; } else { echo "failure" }

print_r($result->sql);		# Returns the executed sql query
print_r($result->success);	# Returns 1 if success
```

### Delete
```php
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
])->sql;

print_r($sql);
```
Returns
```SQL
DELETE FROM user WHERE id = '1' AND user.id BETWEEN 1 AND 7;
```

#### Executing query

To execute the SQL query you only need to call the **execute** function from the JQB::Delete function response

```php
$result = JQB::Delete('user', [
	'where' => array(
		array(
			'columns' => array('id' => 1)
		),
		array(
			'column' => 'user.id',
			'between' => array(1, 7)
		)
	)
])->execute();

if($result->success) { echo "success"; } else { echo "failure" }

print_r($result->sql);		# Returns the executed sql query
print_r($result->success);	# Returns 1 if success
```

### Truncate
```php
use queryBuilder\JsonQB as JQB;

$sql = JQB::Truncate('user')->sql;

print_r($sql);
```
Returns
```SQL
TRUNCATE user;
```

#### Executing query

To execute the SQL query you only need to call the **execute** function from the JQB::Truncate function response

```php
$result = JQB::('user')->execute();

if($result->success) { echo "success"; } else { echo "failure" }

print_r($result->sql);		# Returns the executed sql query
print_r($result->success);	# Returns 1 if success
```

### Select

#### Simple select
```php
use queryBuilder\JsonQB as JQB;

$sql = JQB::Select(array(
	"columns" => array("user.*", "user_type.*"),
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"columns" => array(
				"user.id" => "1"
			)
		),
	)
))->sql;

print_r($sql);
```
Returns
```SQL
SELECT user.*, user_type.* FROM user, user_type WHERE user.id = '1';
```

#### Executing query

To execute the SQL query you only need to call the **execute** function from the JQB::Truncate function response

```php
$result = JQB::Select(array(
	"columns" => array("user.*", "user_type.*"),
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"columns" => array(
				"user.id" => "1"
			)
		),
	)
))->execute();

if($result->success) { echo "success"; } else { echo "failure" }

print_r($result->sql);		# Returns the executed sql query
print_r($result->data);		# Returns array of data result
print_r($result->json);		# Returns data result encoded to json string
print_r($result->object);	# Returns data in object Ex: from $data['id'] to $data->id
```


#### Select between
```php
use queryBuilder\JsonQB as JQB;

$sql = JQB::Select(array(
	"columns" => array("user.*", "user_type.*"), # for all columns use array("*")
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"columns" => array(
				"user.id" => "1"
			)
		),
		array(
			"column" => "user.id",
			"between" => array(1, 7) # Between 1 and 7
		)
	)
))->sql;

print_r($sql);
```
Returns
```SQL
SELECT user.*, user_type.* FROM user, user_type WHERE user.id = '1' AND user.id BETWEEN 1 AND 7
```

#### Custom operator
```php
use queryBuilder\JsonQB as JQB;

$sql = JQB::Select(array(
	"columns" => array("user.*", "user_type.*"), # for all columns use array("*")
	"from" => array("user", "user_type"),
	"where" => array(
		array(
			"operator" => "like", # It may be =, !=, <>, >= or <=
			"columns" => array(
				"user.id" => "1"
			)
		),
	)
))->sql;

print_r($sql);
```
Returns
```SQL
SELECT user.*, user_type.* FROM user, user_type WHERE user.id = '1';
```

#### Order BY
```php
use queryBuilder\JsonQB as JQB;

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
```
Returns
```SQL
SELECT * FROM user, user_type WHERE user.id like '1' ORDER BY user.id asc
```

#### Order BY
```php
use queryBuilder\JsonQB as JQB;

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
```
Returns
```SQL
SELECT * FROM user, user_type WHERE user.id like '1' GROUP BY user.id
```

#### Join
```php
use queryBuilder\JsonQB as JQB;

$sql = JQB::Select(array(
	"columns" => array("*"),
	"from" => array("user", "user_type"),
	'join' => array(
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
```
Returns
```SQL
SELECT * FROM user, user_type INNER JOIN `cash` ON cash.user_created like user.id LEFT JOIN `bank` ON bank.user_created = user.id WHERE user.id = '1'
```

#### IN
```php
use queryBuilder\JsonQB as JQB;

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
```
Returns
```SQL
SELECT * FROM user, user_type WHERE user.id like '1' AND user.id IN (SELECT user.id FROM user) GROUP BY user.id
```


## Transaction

JsonQB also supports Transaction

```php
use queryBuilder\JsonQB as JQB;

JQB::connect([
	'database' => '',	# Database name
	'host' => '',		# Host name
	'port' => '',		# Connection port
	'username' => '',	# Username
	'password' => '',	# Password
	'charset' => '',	# Charset
]);

JQB::begin();		# Creates a new transaction

/* Execunting query */
$result = JQB::Insert('user', $_POST)->execute();

JQB::commit();		# Commits the created transaction

if($result->success) { echo "success"; } else { echo "failure" }

```

### Rollback

To roll back the transaction you need to call the JQB::rollback() function

```php
JQB::begin();

/* Execunting query */
$result = JQB::Insert('user', $_POST)->execute();

JQB::rollback();

if($result->success) { echo "success"; } else { echo "failure" }
```

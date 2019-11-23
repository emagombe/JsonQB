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

Setput your database connetion settings on **JsonQB/app.conf** file
```script
DATABASE=your_database
HOST=your_host
PORT=3306
USERNAME=your_username
PASSWORD=your_password
CHARSET=utf8
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
)->sql();

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
$sql = JQB::Insert('user', $_POST)->sql();

print_r($sql);
```
Returns
```SQL
INSERT INTO user(email, password) VALUES ('example@example.net', '123');
```
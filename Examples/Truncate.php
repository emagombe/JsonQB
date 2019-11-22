<?php

require '../autoload.php';

use queryBuilder\JsonQB as JQB;

$sql = JQB::Truncate('user')->sql();

print_r($sql);

# Returns
# TRUNCATE user;
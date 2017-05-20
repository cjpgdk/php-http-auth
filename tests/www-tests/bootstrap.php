<?php

require '../../src/CJPGDK/PhpHttpAuth/Database/DB.php';
require '../../src/CJPGDK/PhpHttpAuth/Database/MySQL.php';
require '../../src/CJPGDK/PhpHttpAuth/Database/PDODB.php';

require '../../src/CJPGDK/PhpHttpAuth/Hash/Apr1Md5.php';
require '../../src/CJPGDK/PhpHttpAuth/Hash/Md5.php';
require '../../src/CJPGDK/PhpHttpAuth/Hash/Sha.php';
require '../../src/CJPGDK/PhpHttpAuth/Hash/Bcrypt.php';
require '../../src/CJPGDK/PhpHttpAuth/HttpAuth.php';

/**
 * MySQL Hostname
 */
define('MYSQL_HOST', '127.0.0.1');
/**
 * MySQL username
 */
define('MYSQL_USER', '');
/**
 * MySQL Password
 */
define('MYSQL_PASSWORD', '');
/**
 * MySQL Database
 */
define('MYSQL_DBNAME', '');
/**
 * MySQL Port
 */
define('MYSQL_PORT', 3306);
/**
 * MySQL Socket
 */
define('MYSQL_SOCKET', null);

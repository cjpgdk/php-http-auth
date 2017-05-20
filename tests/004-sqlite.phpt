:NAME:
SQLITE db test
:CONTENT:
<?php
include 'bootstrap.php';

use \CJPGDK\PhpHttpAuth\Database\PDODB;
use \CJPGDK\PhpHttpAuth\HttpAuth;

$db       = new PDODB("sqlite::memory:");
$httpAuth = HttpAuth::getInstance(true, $db);

try {
    $db->getDb()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->getDb()->exec("
CREATE table {$db->usersTableName}(
     {$db->tableColumns['id']} INTEGER PRIMARY KEY AUTOINCREMENT,
     {$db->tableColumns['username']} TEXT NOT NULL,
     {$db->tableColumns['password']}  TEXT NOT NULL
);");
    echo "Created {$db->usersTableName} Table".PHP_EOL;
} catch (PDOException $e) {
    //echo $e->getMessage();
    echo "Unable to create {$db->usersTableName} table".PHP_EOL;
}

/*
  STEP: 1
 * 
  create user but not saved to data store
 */
$user1Password = $httpAuth->getApr1Md5Hash('123456');
$httpAuth->addUser('user1', $user1Password, false);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}


/*
  STEP: 2
 * 
  get new instance (tests that user from step 1 is gone.)
  create user and save to data store.
 */
$httpAuth = HttpAuth::getInstance(true, $db);
$httpAuth->addUser('user2', $user1Password, true);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}

/*
  STEP: 3
 * 
  get new instance (tests that user from step 2 persists.)
 */
$httpAuth = HttpAuth::getInstance(true, $db);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}

/*
  STEP: 4
 *
  create users bulk (append)
 */
$users = array();
for ($index = 3; $index <= 10; $index++) {
    $users['user'.$index] = $httpAuth->getShaHash(md5($index));
}
$httpAuth->appendUsers($users, false);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
    if ($name != 'user10') {
        $httpAuth->deleteUser($name, true);
    }
}
$user1Password = $httpAuth->getBcryptHash('123456');
$httpAuth->addUser('user1', $user1Password, true);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}

// TRUE
if ($httpAuth->matchBcryptHash('123456', $user1Password)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchShaHash('123456', $user1Password)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchApr1Md5Hash('123456', $user1Password)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// TRUE
if ($httpAuth->matchPasswd('123456', $user1Password)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// TRUE
if ($httpAuth->matchPasswd('123456', '123456', true)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchPasswd('123456', '123456', false)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
?>
:EXPECTED:
Created users Table
user1:hidden
user2:hidden
user2:hidden
user2:hidden
user3:hidden
user4:hidden
user5:hidden
user6:hidden
user7:hidden
user8:hidden
user9:hidden
user10:hidden
user10:hidden
user1:hidden
PWD1(123456): TRUE
PWD1(123456): FALSE
PWD1(123456): FALSE
PWD1(123456): TRUE
PWD1(123456): TRUE
PWD1(123456): FALSE

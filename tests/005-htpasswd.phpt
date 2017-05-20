:NAME:
HTPASSWD test
:CONTENT:
<?php
include 'bootstrap.php';

use \CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance();
if (file_exists('.htpasswd')) {
    unlink('.htpasswd');
}
$httpAuth->setUsersFile('.htpasswd');


/*
  STEP: 1
 *
  create users bulk (set)
 */
$users = array();
for ($index = 0; $index <= 10; $index++) {
    $users['user'.$index] = $httpAuth->getShaHash(md5($index));
}
$httpAuth->setUsers($users, false);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}


/*
  STEP: 2
 *
  get current instance
 */
$httpAuth = HttpAuth::getInstance(false);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}


/*
  STEP: 3
 *
  get new instance
 */
$httpAuth = HttpAuth::getInstance(true);
$httpAuth->setUsersFile('.htpasswd');
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}


/*
  STEP: 4
 *
  get new instance
  create users bulk and save them
 */
$httpAuth = HttpAuth::getInstance(true);
$httpAuth->setUsersFile('.htpasswd');
$users = array();
for ($index = 0; $index <= 10; $index++) {
    $users['user'.$index] = $httpAuth->getShaHash(md5($index));
}
$httpAuth->setUsers($users, true);
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}


/*
  STEP: 5
 *
  get new instance
  load user from .htpasswd
 */
$httpAuth = HttpAuth::getInstance(true);
$httpAuth->setUsersFile('.htpasswd');
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}


/*
  STEP: 6
 *
  delete users
 */
$c =0;
foreach ($httpAuth->getUsers() as $name => $password) {
    if (++$c % 2) {
        $httpAuth->deleteUser($name, true);
    }
}


/*
  STEP: 6
 *
  get new instance
  load user from .htpasswd
 */
$httpAuth = HttpAuth::getInstance(true);
$httpAuth->setUsersFile('.htpasswd');
foreach ($httpAuth->getUsers() as $name => $password) {
    echo "{$name}:{$password}".PHP_EOL;
}
?>
:EXPECTED:
user0:hidden
user1:hidden
user2:hidden
user3:hidden
user4:hidden
user5:hidden
user6:hidden
user7:hidden
user8:hidden
user9:hidden
user10:hidden
user0:hidden
user1:hidden
user2:hidden
user3:hidden
user4:hidden
user5:hidden
user6:hidden
user7:hidden
user8:hidden
user9:hidden
user10:hidden
user0:hidden
user1:hidden
user2:hidden
user3:hidden
user4:hidden
user5:hidden
user6:hidden
user7:hidden
user8:hidden
user9:hidden
user10:hidden
user0:hidden
user1:hidden
user2:hidden
user3:hidden
user4:hidden
user5:hidden
user6:hidden
user7:hidden
user8:hidden
user9:hidden
user10:hidden
user1:hidden
user3:hidden
user5:hidden
user7:hidden
user9:hidden

:NAME:
Bcrypt Hashing
:CONTENT:
<?php
include 'bootstrap.php';

use \CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance(true);

/* get hash */
$pwd1 = $httpAuth->getBcryptHash('123456');

// TRUE
if ($httpAuth->matchBcryptHash('123456', $pwd1)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchBcryptHash('wrongpwd', $pwd1)) {
    echo "PWD1(wrongpwd): TRUE".PHP_EOL;
} else {
    echo "PWD1(wrongpwd): FALSE".PHP_EOL;
}

/* Create user */
$userpwd = $httpAuth->createUserBcrypt('UserBcrypt', '123456');
echo $httpAuth->getBcryptHtpasswdRow($userpwd).PHP_EOL;
echo $httpAuth->getBcryptHtpasswdRow($userpwd['username'], $userpwd['password']).PHP_EOL;
?>
:EXPECTED:
PWD1(123456): TRUE
PWD1(wrongpwd): FALSE
UserBcrypt:<?php echo (isset($userpwd['password'])?$userpwd['password']:'--').PHP_EOL; ?>
UserBcrypt:<?php echo (isset($userpwd['password'])?$userpwd['password']:'--').PHP_EOL; ?>
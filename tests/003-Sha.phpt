:NAME:
SHA Hashing
:CONTENT:
<?php
include 'bootstrap.php';

use \CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance(true);

/* get hash */
$pwd1 = $httpAuth->getShaHash('123456');

// TRUE
if ($httpAuth->matchShaHash('123456', $pwd1)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchShaHash('wrongpwd', $pwd1)) {
    echo "PWD1(wrongpwd): TRUE".PHP_EOL;
} else {
    echo "PWD1(wrongpwd): FALSE".PHP_EOL;
}

/* Create user */
$userpwd = $httpAuth->createUserSha('UserSha', '123456');
echo $httpAuth->getBcryptHtpasswdRow($userpwd).PHP_EOL;
echo $httpAuth->getBcryptHtpasswdRow($userpwd['username'], $userpwd['password']).PHP_EOL;
?>
:EXPECTED:
PWD1(123456): TRUE
PWD1(wrongpwd): FALSE
UserSha:<?php echo (isset($userpwd['password'])?$userpwd['password']:'--').PHP_EOL; ?>
UserSha:<?php echo (isset($userpwd['password'])?$userpwd['password']:'--').PHP_EOL; ?>
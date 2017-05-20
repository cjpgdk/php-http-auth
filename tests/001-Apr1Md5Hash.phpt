:NAME:
Apr1 Md5 Hashing
:CONTENT:
<?php
include 'bootstrap.php';

use \CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance();

/* default salt, get hash */
$pwd1 = $httpAuth->getApr1Md5Hash('123456');

// TRUE
if ($httpAuth->matchApr1Md5Hash('123456', $pwd1)) {
    echo "PWD1(123456): TRUE".PHP_EOL;
} else {
    echo "PWD1(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchApr1Md5Hash('wrongpwd', $pwd1)) {
    echo "PWD1(wrongpwd): TRUE".PHP_EOL;
} else {
    echo "PWD1(wrongpwd): FALSE".PHP_EOL;
}

/* custom salt, get hash */
$pwd2 = $httpAuth->getApr1Md5Hash('123456', 'salt1234');

// TRUE
if ($httpAuth->matchApr1Md5Hash('123456', $pwd2)) {
    echo "PWD2(123456): TRUE".PHP_EOL;
} else {
    echo "PWD2(123456): FALSE".PHP_EOL;
}
// FALSE
if ($httpAuth->matchApr1Md5Hash('wrongpwd', $pwd2)) {
    echo "PWD2(wrongpwd): TRUE".PHP_EOL;
} else {
    echo "PWD2(wrongpwd): FALSE".PHP_EOL;
}
if (strpos($pwd2, '$apr1$salt1234$') !== false) {
    echo "PWD2 SALT:(\$apr1\$salt1234\$): TRUE".PHP_EOL;
} else {
    echo "PWD2 SALT:(\$apr1\$salt1234\$): FALSE ({$pwd2})".PHP_EOL;
}

/* Create user */
$userpwd = $httpAuth->createUserApr1Md5('UserApr1Md5', '123456');
echo $httpAuth->getApr1Md5HtpasswdRow($userpwd).PHP_EOL;
echo $httpAuth->getApr1Md5HtpasswdRow($userpwd['username'], $userpwd['password']).PHP_EOL;
?>
:EXPECTED:
PWD1(123456): TRUE
PWD1(wrongpwd): FALSE
PWD2(123456): TRUE
PWD2(wrongpwd): FALSE
PWD2 SALT:($apr1$salt1234$): TRUE
UserApr1Md5:<?php echo (isset($userpwd['password'])?$userpwd['password']:'--').PHP_EOL; ?>
UserApr1Md5:<?php echo (isset($userpwd['password'])?$userpwd['password']:'--').PHP_EOL; ?>
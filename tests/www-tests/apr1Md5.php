<?php

require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = new HttpAuth();

// default get hash
$pwd1 = $httpAuth->getApr1Md5Hash('123456');
echo "getApr1Md5Hash(123456): ".$pwd1."<br><br>";
echo "matchApr1Md5Hash(123456, '{$pwd1}'): ".($httpAuth->matchApr1Md5Hash('123456', $pwd1) ? 'True' : 'False')."<br><br>";
echo "matchApr1Md5Hash(wrongpwd, '{$pwd1}'): ".($httpAuth->matchApr1Md5Hash('wrongpwd', $pwd1) ? 'True' : 'False')."<br><br>";

// custom salt get hash
$pwd2 = $httpAuth->getApr1Md5Hash('123456', 'salt1234');
echo "getApr1Md5Hash(123456, 'salt1234'): ".$pwd2."<br><br>";
echo "matchApr1Md5Hash(123456, '{$pwd2}'): ".($httpAuth->matchApr1Md5Hash('123456', $pwd2) ? 'True' : 'False')."<br><br>";
echo "matchApr1Md5Hash(wrongpwd2, '{$pwd2}'): ".($httpAuth->matchApr1Md5Hash('wrongpwd2', $pwd2) ? 'True' : 'False')."<br><br>";

// create User
$userpwd = $httpAuth->createUserApr1Md5('UserApr1Md5', '123456');
echo "createUserApr1Md5('UserApr1Md5', '123456')<pre>".print_r($userpwd, true)."</pre><br><br>";

// create htpasswd row
echo "getApr1Md5HtpasswdRow(\$userpwd)<pre>".$httpAuth->getApr1Md5HtpasswdRow($userpwd)."</pre><br><br>";
echo "getApr1Md5HtpasswdRow(\$userpwd['username'], \$userpwd['password'])<pre>".$httpAuth->getApr1Md5HtpasswdRow($userpwd['username'], $userpwd['password'])."</pre><br><br>";



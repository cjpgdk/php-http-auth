<?php

require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = new HttpAuth();

// default get hash
$pwd1 = $httpAuth->getBcryptHash('123456');
echo "getBcryptHash(123456): ".$pwd1."<br><br>";
echo "matchBcryptHash(123456, '{$pwd1}'): ".($httpAuth->matchBcryptHash('123456', $pwd1) ? 'True' : 'False')."<br><br>";
echo "matchBcryptHash(wrongpwd, '{$pwd1}'): ".($httpAuth->matchBcryptHash('wrongpwd', $pwd1) ? 'True' : 'False')."<br><br>";

// create User
$userpwd = $httpAuth->createUserBcrypt('UserSha', '123456');
echo "createUserBcrypt('UserSha', '123456')<pre>".print_r($userpwd, true)."</pre><br><br>";

// create htpasswd row
echo "getBcryptHtpasswdRow(\$userpwd)<pre>".$httpAuth->getBcryptHtpasswdRow($userpwd)."</pre><br><br>";
echo "getBcryptHtpasswdRow(\$userpwd['username'], \$userpwd['password'])<pre>".$httpAuth->getBcryptHtpasswdRow($userpwd['username'], $userpwd['password'])."</pre><br><br>";



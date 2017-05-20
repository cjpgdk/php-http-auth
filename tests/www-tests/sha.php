<?php

require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = new HttpAuth();

// default get hash
$pwd1 = $httpAuth->getShaHash('123456');
echo "getShaHash(123456): ".$pwd1."<br><br>";
echo "matchShaHash(123456, '{$pwd1}'): ".($httpAuth->matchShaHash('123456', $pwd1) ? 'True' : 'False')."<br><br>";
echo "matchShaHash(wrongpwd, '{$pwd1}'): ".($httpAuth->matchShaHash('wrongpwd', $pwd1) ? 'True' : 'False')."<br><br>";

// create User
$userpwd = $httpAuth->createUserSha('UserSha', '123456');
echo "createUserSha('UserSha', '123456')<pre>".print_r($userpwd, true)."</pre><br><br>";

// create htpasswd row
echo "getShaHtpasswdRow(\$userpwd)<pre>".$httpAuth->getShaHtpasswdRow($userpwd)."</pre><br><br>";
echo "getShaHtpasswdRow(\$userpwd['username'], \$userpwd['password'])<pre>".$httpAuth->getShaHtpasswdRow($userpwd['username'], $userpwd['password'])."</pre><br><br>";



<?php

require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = new HttpAuth();

// default get hash
$pwd1 = $httpAuth->getMd5Hash('123456');
echo "getMd5Hash(123456): ".$pwd1."<br><br>";
echo "matchMd5Hash(123456, '{$pwd1}'): ".($httpAuth->matchMd5Hash('123456', $pwd1) ? 'True' : 'False')."<br><br>";
echo "matchMd5Hash(wrongpwd, '{$pwd1}'): ".($httpAuth->matchMd5Hash('wrongpwd', $pwd1) ? 'True' : 'False')."<br><br>";

// create User
$userpwd = $httpAuth->createUserMd5('UserMd5', '123456');
echo "createUserMd5('UserMd5', '123456')<pre>".print_r($userpwd, true)."</pre><br><br>";

// create htpasswd row
echo "getMd5HtpasswdRow(\$userpwd)<pre>".$httpAuth->getMd5HtpasswdRow($userpwd)."</pre><br><br>";
echo "getMd5HtpasswdRow(\$userpwd['username'], \$userpwd['password'])<pre>".$httpAuth->getMd5HtpasswdRow($userpwd['username'], $userpwd['password'])."</pre><br><br>";



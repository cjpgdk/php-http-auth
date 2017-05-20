<?php

require '../src/CJPGDK/PhpHttpAuth/Hash/Apr1Md5.php';
require '../src/CJPGDK/PhpHttpAuth/Hash/Sha.php';
require '../src/CJPGDK/PhpHttpAuth/Hash/Bcrypt.php';
require '../src/CJPGDK/PhpHttpAuth/HttpAuth.php';

$realm   = 'Restricted area';
$message = 'Restricted area';

use \CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance();

$httpAuth->setUsersFile('includes/.htpasswd');

if (isset($_GET['auth'])) {
    if (!$httpAuth->authBasic($realm, $message)) {
        $httpAuth->requestReAuthBasic($realm, $message);
    }
}

$userLoggedIn = $httpAuth->hasValidCredentials($realm);
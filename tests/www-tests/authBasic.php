<?php

require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance();

//$httpAuth->allowPlainPassword = true;
$httpAuth->addUser("admin", "admin123");

$httpAuth->allowPlainPassword = false;
$httpAuth->addUser("admin2", $httpAuth->getApr1Md5Hash('admin123'));
$httpAuth->addUser("admin3", $httpAuth->getBcryptHash('admin123'));
//$httpAuth->addUser("admin4", $httpAuth->getMd5Hash('admin123'));
$httpAuth->addUser("admin5", $httpAuth->getShaHash('admin123'));

$realm = 'Restricted area';
$message = 'Restricted area';

if (!$httpAuth->authBasic($realm, $message)) {
    $httpAuth->requestReAuthBasic($realm, $message);
}
?>
<h1>Hello: <?php echo $httpAuth->whoAmI(); ?></h1>
<?php
require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;
use CJPGDK\PhpHttpAuth\Database\PDODB;

$dsn = 'mysql:dbname='.MYSQL_DBNAME.';host='.MYSQL_HOST;
$db = new PDODB($dsn, MYSQL_USER, MYSQL_PASSWORD);

$httpAuth = HttpAuth::getInstance(true, $db);

$httpAuth->autoSaveUsers = true;

$httpAuth->allowPlainPassword = true;
/*
$httpAuth->addUser("admin", "admin123");

$httpAuth->appendUsers(array(
    "admin2" => $httpAuth->getApr1Md5Hash('admin123'),
    "admin3" => $httpAuth->getBcryptHash('admin123'),
    //"admin4" => $httpAuth->getMd5Hash('admin123'),
    "admin5" => $httpAuth->getShaHash('admin123')
));
*/
$realm   = 'Restricted area';
$message = 'Restricted area';

if (!$httpAuth->authBasic($realm, $message)) {
    $httpAuth->requestReAuthBasic($realm, $message);
}
?>
<h1>Hello: <?php echo $httpAuth->whoAmI(); ?></h1>
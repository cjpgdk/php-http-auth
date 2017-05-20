<?php

require './bootstrap.php';

use CJPGDK\PhpHttpAuth\HttpAuth;

$httpAuth = HttpAuth::getInstance();

$httpAuth->addUser("admin", "admin123");
$httpAuth->addUser("admin2", "admin1232");
$httpAuth->addUser("admin3", "admin1233");

$realm = 'Restricted area';
$message = 'Restricted area';
$httpAuth->authDigest($realm, $message);

$data = $httpAuth->authDigestGetUserDetails();

$validateAuthDigestResponse = $httpAuth->validateAuthDigestResponse($realm, $data);

if (!$validateAuthDigestResponse) {
    $httpAuth->requestReAuthDigest($realm, $message);
}
?>
<h1>Hello: <?php echo $httpAuth->whoAmI(); ?></h1>
<pre><?php echo print_r($data, true); ?></pre>
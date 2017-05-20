<?php
require 'includes/bootstrap.php';
?><!DOCTYPE html>
<html lang="en">
    <?php require 'includes/head.php'; ?>
    <body>
        <div id="wrapper" class="toggled">
            <?php require 'includes/sidebar.php'; ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1>PhpHttpAuth - PHP Library to make use of HTTP Authentication</h1>
                            <p>
                                This is an unprotected page.<br>
                            </p>
                            <pre><code>$realm   = 'Restricted area';
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

<?php
if ($userLoggedIn) {
    echo "You are logged in as ".$httpAuth->whoAmI();
}else{
    echo "No valid credentials found";
}
?></code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'includes/footer.php'; ?>
    </body>
</html>

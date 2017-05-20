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
                            <h1>Generate .htpasswd user line</h1>
                            <form class="form-horizontal" method="POST">
                                <div class="form-group">
                                    <label for="inputUser" class="col-sm-2 control-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputUser" name="username" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPasswordAlgorithm" class="col-sm-2 control-label">Password Algorithm</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="inputPasswordAlgorithm" name="pwalgorithm">
                                            <option value="apr1-md5">APR1-MD5</option>
                                            <option value="bcrypt">BCRYPT</option>
                                            <option value="sha">SHA</option>
                                            <option value="plain">Plain</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default">Generate</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                            if (isset($_POST['username']) && isset($_POST['password'])):
                                $_POST['pwalgorithm'] = isset($_POST['pwalgorithm']) ? $_POST['pwalgorithm'] : 'apr1-md5';
                                switch ($_POST['pwalgorithm']) {
                                    case 'apr1-md5':
                                        $password = $httpAuth->getApr1Md5Hash($_POST['password']);
                                        break;
                                    case 'bcrypt':
                                        $password = $httpAuth->getBcryptHash($_POST['password']);
                                        break;
                                    case 'sha':
                                        $password = $httpAuth->getShaHash($_POST['password']);
                                        break;
                                    default:
                                        $password = $_POST['password'];
                                        break;
                                }
                                ?>

                            <pre>Add the below line to your <code>.htpasswd</code> file

<?php echo $_POST['username']; ?>:<?php echo $password; ?>
</pre>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'includes/footer.php'; ?>
    </body>
</html>
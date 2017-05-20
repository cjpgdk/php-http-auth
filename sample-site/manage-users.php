<?php
require 'includes/bootstrap.php';

if (!$userLoggedIn) {
    if (!$httpAuth->authBasic($realm, $message)) {
        $httpAuth->requestReAuthBasic($realm, $message);
    }
}

if (isset($_POST['delete'])) {
    $httpAuth->deleteUser($_POST['delete'], true);
    echo "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
        <span aria-hidden=\"true\">&times;</span></button><strong>Hmm okay then</strong> User ({$_POST['delete']}) deleted.</div>";
    exit;
}

/* add new user */
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['newuser'])) {
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
    $httpAuth->addUser($_POST['username'], $password, true);
    echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
        <span aria-hidden=\"true\">&times;</span></button><strong>Well done!</strong> User ({$_POST['username']}) added.</div>";
    exit;
} else if (isset($_POST['username']) && isset($_POST['password'])) {
    /* edit user */
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
    $httpAuth->updateUser($_POST['username'], $password, true);
    echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
        <span aria-hidden=\"true\">&times;</span></button><strong>Well done!</strong> User ({$_POST['username']}) password updated.</div>";
    exit;
}
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
                            <h1>
                                Manage users.
                                <button class="btn btn-default pull-right" type="button" data-toggle="modal" data-target="#addNewUserModal">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </h1>
                            <div id="messageswrapper">
                                <?php echo isset($addUserMessage) ? $addUserMessage : ''; ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="userListTable">
                                    <tr>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Actions</th>
                                    </tr>
                                    <?php foreach ($httpAuth->getUsers() as $name => $password): ?>
                                        <tr id="username<?php echo $name; ?>">
                                            <td><?php echo $name; ?></td>
                                            <td><?php echo $password; ?></td>
                                            <td>
                                                <?php if ($name != $httpAuth->whoAmI()): ?>
                                                    <button type="button"  data-username="<?php echo $name; ?>" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal">Delete</button>
                                                <?php else: ?>
                                                    <span class="badge">This is you!</span>
                                                <?php endif; ?>
                                                <button data-username="<?php echo $name; ?>" type="button" class="btn btn-default" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require 'includes/footer.php';
        require 'includes/modals/change_password.php';
        require 'includes/modals/delete_user.php';
        require 'includes/modals/add_new_user.php';
        ?>
    </body>
</html>
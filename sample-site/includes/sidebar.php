<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="index.php">PhpHttpAuth</a>
        </li>
        <?php if ($userLoggedIn): ?>
        <li><a href="add-user.php">Add User</a></li>
        <li><a href="manage-users.php">Manage Users</a></li>
        <?php else: ?>
        <li><a href="index.php?auth">Login</a></li>
        <?php endif; ?>
        <li><a href="generate-user.php">Generate user</a></li>
    </ul>
</div>
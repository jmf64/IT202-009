<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
    <ul class="nav">
        <li><a href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
        <?php if (has_role("Admin")): ?>
            <li><a href="test_create_account.php">Create Account Admin</a></li>
            <li><a href="test_list_account.php">List Account Admin</a></li>
            <li><a href="test_create_transaction.php">Create Transaction Admin</a></li>
            <li><a href="test_list_transaction.php">List Transaction Admin</a></li>
        <?php endif; ?>
        <?php if (is_logged_in()): ?>
            <li><a href="create_account.php">Create Account</a></li>
            <li><a href="list_account.php">List Account</a></li>
            <li><a href="deposit.php">Deposit</a></li>
            <li><a href="withdraw.php">Withdraw</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="ext_transfer.php">Exterior Transfer</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>

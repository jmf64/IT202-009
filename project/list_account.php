<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$user_id = get_user_id();
$results = [];

if (isset($_POST["search"])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, account_number, account_type, balance, apy, user_id 
from Accounts WHERE Accounts.user_id = :user_id AND active = 1 LIMIT 5");
    $r = $stmt->execute([":user_id" => $user_id]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}

?>
<h3>List Accounts</h3>
<form method="POST">
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Account Number:</div>
                        <div><?php safer_echo($r["account_number"]); ?></div>
                    </div>
                    <div>
                        <div>Account Type:</div>
                        <div><?php safer_echo($r["account_type"]); ?></div>
                    </div>
                    <div>
                        <div>Balance:</div>
                        <div><?php safer_echo(abs($r["balance"])); ?></div>
                    </div>
                    <div>
                        <div>APY:</div>
                        <div><?php safer_echo($r["apy"]); ?></div>
                    </div>
                    <div>
                        <div>Owner Id:</div>
                        <div><?php safer_echo($r["user_id"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="transaction_history.php?id=<?php safer_echo($r['id']);?>">View Transaction History</a>
                        <a type="button" href="delete_account.php?id=<?php safer_echo($r['id']);?>">Delete This Account</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php");
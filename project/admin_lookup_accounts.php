<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

    <form method="POST">
        <label>Account Number</label>
        <input type="text" name="account_number"/>
    </form>

<?php
$results = [];
$account_number = $_POST["account_number"];

if (isset($_POST["search"])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, last_updated, balance,
    apy, nextApy, active from Accounts WHERE account_number = :account_number");
    $r = $stmt->execute([":account_number" => $account_number]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}

?>
    <h3>Account</h3>
    <form method="POST">
        <input type="submit" value="Search" name="search"/>
    </form>
    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Account ID:</div>
                            <div><?php safer_echo($r["id"]); ?></div>
                        </div>
                        <div>
                            <div>Account Number:</div>
                            <div><?php safer_echo($r["account_number"]); ?></div>
                        </div>
                        <div>
                            <div>User Id:</div>
                            <div><?php safer_echo($r["user_id"]); ?></div>
                        </div>
                        <div>
                        <div>
                            <div>Account Type:</div>
                            <div><?php safer_echo($r["account_type"]); ?></div>
                        </div>
                        <div>
                            <div>Opened Date:</div>
                            <div><?php safer_echo($r["opened_date"]); ?></div>
                        </div>
                        <div>
                            <div>Last Updated:</div>
                            <div><?php safer_echo($r["last_updated"]); ?></div>
                        </div>
                        <div>
                            <div>Balance:</div>
                            <div><?php safer_echo($r["balance"]); ?></div>
                        </div>
                        <div>
                            <div>APY:</div>
                            <div><?php safer_echo($r["apy"]); ?></div>
                        </div>
                        <div>
                            <div>Next APY:</div>
                            <div><?php safer_echo($r["nextAPY"]); ?></div>
                        </div>
                        <div>
                            <div>Active:</div>
                            <div><?php safer_echo($r["active"]); ?></div>
                        </div>
                            <a type="button" href="admin_transaction_history.php?id=<?php safer_echo($r['id']);?>">View Transaction History</a>
                            <a type="button" href="admin_freeze.php?id=<?php safer_echo($r['id']);?>">Freeze This Account</a>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>
<?php require(__DIR__ . "/partials/flash.php");

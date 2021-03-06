<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}

if(isset($_GET["id"])){
    $account_id = $_GET["id"];
}

$db = getDB();

if(isset($account_id)) {

    $stmt = $db->prepare("SELECT amount, action_type, memo, created FROM Transactions WHERE act_src_id = :account_id LIMIT 10");
    $r = $stmt->execute(["account_id" => $account_id]);
    if ($r) {
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error fetching transaction info " . var_export($e, true));
    }
}
?>
    <h3>Transaction History</h3>
    <div class="results">
        <?php if (count($transactions) > 0): ?>
            <div class="list-group">
                <?php foreach ($transactions as $t): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Amount:</div>
                            <div><?php safer_echo($t["amount"]); ?></div>
                        </div>
                        <div>
                            <div>Action Type:</div>
                            <div><?php safer_echo($t["action_type"]); ?></div>
                        </div>
                        <div>
                            <div>Memo:</div>
                            <div><?php safer_echo($t["memo"]); ?></div>
                        </div>
                        <div>
                            <div>Created:</div>
                            <div><?php safer_echo($t["created"]); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>
<?php require(__DIR__ . "/partials/flash.php");

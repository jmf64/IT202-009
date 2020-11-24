<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

if(isset($_GET["id"])){
    $account_id = $_GET["id"];
}

$db = getDB();
$user_id = get_user_id();

if(isset($account_id)) {
    $stmt = $db->prepare("SELECT account_number, account_type FROM Accounts WHERE id = :id AND user_id = :user_id");
    $r = $stmt->execute([
        ":id" => $account_id,
        ":user_id" => $user_id
    ]);

    if($r){
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $account_number = $results["account_number"];
        $account_type = $results["account_type"];
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error fetching account info " . var_export($e, true));
    }
}

if(isset($account_id) && isset($account_number) && isset($account_type)){
    $stmt = $db->prepare("SELECT amount, action_type, memo, created FROM Transactions WHERE act_src_id = :account_id LIMIT 10");
    $r = $stmt->execute(["account_id" => $account_id]);
    if ($r) {
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error fetching transaction info " . var_export($e, true));
    }
} else {
    flash("There was a problem fetching the results.");
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
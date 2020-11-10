
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Transactions.id, Transactions.act_src_id, Transactions.act_dest_id, Transactions.amount,
Transactions.action_type, Transactions.memo, Users.username, Accounts.name FROM Accounts JOIN Users on Transactions.user_id = Users.id 
LEFT JOIN Accounts on Accounts.id = Transactions.account_number where Transactions.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<h3>Transactions</h3>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["name"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>Info</p>
                <div>Account Source Id: <?php safer_echo($result["act_src_id"]); ?></div>
                <div>Account Destination Id: <?php safer_echo($result["act_dest_id"]); ?></div>
                <div>Amount: <?php safer_echo($result["amount"]); ?></div>
                <div>Action Type: <?php safer_echo($result["action_type"]); ?></div>
                <div>Memo: <?php safer_echo($result["memo"]); ?></div>
                <div>Transaction Made By: <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
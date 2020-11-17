<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

$user_id = get_user_id();
$db = getDB();
$stmt = $db->prepare("SELECT account_number, id FROM Accounts WHERE Accounts.user_id = user_id LIMIT 5");
$r = $stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
    <h3>Deposit</h3>
    <form method="POST">
        <label>Account ID</label>
        <select name="act_id">
            <?php foreach ($accounts as $account): ?>
                <option value="<?php safer_echo($account["id"]); ?>"
                ><?php safer_echo($account["account_number"]); ?></option>
            <?php endforeach;?>
        </select>
        <label>Amount</label>
        <input type="number" name="amount" min="5" placeholder="0.00"/>
        <label>Memo</label>
        <input type="text" name="memo"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $world_id = 2;
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM Accounts WHERE account_number = '000000000000'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $world_id = $result["id"];

    $act_id = $_POST["act_id"];
    $amount = $_POST["amount"];
    $action_type = 'deposit';
    $memo = $_POST["memo"];

    if (isset($_POST['act_id']) && isset($_POST['amount'])) {
        switch ($action_type) {
            case 'deposit':
                doTransaction($world_id, $act_id, ($amount * -1), $action_type, $memo);
                break;
            //case 'withdraw':
            //    doTransaction($act_src_id, $world_id, ($amount * -1), $action_type, $memo);
            //    break;
            //case 'transfer':
            //    doTransaction($act_src_id, $act_dest_id, ($amount * -1), $action_type, $memo);
            //    break;
        }
    }

    if ($r) {
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");


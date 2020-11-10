<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
    <h3>Create Transaction</h3>
    <form method="POST">
        <label>Account Source ID</label>
        <input name="act_src_id" placeholder="act_src_id"/>
        <label>Account Destination ID</label>
        <input type="number" name="act_dest_id"/>
        <label>Amount</label>
        <input type="number" name="amount"/>
        <label>Action Type</label>
        <select name="action_type">
            <option>Deposit</option>
            <option>Withdraw</option>
            <option>Transfer</option>
        </select>
        <label>Memo</label>
        <input type="text" name="memo"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $act_src_id = $_POST["act_src_id"];
    $act_dest_id = $_POST["act_dest_id"];
    $amount = $_POST["amount"];
    $action_type = $_POST["action_type"];
    $memo = $_POST["memo"];
    $user_id = get_user_id();
    doTransaction($act_src_id, $act_dest_id, $amount, $action_type);
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Transactions (act_src_id, act_dest_id, amount, action_type, memo, user_id) 
VALUES(:act_src_id, :act_dest_id, :action_type,:memo, :user_id)");
    $r = $stmt->execute([
        ":act_src_id" => $act_src_id,
        ":act_dest_id" => $act_dest_id,
        ":action_type" => $action_type,
        ":memo" => $memo,
        ":user" => $user_id
    ]);
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

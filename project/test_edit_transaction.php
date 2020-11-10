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
//saving
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $act_src_id = $_POST["act_src_id"];
    $act_dest_id = $_POST["act_dest_id"];
    $amount = $_POST["amount"];
    $action_type = $_POST["action_type"];
    $user_id = get_user_id();
    doTransaction($act_src_id, $act_dest_id, $amount, $action_type);
    $db = getDB();
    if (isset($id)) {
        $stmt = $db->prepare("UPDATE Transactions set act_src_id=:act_src_id, act_dest_id=:act_dest_id, 
amount=:amount, action_type=:action_type, user_id=:user_id, where id=:id");
        $r = $stmt->execute([
            ":act_src_id" => $act_src_id,
            ":act_dest_id" => $act_dest_id,
            ":amount" => $amount,
            ":action_type" => $action_type,
            ":user_id" => $user_id,
            ":id" => $id
        ]);
        if ($r) {
            flash("Updated successfully with id: " . $id);
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else {
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Transactions where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
//get eggs for dropdown
$db = getDB();
$stmt = $db->prepare("SELECT id,account_number from Accounts LIMIT 10");
$r = $stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Edit Transaction</h3>
    <form method="POST">
        <label></label>
        <input name="act_src_id" placeholder="act_src_id" value="<?php echo $result["act_src_id"]; ?>"/>
        <label>Transaction</label>
        <select name="act_dest_id" value="<?php echo $result["act_dest_id"];?>" >
            <option>None</option>
            <?php foreach ($accounts as $account): ?>
                <option value="<?php safer_echo($account["id"]); ?>" <?php echo ($result["account_id"] == $account["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($account["account_number"]); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Amount</label>
        <input type="number" name="amount" value="<?php echo $result["amount"]; ?>"/>
        <label>Action Type</label>
        <input type="text" name="action_type" value="<?php echo $result["action_type"]; ?>"/>
        <label>Memo</label>
        <input type="text" name="memo" value="<?php echo $result["memo"]; ?>"/>
        <input type="submit" name="save" value="Update"/>
    </form>


<?php require(__DIR__ . "/partials/flash.php");

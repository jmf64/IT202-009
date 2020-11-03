
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
if(isset($_GET["id"])){
    $id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
    //TODO add proper validation/checks
    $account_number = $_POST["account_number"];
    $account_type = $_POST["account_type"];
    $balance = $_POST["balance"];
    $user_id = get_user_id();
    $db = getDB();
    if(isset($id)){
        $stmt = $db->prepare("UPDATE Accounts set account_number=:account_number,
account_type=:account_type, balance=:balance, user_id=:user_id where id=:id");
        $r = $stmt->execute([
            ":account_number"=>$account_number,
            ":account_type"=>$account_type,
            ":balance"=>$balance,
            ":user_id"=>$user_id, ":id"=>$id
        ]);

        if($r){
            flash("Updated successfully with id: " . $user_id);
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else{
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Accounts where id = :id");
    $r = $stmt->execute([":id"=>$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST">

    <label>Account Number</label>
    <input name="account_number" placeholder="Account Number" value ="<?php echo $result["account_number"];?>"/>
    <label>Account Type</label>
    <select name="account_type" value="<?php echo $result["account_type"];?>">
        <option value="checking" <?php echo ($result["account_type"] == "checking"?'selected="selected"':'');?>>Checking</option>
        <option value="savings" <?php echo ($result["account_type"] == "savings"?'selected="selected"':'');?>>Savings</option>
        <option value="loan" <?php echo ($result["account_type"] == "loan"?'selected="selected"':'');?>>Loan</option>
    </select>
    <label>Balance</label>
    <input name ="balance" type="number" value="<?php echo $result["balance"];?>"/>
    <input type="submit" name="save" value="Update"/>
</form>

<?php require(__DIR__ . "/partials/flash.php");

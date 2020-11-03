
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
    $opened_date = $_POST["opened_date"];
    $last_updated = $_POST["last_updated"];
    $balance = $_POST["balance"];
    $nst = date('Y-m-d H:i:s');//calc
    $user = get_user_id();
    $db = getDB();
    if(isset($id)){
        $stmt = $db->prepare("UPDATE create_table_accounts set account_number:account_number, account_type:account_type, opened_date:opened_date, 
last_updated:last_updated, balance:balance, user:user, where id=:id");
        $r = $stmt->execute([
            ":account_number"=>$account_number,
            ":account_type"=>$account_type,
            ":opened_date"=>$opened_date,
            ":last_updated"=>$last_updated,
            "balance"=>$balance,
            ":user"=>$user
        ]);

        if($r){
            flash("Updated successfully with id: " . $id);
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
    $stmt = $db->prepare("SELECT * FROM create_table_accounts where id = :id");
    $r = $stmt->execute([":id"=>$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST">

    <label>Account Number</label>
    <input name="account number" placeholder="Account Number" value ="<?php echo $result["account_number"];?>"/>
    <label>Account Type</label>
    <select name="account type" value="<?php echo $result["account_type"];?>>
        <option <?php echo ($result["account_type"]?'selected="selected"':'');?>>Checking</option>
        <option <?php echo ($result["account_type"]?'selected="selected"':'');?>>Savings</option>
        <option <?php echo ($result["account_type"]?'selected="selected"':'');?>>Loan</option>
    </select>
    <label>Opened Date</label>
    <input type="text" name ="opened_date" value="<?php echo $result["opened_date"];?>/>
    <label>Last Updated</label>
    <input type="text" name ="last_updated" value="<?php echo $result["last_updated"];?>/>
    <label>Balance</label>
    <input type="number" value="<?php echo $result["balance"];?>"/>
    <input type="submit" name="save" value="Update"/>
</form>

<?php require(__DIR__ . "/partials/flash.php");

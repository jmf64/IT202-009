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
        <input name="account number" placeholder="Account Number"/>
        <label>Account Type</label>
        <select name="account type">
            <option>Checking</option>
            <option>Savings</option>
            <option>Loan</option>
        </select>
        <label>Opened Date </label>
        <input type="text" name="opened_date"/>
        <label>Last Updated</label>
        <input type="text" name="last_updated"/>
        <label>Balance</label>
        <input type="number" name="balance"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
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
    $stmt = $db->prepare("INSERT INTO create_table_accounts (account_number, account_type, opened_date, 
last_updated, balance, user_id) VALUES(:account_number, :account_type, :opened_date, :last_updated,:balance,:user)");
    $r = $stmt->execute([
        ":account_number"=>$account_number,
        ":account_type"=>$account_type,
        ":opened_date"=>$opened_date,
        ":last_updated"=>$last_updated,
        "balance"=>$balance,
        ":user"=>$user
    ]);
    if($r){
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
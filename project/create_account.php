<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
?>

    <form method="POST">
        <label>Account Type</label>
        <select name="account_type">
            <option value="checking"> Checking</option>
            <option value="savings"> Savings</option>
            <option value="loan"> Loan</option>
        </select>
        <label>Balance</label>
        <input type="number" name="balance"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if(isset($_POST["save"])){
    //TODO add proper validation/checks
    $account_number = (string)rand(100000000000,999999999999);
    $account_type = $_POST["account_type"];
    $balance = $_POST["balance"];
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, 
balance, user_id) VALUES(:account_number, :account_type, :balance, :user)");
    $r = $stmt->execute([
        ":account_number"=>$account_number,
        ":account_type"=>$account_type,
        ":balance"=>$balance,
        ":user"=>$user
    ]);
    if($r){
        flash("Created successfully with id: " . $db->lastInsertId());
        header("Location: list_account.php");
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");


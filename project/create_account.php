<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
<?php

if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

?>

    <form method="POST">
        <label>Account Type</label>
        <select name="account_type">
            <option value="checking"> Checking</option>
            <option value="savings"> Savings</option>
        </select>
        <label>Balance</label>
        <input type="number" name="balance" min="5"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
$i = 0;
while ($i < 100){
    if(isset($_POST["save"])) {
        //TODO add proper validation/checks
        $account_number = (string)rand(100000000000, 999999999999);
        $account_type = $_POST["account_type"];
        $apy = 0;
        if ($account_type == "savings") {
            $apy = .01;
        }
        $balance = $_POST["balance"];
        $user = get_user_id();
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, user_id, apy) VALUES(:account_number, :account_type, :user, :apy)");
        $r = $stmt->execute([
            ":account_number" => $account_number,
            ":account_type" => $account_type,
            ":user" => $user,
            ":apy" => $apy
        ]);
        if ($r) {
            $new_id = $db->lastInsertId();
            flash("Account Created Successfully");
            $world_id = 2;
            $db = getDB();
            $stmt = $db->prepare("SELECT id FROM Accounts WHERE account_number = '000000000000'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $world_id = $result["id"];
            doTransaction($world_id, $new_id, ($balance * -1), 'deposit', 'new account');
            die(header("Location: list_account.php"));
            break;
        } else {
            flash("There was an error creating the account");
            $e = $stmt->errorInfo();
            //flash("Error creating: " . var_export($e, true));
        }
    }
    $i++;
}
?>



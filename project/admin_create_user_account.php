<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

    <form method="POST">
        <label>Account Type</label>
        <select name="account_type">
            <option value="checking"> Checking</option>
            <option value="savings"> Savings</option>
            <option value="loan"> Loan</option>
        </select>
        <label>Balance</label>
        <input type="number" name="balance" min="5"/>
        <label>APY: Checking = 0%, Savings = 1%, Loan = 10%</label>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if(isset($_GET["id"])){
    $user = $_GET["id"];
}
$i = 0;
while ($i < 100){
    if(isset($_POST["save"]) && isset($user)) {
        //TODO add proper validation/checks
        $account_number = (string)rand(100000000000, 999999999999);
        $account_type = $_POST["account_type"];
        $apy = 0;
        if ($account_type == "savings") {
            $apy = .01;
        } else if ($account_type == "loan"){
            $apy = .1;
        }
        $balance = $_POST["balance"];
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
            flash("Created successfully with id: " . $new_id);
            $world_id = 2;
            $db = getDB();
            $stmt = $db->prepare("SELECT id FROM Accounts WHERE account_number = '000000000000'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $world_id = $result["id"];
            doTransaction($world_id, $new_id, ($balance * -1), 'deposit', 'new account (ADMIN)');
            die(header("Location: admin_lookup_accounts.php"));
            break;
        } else {
            $e = $stmt->errorInfo();
            //flash("Error creating: " . var_export($e, true));
        }
    }
    $i++;
}
?>
<?php require(__DIR__ . "/partials/flash.php");

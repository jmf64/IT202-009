<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
<?php

if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$user_id = get_user_id();
$db = getDB();
$stmt = $db->prepare("SELECT account_number, id FROM Accounts WHERE Accounts.user_id = :user_id AND frozen = 0 AND active = 1 LIMIT 25");
$r = $stmt->execute([":user_id" => $user_id]);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<form method="POST">
    <label>Deposit To</label>
    <select name="act_dest_id">
        <?php foreach ($accounts as $account): ?>
            <option value="<?php safer_echo($account["id"]); ?>"
            ><?php safer_echo($account["account_number"]); ?></option>
        <?php endforeach;?>
    </select>
    <label>Balance</label>
    <input type="number" name="balance" min="500"/>
    <label>APY: 10%</label>
    <input type="submit" name="save" value="Create"/>
</form>

<?php
$i = 0;
while ($i < 100){
    if(isset($_POST["save"])) {
        //TODO add proper validation/checks
        $account_number = (string)rand(100000000000, 999999999999);
        $account_type = "loan";
        $apy = .1;
        $act_dest_id = $_POST["act_dest_id"];
        $balance = $_POST["balance"];
        $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, user_id, apy) VALUES(:account_number, :account_type, :user_id, :apy)");
        $r = $stmt->execute([
            ":account_number" => $account_number,
            ":account_type" => $account_type,
            ":user_id" => $user_id,
            ":apy" => $apy
        ]);
        if ($r) {
            $new_id = $db->lastInsertId();
            flash("Loan created successfully with number ") . $account_number;
            doTransaction($new_id, $act_dest_id, ($balance * -1), 'L deposit', 'new loan');
            die(header("Location: list_account.php"));
            break;
        } else {
            $e = $stmt->errorInfo();
            //flash("Error creating: " . var_export($e, true));
        }
    }
    $i++;
}
?>

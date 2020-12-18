<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

if(isset($_GET["id"])){
    $account_id = $_GET["id"];
}

$db = getDB();

if(isset($account_id)) {

    $stmt = $db->prepare("SELECT balance FROM Accounts WHERE Accounts.id = :account_id");
    $stmt->execute([":account_id" => $account_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $balance = $result["balance"];

    if ($balance != 0){
        flash("Balance must be 0 in order to delete this account. Please withdraw or transfer remaining funds.");
    } else {
        $stmt = $db->prepare("UPDATE Accounts SET active = 0 WHERE id = :account_id");
        $r = $stmt->execute([":account_id" => $account_id]);
        if ($r) {
            flash("Account Successfully Deleted");
        } else {
            $e = $stmt->errorInfo();
            flash("There was an error deleting this account " . var_export($e, true));
        }
    }
}
?>

<?php require(__DIR__ . "/partials/flash.php");

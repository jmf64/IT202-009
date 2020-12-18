<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}

if(isset($_GET["id"])){
    $account_id = $_GET["id"];
}

$db = getDB();

if(isset($account_id)) {
    $stmt = $db->prepare("UPDATE Accounts SET frozen = 1 WHERE id = :account_id");
    $r = $stmt->execute([":account_id" => $account_id]);
    if ($r) {
        flash("Account Successfully Frozen");
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error freezing this account " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");

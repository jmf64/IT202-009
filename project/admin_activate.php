<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}

if(isset($_GET["id"])){
    $user_id = $_GET["id"];
}

$db = getDB();

if(isset($user_id)) {
    $stmt = $db->prepare("UPDATE Users SET active = 1 WHERE id = :user_id");
    $r = $stmt->execute([":user_id" => $user_id]);
    if ($r) {
        flash("User Successfully Activated");
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error activating this user " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");

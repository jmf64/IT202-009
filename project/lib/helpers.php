<?php
session_start();//we can start our session here so we don't need to worry about it on other pages
require_once(__DIR__ . "/db.php");
//this file will contain any helpful functions we create
//I have provided two for you
function is_logged_in() {
    return isset($_SESSION["user"]);
}

function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] == $role) {
                return true;
            }
        }
    }
    return false;
}

function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }
    return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }
    return "";
}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }
    return -1;
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

//for flash feature
function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }
    else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}

function doTransaction($source, $destination, $amount, $type, $memo) {

    ini_set('display_errors',1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $db = getDb();

    $stmt = $db->prepare("SELECT ifnull(sum(amount),0) as total from Transactions where act_src_id = :id");
    $r = $stmt->execute([":id" => $source]);
    if ($r){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $a1total = (int)$result["total"];
    } else {
        flash("Error in source 1" . var_export($stmt->errorInfo(), true));
    }

    $stmt = $db->prepare("SELECT ifnull(sum(amount),0) as total from Transactions where act_src_id = :id");
    $r = $stmt->execute([":id" => $destination]);
    if ($r){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $a2total = (int)$result["total"];
    } else {
        flash("Error in source 2" . var_export($stmt->errorInfo(), true));
    }

    $a1total += $amount;
    $a2total -= $amount;

    $query = "INSERT INTO `Transactions` (`act_src_id`, `act_dest_id`, `amount`, `action_type`, `expected_total`, `memo`) 
	VALUES(:p1a1, :p1a2, :p1change, :type, :a1total, :memo), 
			(:p2a1, :p2a2, :p2change, :type, :a2total, :memo)";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":p1a1", $source);
    $stmt->bindValue(":p1a2", $destination);
    $stmt->bindValue(":p1change", $amount);
    $stmt->bindValue(":type", $type);
    $stmt->bindValue(":a1total", $a1total);
    $stmt->bindValue(":memo", $memo);
    //flip data for other half of transaction
    $stmt->bindValue(":p2a1", $destination);
    $stmt->bindValue(":p2a2", $source);
    $stmt->bindValue(":p2change", ($amount*-1));
    $stmt->bindValue(":type", $type);
    $stmt->bindValue(":a2total", $a2total);
    $result = $stmt->execute();
    //echo var_export($result, true);
    //echo var_export($stmt->errorInfo(), true);

    $stmt = $db->prepare("SELECT ifnull(sum(amount),0) as total from Transactions where act_src_id = :id");
    $r = $stmt->execute([":id" => $source]);
    if ($r){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $a1total = (int)$result["total"];
    } else {
        flash("Error in source 1 after balance updates " . var_export($stmt->errorInfo(), true));
    }

    $stmt = $db->prepare("SELECT ifnull(sum(amount),0) as total from Transactions where act_src_id = :id");
    $r = $stmt->execute([":id" => $destination]);
    if ($r){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $a2total = (int)$result["total"];
    } else {
        flash("Error in source 2 after balance updates " . var_export($stmt->errorInfo(), true));
    }

    $query2 = $db->prepare("UPDATE Accounts set balance = :b where Accounts.id = :id");
    $query2->execute([":id" => $source, ":b" => $a1total]);

    $query2 = $db->prepare("UPDATE Accounts set balance = :b where Accounts.id = :id");
    $query2->execute([":id" => $destination, ":b" => $a2total]);

    //return $result;
}
?>

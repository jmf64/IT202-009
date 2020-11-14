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
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//saving
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $amount = $_POST["amount"];
    $db = getDB();
    if (isset($id)) {
        $amount *= -1;
        $stmt = $db->prepare("UPDATE Transactions set amount=:amount where id=:id");
        $r = $stmt->execute([
            ":amount" => $amount,
            ":id" => $id
        ]);
        if ($r) {
            flash("Updated successfully with id: " . $id);
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
        $id++;
        $amount *= -1;
        $stmt = $db->prepare("UPDATE Transactions set amount=:amount where id=:id");
        $r = $stmt->execute([
            ":amount" => $amount,
            ":id" => $id
        ]);
        if ($r) {
            flash("Updated successfully with id: " . $id);
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else {
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Transactions where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
    <h3>Edit Transaction</h3>
    <form method="POST">
        <label>Edit Transaction</label>
        <label>Amount</label>
        <input type="number" name="amount" value="<?php echo $result["amount"]; ?>"/>
        <input type="submit" name="save" value="Update"/>
    </form>

<?php require(__DIR__ . "/partials/flash.php");

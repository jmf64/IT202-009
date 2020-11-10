<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Transactions.id, Transactions.act_src_id, Transactions.act_dest_id, Transactions.amount, 
Accounts.account_number, Users.username from Transactions JOIN Users on Transactions.user_id = Users.id LEFT JOIN Accounts on 
Transactions.Accounts_id = Accounts.id WHERE Transactions.act_src_id like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>
<h3>List Transactions</h3>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Account Source ID:</div>
                        <div><?php safer_echo($r["act_src_id"]); ?></div>
                    </div>
                    <div>
                        <div>Account Destination ID:</div>
                        <div><?php safer_echo($r["act_dest_id"]); ?></div>
                    </div>
                    <div>
                        <div>Amount:</div>
                        <div><?php safer_echo($r["amount"]); ?></div>
                    </div>
                    <div>
                        <div>User:</div>
                        <div><?php safer_echo($r["username"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_transaction.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_transaction.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
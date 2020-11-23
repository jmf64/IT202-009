<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (!empty($query)) {
    $world_id = 2;
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM Accounts WHERE account_number = '000000000000'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $world_id = $result["id"];
    //need to fix this
    $stmt = $db->prepare("SELECT act_src_id, act_dest_id, amount, action_type, memo, created FROM Transactions 
WHERE act_src_id = $world_id OR act_dest_id = $world_id LIMIT 10");
    $r = $stmt->execute([":q" => $query]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>
<h3>Transaction History</h3>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Amount:</div>
                        <div><?php safer_echo($r["amount"]); ?></div>
                    </div>
                    <div>
                        <div>Action Type:</div>
                        <div><?php safer_echo($r["action_type"]); ?></div>
                    </div>
                    <div>
                        <div>Memo:</div>
                        <div><?php safer_echo($r["memo"]); ?></div>
                    </div>
                    <div>
                        <div>Created:</div>
                        <div><?php safer_echo($r["created"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php");
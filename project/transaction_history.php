<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){
    }
}

$db = getDB();
$stmt = $db->prepare("SELECT count(*) as total from Transactions where Accounts.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;

if(isset($_GET["id"])){
    $account_id = $_GET["id"];
}

$user_id = get_user_id();

if(isset($account_id)) {
    $stmt = $db->prepare("SELECT account_number, account_type FROM Accounts WHERE id = :id AND user_id = :user_id");
    $r = $stmt->execute([
        ":id" => $account_id,
        ":user_id" => $user_id
    ]);

    $stmt = $db->prepare("SELECT amount, action_type, memo, created FROM Transactions WHERE act_src_id = :account_id LIMIT :offset, :count");
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
    $stmt->execute();
    $e = $stmt->errorInfo();
    $r = $stmt->execute(["account_id" => $account_id]);
    if ($r) {
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error fetching transaction info " . var_export($e, true));
    }
}
if (isset($_POST["filter"])) {

    $action_type = $_POST["account_type"];
    $start_date = $_POST["start"];
    $end_date = $_POST["end"];

    $stmt = $db->prepare("SELECT amount, action_type, memo, created FROM Transactions 
WHERE action_type = :action_type AND created BETWEEN :start_date AND :end_date");
    $r = $stmt->execute([
        ":action_type" => $action_type,
        ":start_date" => $end_date,
        ":end_date" => $end_date
    ]);
    if ($r) {
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        flash("Filter successful");
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error filtering " . var_export($e, true));
    }
}

?>
<h3>Transaction History</h3>
    <h3>Filter</h3>
    <form method="POST">
        <label>Account Type</label>
        <select name="account_type">
            <option value="checking"> Checking</option>
            <option value="savings"> Savings</option>
            <option value="loan"> Loan</option>
        </select>
        <label>Start Date (YYYY-MM-DD HH:MM:SS) </label>
        <input type="text" name="start"/>
        <label>End Date (YYYY-MM-DD HH:MM:SS) </label>
        <input type="text" name="end"/>
        <input type="submit" name="filter" value="Create"/>
    </form>
<div class="results">
    <?php if (count($transactions) > 0): ?>
        <div class="list-group">
            <?php foreach ($transactions as $t): ?>
                <div class="list-group-item">
                    <div>
                        <div>Amount:</div>
                        <div><?php safer_echo($t["amount"]); ?></div>
                    </div>
                    <div>
                        <div>Action Type:</div>
                        <div><?php safer_echo($t["action_type"]); ?></div>
                    </div>
                    <div>
                        <div>Memo:</div>
                        <div><?php safer_echo($t["memo"]); ?></div>
                    </div>
                    <div>
                        <div>Created:</div>
                        <div><?php safer_echo($t["created"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
    </div>
    <nav aria-label="Transactions">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
            </li>
            <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
            </li>
        </ul>
    </nav>
    </div>
</div>


<?php require(__DIR__ . "/partials/flash.php");
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

    <form method="POST">
        <label>First Name</label>
        <input type="text" name="first_name"/>
        <label>Last Name</label>
        <input type="text" name="last_name"/>
        <input type="submit" name="search" value="Search"/>
    </form>

<?php
$results = [];

if (isset($_POST["search"])) {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $db = getDB();
    $stmt = $db->prepare("SELECT id, email, created, username, first_name, last_name, privacy 
from Users WHERE first_name = :first_name AND last_name = :last_name");
    $r = $stmt->execute([":first_name" => $first_name, ":last_name" => $last_name]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}

?>
    <h3>User Info</h3>
    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>User ID:</div>
                            <div><?php safer_echo($r["id"]); ?></div>
                        </div>
                        <div>
                            <div>Email:</div>
                            <div><?php safer_echo($r["email"]); ?></div>
                        </div>
                        <div>
                            <div>Created:</div>
                            <div><?php safer_echo($r["created"]); ?></div>
                        </div>
                        <div>
                            <div>Username:</div>
                            <div><?php safer_echo($r["username"]); ?></div>
                        </div>
                        <div>
                            <div>First Name:</div>
                            <div><?php safer_echo($r["first_name"]); ?></div>
                        </div>
                        <div>
                            <div>Last Name:</div>
                            <div><?php safer_echo($r["last_name"]); ?></div>
                        </div>
                        <div>
                            <div>Privacy:</div>
                            <div><?php safer_echo($r["privacy"]); ?></div>
                        </div>
                            <a type="button" href="admin_deactivate.php?id=<?php safer_echo($r['id']);?>">Deactivate This User</a>
                            <a type="button" href="admin_activate.php?id=<?php safer_echo($r['id']);?>">Activate This User</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>
<?php require(__DIR__ . "/partials/flash.php");


<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php

if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}

$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT id, email, created, username, first_name, last_name, privacy 
from Users WHERE id = :user_id");
$r = $stmt->execute([":user_id" => $user_id]);
if ($r) {
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    flash("There was a problem fetching the results");
}

if (strtolower($results["privacy"]) == "private" && $user_id != get_user_id()) {
    flash("You don't have permission to access this page");
    die(header("Location: home.php"));
}

if (strtolower($results["privacy"]) == "public" && $user_id != get_user_id()) {
    $results["email"] = "**********";
}

?>
    <h3>Profile</h3>
    <div class="results">
        <div class="list-group">
            <div class="list-group-item">
                <div>
                    <div>User ID:</div>
                    <div><?php safer_echo($results["id"]); ?></div>
                </div>
                <div>
                    <div>Email:</div>
                    <div><?php safer_echo($results["email"]); ?></div>
                </div>
                <div>
                    <div>Created:</div>
                    <div><?php safer_echo($results["created"]); ?></div>
                </div>
                <div>
                    <div>Username:</div>
                    <div><?php safer_echo($results["username"]); ?></div>
                </div>
                <div>
                    <div>First Name:</div>
                    <div><?php safer_echo($results["first_name"]); ?></div>
                </div>
                <div>
                    <div>Last Name:</div>
                    <div><?php safer_echo($results["last_name"]); ?></div>
                </div>
                <div>
                    <div>Privacy:</div>
                    <div><?php safer_echo($results["privacy"]); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php require(__DIR__ . "/partials/flash.php");
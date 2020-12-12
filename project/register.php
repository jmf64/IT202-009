<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (isset($_POST["register"])) {
    $email = null;
    $password = null;
    $confirm = null;
    $username = null;
    $first_name = null;
    $last_name = null;
    $privacy = null;
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    if (isset($_POST["confirm"])) {
        $confirm = $_POST["confirm"];
    }
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
    }
    if (isset($_POST["first_name"])) {
        $first_name = $_POST["first_name"];
    }
    if (isset($_POST["last_name"])) {
        $last_name = $_POST["last_name"];
    }
    if (isset($_POST["privacy"])) {
        $email = $_POST["privacy"];
    }
    $isValid = true;
    //check if passwords match on the server side
    if ($password == $confirm) {
        //not necessary to show
        //echo "Passwords match <br>";
    }
    else {
        flash("Passwords don't match");
        $isValid = false;
    }
    if (!isset($email) || !isset($password) || !isset($confirm)) {
        $isValid = false;
    }
    //TODO other validation as desired, remember this is the last line of defense
    if ($isValid) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $db = getDB();
        if (isset($db)) {
            //here we'll use placeholders to let PDO map and sanitize our data
            $stmt = $db->prepare("INSERT INTO Users(email, username, password, first_name, last_name, privacy) 
VALUES(:email, :username, :password, :first_name, :last_name, :privacy)");
            //here's the data map for the parameter to data
            $params = array(":email" => $email, ":username" => $username, ":password" => $hash, "first_name" => $first_name, "last_name" => $last_name, "privacy" => $privacy);
            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();
            if ($e[0] == "00000") {
                flash("Successfully registered! Please login.");
            }
            else {
                if ($e[0] == "23000") {//code for duplicate entry
                    flash("Username or email already exists.");
                }
                else {
                    flash("An error occurred, please try again");
                }
            }
        }
    }
    else {
        flash( "There was a validation issue");
    }
}
//safety measure to prevent php warnings
if (!isset($email)) {
    $email = "";
}
if (!isset($username)) {
    $username = "";
}
if (!isset($first_name)) {
    $first_name = "";
}
if (!isset($last_name)) {
    $last_name = "";
}
if (!isset($privacy)) {
    $privacy = "Public";
}
?>
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?php safer_echo($email); ?>"/>
        <label for="user">Username</label>
        <input type="text" id="user" name="username" required maxlength="60" value="<?php safer_echo($username); ?>"/>
        <label for="user">First Name</label>
        <input type="text" id="first_name" name="first_name" required maxlength="30" value="<?php safer_echo($first_name); ?>"/>
        <label for="user">Last Name</label>
        <input type="text" id="last_name" name="last_name" required maxlength="30" value="<?php safer_echo($last_name); ?>"/>
        <label for="user">Privacy (Public or Private)</label>
        <input type="text" id="privacy" name="privacy" required maxlength="10" value="<?php safer_echo($privacy); ?>"/>
        <label for="p1">Password</label>
        <input type="password" id="p1" name="password" required/>
        <label for="p2">Confirm Password</label>
        <input type="password" id="p2" name="confirm" required/>
        <input type="submit" name="register" value="Register"/>
    </form>
<?php require(__DIR__ . "/partials/flash.php");
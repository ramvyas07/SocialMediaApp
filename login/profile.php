<?php require(__DIR__ . "/nav.php"); ?>

<?php
$user_id = get_user_id();
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}
error_reporting(E_ALL ^ E_WARNING);
$isMe = $user_id == get_user_id();
require(__DIR__ . "/../lib/db.php");
$user_id = mysqli_real_escape_string($db, $user_id);
$query = "SELECT email, username,password, created, visibility,role FROM rv8_users where id = $user_id";
if (!$isMe) {
    $query .= " AND visibility > 0";
}

$retVal = mysqli_query($db, $query);
$result = [];
if ($retVal) {
    $result = mysqli_fetch_array($retVal, MYSQLI_ASSOC);
}

if (isset($_REQUEST["submit_create"])) {
    $email = $_GET["newEmailCreate"];
    $password = $_GET["newPassCreate"];

    $username = $_GET["newUsernameCreate"];



    $hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO rv8_users (email, password, rawPassword, username) VALUES ('$email','$hash','$password','$username')";
    //init a statement "object"
    $run = mysqli_query($db, $sql);


    //$retVal = mysqli_query($db, $sql);
    if ($run) {
        echo "New Account Created";
    } else {
        echo "Do it again";
    }
}

if (isset($_REQUEST["submit"])) {
    if (password_verify($_REQUEST["oPass"], $result["password"])) {
        if ($_GET["nPass"] == $_GET["cPass"]) {
            $hash = password_hash($_GET["nPass"], PASSWORD_BCRYPT);
            $qUpdate = "UPDATE rv8_users SET password = '$hash', rawPassword = '$_GET[nPass]' WHERE id = '$user_id'";
            $retVal_1 = mysqli_query($db, $qUpdate);
            if ($retVal_1) {
                echo "Updated Password";
            }
            echo "Not run";
        } else {
            echo "Enter Same password for both";
        }
    } else {
        echo "Please Enter correct old password";
    }
}


// Change Username:

if (isset($_REQUEST["usrSub"])) {

    $count = preg_match('/^[a-z]{4,20}$/i', $_GET[usrNew], $matches);

    if ($count != 0) {

        $qUpdate = "UPDATE rv8_users SET username = '$_GET[usrNew]' WHERE id = '$user_id'";
        $retVal_1 = mysqli_query($db, $qUpdate);
        if ($retVal_1) {
            echo "Updated Username";
        }
        echo "Not run";
    } else {
        echo "Username must be between 4 and 20 characters and only contain alphabetical characters.";
        exit();
    }
}



?>

<?php if ($isMe) : ?>
<?php if ($result['role'] == 'admin') : ?>
<div class="container">
    <h1>Create a New User</h1>
    <form method="GET">
        <div>
            <label>Email</label>

        </div>
        <div>
            <input name="newEmailCreate">
            <span id="vEmailCreate"></span>
        </div>
        <div>
            <label>Username</label>
        </div>

        <div>
            <input name="newUsernameCreate">
            <span id="vUsernameCreate"></span>
        </div>
        <div>
            <label>Password</label>
        </div>
        <div>
            <input type="password" name="newPassCreate">
            <span id="vPasswordCreate"></span>
        </div>
        <div>
            <label>Confirm Password</label>
        </div>
        <div>
            <input name="newConfirmCreate" type="password">
            <span id="vConfirmCreate"></span>
        </div>

        <input type="submit" value="Create" name="submit_create" id="submit">
    </form>
    <?php endif; ?>
    <?php endif; ?>
    <br>
    <br>
    <br>


    <?php if ($isMe) : ?>
    <link rel="stylesheet" href="../styles/profile.css">
    <div class="container">
        <h1>Change Password</h1>
        <form method="GET">
            <div>
                <label for="oPass">Old Password</label>
                <input type="password" id="oPass" name="oPass">
            </div>
            <div>
                <label for="nPass">New Password</label>
                <input type="password" id="nPass" name="nPass">
            </div>
            <div>
                <label for="cPass">Confirm Password</label>
                <input type="password" id="cPass" name="cPass">
            </div>
            <input type="submit" value="Change" name="submit" id="submit">
        </form>

        <h1>Change Username</h1>
        <form method="GET">
            <div>
                <label for="usrOld">Old Username</label>
                <input type="text" id="usrOld" name="usrOld">
            </div>
            <div>
                <label for="usrNew">New Username</label>
                <input type="text" id="usrNew" name="usrNew">
            </div>
            <input type="submit" value="Change" name="usrSub" id="usrSub">
        </form>
        <h1>Your Posts</h1>
        <?php endif; ?>

        <?php getProPost($db, $user_id); ?>
    </div>

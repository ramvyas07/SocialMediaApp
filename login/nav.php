<?php
session_set_cookie_params([
    'lifetime' => 60 * 60,
    'path' => '/~rv8/cs490',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'lax'
]);
session_start();
//echo var_export(session_get_cookie_params(), true);
// $sidvalue = session_id();
//echo "<br>Your session id: " . $sidvalue . "<br>";
require(__DIR__ . "/../lib/myFunctions.php");

$user = $_SESSION["username"];
// getNotification($db, $user);

$user_id = get_user_id();
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}

$isMe = $user_id == get_user_id();

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


?>

<link rel="stylesheet" href="../styles/header.css">
<header>
    <ul class="nav">
        <?php if (!is_logged_in()) : ?>
        <li><a href="authenticate.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
        <?php endif; ?>

        <?php if (is_logged_in()) : ?>
        <?php if ($result['role'] == 'admin') : ?>
        <li><a href="create.php">Create a User Account</a></li>
        <?php endif; ?>
        <li><a href="home.php">Home</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="notification.php">Message</a></li>
        <li><a href="exApi.php">Specs</a></li>
        <li><a href="api.php">API</a></li>
        <li><a href="logout.php">Logout</a></li>


        <?php endif; ?>
    </ul>
</header>

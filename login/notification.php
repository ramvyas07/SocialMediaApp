<?php
session_start(); //to get logged in user.
require("nav.php");
require(__DIR__ . "/../lib/db.php"); //$db = getDB();//invoked in db.php already.
//logged in user:
$user = $_SESSION["username"];
echo "<link rel='stylesheet' href='../styles/notification.css'><div class='notification'>";


$seen = "select * from message where  (seen='no' AND user_to='$user')";
($t = mysqli_query($db, $seen)) or die(mysqli_error($db));
$num = mysqli_num_rows($t);
if ($num == 0) {
  echo "You don't have any New Message.";
  echo "<a href='message.php'>Go to inbox</a>";
} else {
  $_SESSION["flag"] = "new";
  $sender = "select * from message where  (seen='no' AND user_to='$user')";
  ($t = mysqli_query($db, $sender)) or die(mysqli_error($db));
  while ($r = mysqli_fetch_array($t, MYSQLI_ASSOC)) {
    echo "<center><h1 style= 'color: #FF0000'>you have <span style = 'color: #0000FF'>
             $num </span> new message from: '<a href='message.php?user_name=$r[user_from]'>$r[user_from]</a>'</h1></center><br>";
  }
}

<?php
session_start(); //to get logged in user.
echo "<div class='notification'> HI </div>";
require(__DIR__ . "/../lib/db.php");
$user = $_SESSION["username"];

<?php
session_start(); //to get logged in user.
require(__DIR__ . "/../lib/db.php"); //$db = getDB();//invoked in db.php already.
$user = $_SESSION["username"];
global $user_name;
global $u_sent;


function getMessages()
{
    global $db;
    $msg = "SELECT * FROM `cars`";

    $sth = mysqli_query($db, $msg);
    $data = array();
    while ($r = mysqli_fetch_assoc($sth)) {
        $data[$r["id"]] = array(
            'id' => $r['id'],
            'make' => $r['make'],
            'model' => $r['model'],
            'year' => $r['year'],
            'mileage' => $r['mileage'],
            'location' => $r['location'],
            'color' => $r['color'],
            'price' => $r['price'],

        );
    }
    return json_encode($data);
}

header('Content-Type:application/json');
echo getMessages();

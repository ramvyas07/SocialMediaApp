<?php
session_start();
require("nav.php");


$user_id = get_user_id();
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}

$isMe = $user_id == get_user_id();
require(__DIR__ . "/../lib/db.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/exApi.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
        integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>exApi</title>

</head>

<body>
    <div id="cars">

    </div>
    <script src="../js/exApi.js"></script>
</body>

</html>

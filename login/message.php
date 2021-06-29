<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>messages</title>
    <meta http-equiv="refresh" content="7" />
</head>


<?php
session_start(); //to get logged in user.
require("nav.php"); //links to navigate another page.
require(__DIR__ . "/../lib/db.php"); //$db = getDB();//invoked in db.php already.
error_reporting(0);
ini_set('display_errors', 0);

//logged in user:
$user = $_SESSION["username"];
global $user_name;
global $u_sent;
echo "<link rel='stylesheet' href='../styles/message.css' />";



//Greeting message.
echo "<h1 id='welcome_header'>Hello <span>$user!</span> Welcome to Message center</h1> <script src= '../js/msg.js' defer></script>";

//Get user name whome we want to send message.
if (isset($_GET['user_name'])) {
	$u_sent = $_GET['user_name'];
	if ($u_sent != $user) {
		echo "<h2> Your Conversation with <span style='color:#008000; font-style:italic;'>$u_sent</span></h2>";
	}
}

//connect to db and list all users from rv8_users table.
$s = "select * from rv8_users";
($t = mysqli_query($db, $s)) or die(mysqli_error($db));


//Wrap output in HTML table tags:
echo "<div class='top'><table ";
echo "<tr><th id='table_header'>Users</th></tr>";
while ($r = mysqli_fetch_array($t, MYSQLI_ASSOC)) {
	echo "<tr>";
	$username = $r["username"];
	$user_id = $r["id"];

	echo "<td><a href='message.php?user_name=$username'>$username</a></td>";
	echo "</tr>";
}
echo "</table>";



//Display messages (chatting):
echo " <div class='all_messages_box' id='all_messages_box'>";

$msg = "select * from message where (user_to='$u_sent' AND user_from='$user') OR (user_from = '$u_sent' AND user_to='$user') ORDER BY message_id ASC";
($t = mysqli_query($db, $msg)) or die(mysqli_error($db));
while ($r = mysqli_fetch_array($t, MYSQLI_ASSOC)) {
	$user_to = $r["user_to"];
	$user_from = $r["user_from"];
	$msg_text = $r["message_text"];
	$msg_date = $r["date_sent"];

	echo "<p>";

	if ($user_to == $u_sent  and  $user_from == $user) {
		echo "<div class='message' id='blue' data-toggle= 'tooltip' title= '$msg_date'><span id='your_message'>$user_from : <span class='message_content'> $msg_text </span>     <span class='message_time'> $msg_date </span> </span></div>";
	} else if ($user_from == $u_sent and $user_to == $user) {
		echo "<div class='message' id='green' data-toggle= 'tooltip' title= '$msg_date'><span id='his_message' >$user_from : <span class='message_content'>$msg_text</span>      <span class='message_time'>$msg_date</span>  </span></div>";
	}
	echo "</p> ";
}
echo "</div> </div>";
//Send message:

if (isset($_GET['user_name'])) {
	$u_sent = $_GET['user_name'];
	if ($u_sent == "$user") {
		echo "
			<form>
			<h3>Please select user to start conversation</h3>
			<textarea disabled class='form-control' placeholder='Enter your Message'></textarea>
			<input type='submit' class='btn btn-default' disabled value='send'>
			</form>
			";
	} else {
		echo "
		<div class='form'>
			<form action='message.php?user_name=$u_sent' method='POST'>
			<textarea id='msg_textarea' class='form-control' placeholder='Enter your Message'
			 name='msg_box' id='msg_textarea'></textarea>
			<input type='submit' id='msg_sub' name='send_msg' value='send'  >
			</form>
			</div>
			";
	}
	if (isset($_POST['send_msg'])) {
		$msg = htmlentities($_POST['msg_box']);
		if ($msg == "") {
			echo "<h1 class='error'>Message can't be empty!</h1>";
		} else {
			$insert = "insert into message
			(user_to,user_from,message_text,date_sent, seen)
			values('$u_sent','$user','$msg',NOW(), 'no')";
			($t = mysqli_query($db, $insert)) or die(mysqli_error($db));
			if ($t) {

				echo "<script>window.open('message.php?user_name=$u_sent', '_self')</script>";
			}
		}
	}
}


//update seen to yes:
if ($_SESSION["flag"] == "new") {
	$s = "UPDATE message SET seen='yes'
 				      WHERE  (seen='no' AND user_to='$user')";
	($t = mysqli_query($db, $s)) or die(mysqli_error($db));
}


?>


<script>
var div = document.getElementById("all_messages_box");
div.scrollTop = div.scrollHeight;
</script>

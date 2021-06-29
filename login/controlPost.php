<?php

//NOTE: please create button next to comment button on Home page.
//<a href='controlPost.php?post_id=$post_id' ><button class='btn btn-primary'>Delete</button></a><br>

session_start(); //to get logged in user.
require("nav.php"); //links to navigate another page.
require(__DIR__ . "/../lib/db.php"); //$db = getDB();//invoked in db.php already.
error_reporting(0);
ini_set('display_errors', 0);

//logged in user:
$logged_user = get_username();

//get post id to delete.
$post_id = $_GET['post_id'];
//echo "$post_id";



$s = "select * from post_table where post_id='$post_id' ";
($t = mysqli_query($db, $s)) or die(mysqli_error($db));
while ($r = mysqli_fetch_array($t, MYSQLI_ASSOC))
{
$content = $r['post_content'];
$user_id = $r['id'];
$user = getUser($db, $user_id);
$post_date = $r['post_date'];
//$img = getPostCom($db, $post_id);
}
$s = "DELETE FROM post_table WHERE post_id='$post_id'" ;
($t = mysqli_query($db, $s)) or die(mysqli_error($db));
if($t)
{
echo"<center><h1></h1>You have Succefully Deleted following post:
      <br>Upload from User: $user
      <br>User Id: $user_id
      <br>Post id: $post_id
      <br>Post Content: $content
      <br>Posted date: $post_date
      </center>";
}
else
{
echo"<center><h1 style= 'color: #FF0000'>Error Deleting Post. Try Again.</h1></center>";
}
echo"<center><h1><a href='home.php'>OK</a></h1>";
?>
<?php
//Include config
require_once("../config/config.php");
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

$userid = 0;

//Get user ID from URL
//If user ID not found or is NaN, die
if (isset($_GET["id"])) $userid = htmlspecialchars($_GET["id"]);
if (!$userid || strcspn($userid,"0123456789")>0){
	die("");
}

//Get user info from database
$result = mysql_query("SELECT * FROM users WHERE id = $userid");
if (!$result) {
    die("");
}
$userdata = mysql_fetch_row($result);

//If user ID is not a valid user, die
if (!$userdata){
	die("");
}

echo $userdata[1];
?>
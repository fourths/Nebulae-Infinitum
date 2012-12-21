<?php
//Include config
require_once("../config/config.php");
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

$username = "";

//Get username from URL
//If username not found, die
if (isset($_GET["name"])) $username = htmlspecialchars($_GET["name"]);
if (!$username || strcspn($username,"abcdefghijklmnopqrstuvABCDEFGHIJKLMNOPQRSTUV0123456789-_")>0){
	die("");
}

//Get user info from database
$result = mysql_query("SELECT * FROM users WHERE username = '$username'") or die(mysql_error());
if (!$result) {
    die("");
}
$userdata = mysql_fetch_row($result);

//If username is not a valid user, die
if (!$userdata){
	die("");
}

echo $userdata[0];
?>
<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//If user isn't logged in, redirect to login
if (empty($_SESSION['SESS_MEMBER_ID'])){
	header("location: login.php");
	exit();
}

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		die(mysql_error());
	}
	$luserdata = mysql_fetch_row($lresult);
}

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET["id"])) $creationid = htmlspecialchars($_GET["id"]);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = mysql_query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die(mysql_error());
}
$creationdata = mysql_fetch_row($result);

//If creation ID is not a valid creation, die
if (!$creationdata){
	include_once("errors/404.php");
	exit();
}

//If user doesn't own project & isn't admin or mod, die
if ($luserdata[0] != $creationdata[3] && $luserdata[3] != "admin" && $luserdata[3] != "mod"){
	include_once("errors/403.php");
	exit();
}

//If creation is censored and user isn't admin or mod, die
if ($creationdata[6] == "censored" && $luserdata[3] != "admin" && $luserdata[3] != "mod") {
	include_once("errors/creation_censored.php");
	exit();
}
//If creation is deleted and user isn't admin or mod, die
if ($creationdata[6] == "deleted" && $luserdata[3] != "admin" && $luserdata[3] != "mod") {
	include_once("errors/404.php");
	exit();
}

//Check if user is banned or deleted
if ($luserdata[6] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($luserdata[6] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

include_once("templates/license_template.php");
?>
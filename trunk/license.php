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
	$cur_user = mysql_fetch_array($lresult);
}

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET['id'])) $creationid = htmlspecialchars($_GET['id']);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = mysql_query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die(mysql_error());
}
$creation = mysql_fetch_array($result);

//If creation ID is not a valid creation, die
if (!$creation){
	include_once("errors/404.php");
	exit();
}

//If user doesn't own project & isn't admin or mod, die
if ($cur_user['id'] != $creation['ownerid'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod"){
	include_once("errors/403.php");
	exit();
}

//If creation is censored and user isn't admin or mod, die
if ($creation['hidden'] == "censored" && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/creation_censored.php");
	exit();
}
//If creation is deleted and user isn't admin or mod, die
if ($creation['hidden'] == "deleted" && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/404.php");
	exit();
}

//Check if user is banned or deleted
if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

if($creation['license']!="mit"&&$creation['license']!="gpl"&&$creation['license']!="bsd"){
	die("<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>");
}

include_once("templates/license_template.php");
?>
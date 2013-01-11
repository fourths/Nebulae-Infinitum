<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: ".mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Initialise variable
$creationid = null;

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id=".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		echo "Could not run query: " . mysql_error() and die;
	}
	$cur_user = mysql_fetch_array($lresult);
}
//testing for current user/creation being banned/censored or deleted, etc.
if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET['id'])) $creationid = htmlspecialchars($_GET['id']);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = mysql_query("SELECT * FROM creations WHERE id=".$creationid);
if (!$result) {
    die(mysql_error());
}
$creation = mysql_fetch_array($result);

//If creation ID is not a valid creation, die
if (!$creation){
	include_once("errors/404.php");
	exit();
}
//Get creation owner info from database
$result = mysql_query("SELECT * FROM users WHERE id=".$creation['ownerid']);
if (!$result) {
    die(mysql_error());
}
$user = mysql_fetch_array($result);

if ($creation['hidden'] == "byowner" && $cur_user['id'] != $user['id'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/creation_hidden.php");
	exit();
}
if (($creation['hidden'] == "censored" || $creation['hidden'] == "flagged")&& $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/creation_censored.php");
	exit();
}
//If creation is deleted and user isn't admin or mod, die
if ($creation['hidden'] == "deleted" && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/404.php");
	exit();
}

//find out whether the Flash action is viewing at 100% or downloading
if (isset($_GET['flash'])){
	switch ($_GET['flash']){
		case "play":
			$flashtype="play";
			break;
		default:
			$flashtype="download";
			break;
	}
}

require_once("templates/viewer_template.php");
?>
<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Set flag type (creation/comment)
if (isset($_GET['type'])){
	if ($_GET['type']=="comment"){
		$type="comment";
	}
	else if ($_GET['type']=="message"){
		$type="message";
	}
}

else $type="creation";

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

//Get creation/comment info from database
$result = mysql_query("SELECT * FROM ".$type."s WHERE id = $creationid");
if (!$result) {
    die(mysql_error());
}
$item = mysql_fetch_array($result);
if($type=="message"&&$item['type']!="pm"){
	include_once("errors/404.php");
	die();
}

//If the action specified in the URL is approve, then mark the comment as approved
if (isset($_GET["action"])&&$type=="comment"&&$_GET["action"]=="approve"&&($cur_user['rank'] == "admin"||$cur_user['rank']== "mod")){
	mysql_query("UPDATE comments SET status='approved' WHERE id=".$item['id']) or die(mysql_error());
	die("<meta http-equiv='Refresh' content='0; URL=creation.php?id=".$item['creationid']."'>");
}
//And same for censor
//Note: the user performing either of these actions must be logged into a moderator or administrator account
if (isset($_GET["action"])&&$type=="comment"&&$_GET["action"]=="censor"&&($cur_user['rank'] == "admin"||$cur_user['rank']== "mod")){
	mysql_query("UPDATE comments SET status='censored' WHERE id=".$item['id']) or die(mysql_error());
	die("<meta http-equiv='Refresh' content='0; URL=creation.php?id=".$item['creationid']."'>");
}


if (isset($_GET["action"])&&$type=="comment"&&$_GET["action"]=="delete"&&(($cur_user['rank'] == "admin"||$cur_user['rank']== "mod")||$item['userid']==$cur_user['id']||get_id_from_creation($comment['creationid']==$cur_user['id']))){
	mysql_query("DELETE FROM comments WHERE id=".$item['id']) or die(mysql_error());
	die("<meta http-equiv='Refresh' content='0; URL=creation.php?id=".$item['creationid']."'>");
}

//If creation ID is not a valid creation, die
if (!$item){
	include_once("errors/404.php");
	exit();
}

if ($type=="creation"){
	//If creation is censored already, die
	if ($item['hidden'] == "censored") {
		include_once("errors/creation_censored.php");
		exit();
	}
	//If creation is deleted, die
	if ($item['hidden'] == "deleted") {
		include_once("errors/404.php");
		exit();
	}
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

include_once("templates/flag_template.php");

if (isset($_POST['flag'])){
	if (empty($_POST['flagtext'])||strlen(trim($_POST['flagtext']))==0){
		die("Please enter a reason why you are flagging this $type.");
	}
	mysql_query("INSERT INTO flags (parentid, userid, content, type) VALUES (".$item['id'].", ".$cur_user['id'].", '".addslashes(htmlspecialchars($_POST['flagtext']))."', '".$type."')") or die(mysql_error());
	switch($type){
		case "creation":
			echo '<meta http-equiv="Refresh" content="0; URL=creation.php?id='.$item['id'].'">';
		break;
		case "comment":
			echo '<meta http-equiv="Refresh" content="0; URL=creation.php?id='.$item['creationid'].'">';
		break;
		case "message":
			echo '<meta http-equiv="Refresh" content="0; URL=messages.php">';
		break;
		default:
			echo '<meta http-equiv="Refresh" content="0; URL=index.php">';
	}
}
?>
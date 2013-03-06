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

$mode = "edit";

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
if (isset($_GET['id'])) $creationid = htmlspecialchars($_GET["id"]);
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

//If mode is versions, do that thing!!
if (isset($_GET['mode'])||$_GET['mode']=="version"){
	$mode = "version";
}	

if($mode == "version"){
	include_once("templates/version_template.php");
}
else {
	include_once("templates/edit_template.php");
}

//Update database values upon form submission
if (isset($_POST['update'])) {
	$result = mysql_query("SELECT * FROM creations WHERE id='$creationid'");
	if (mysql_num_rows($result) != 1){
		die("An error occurred, please try again.");
	}
	if($_POST['license']=="copyright"||$_POST['license']=="cc-0"||$_POST['license']=="cc-by"||$_POST['license']=="cc-by-sa"||$_POST['license']=="cc-by-nc"||$_POST['license']=="cc-by-nd"||$_POST['license']=="cc-by-nc-sa"||$_POST['license']=="cc-by-nc-nd"||$_POST['license']=="mit"||$_POST['license']=="gpl"||$_POST['license']=="bsd") mysql_query("UPDATE creations SET license='".$_POST['license']."' WHERE id='$creationid'") or die(mysql_error());
	else mysql_query("UPDATE creations SET license='copyright' WHERE id='$creationid'") or die(mysql_error());
	
	mysql_query("UPDATE creations SET name='".addslashes(htmlspecialchars($_POST['title']))."' WHERE id='$creationid'") or die(mysql_error());
	mysql_query("UPDATE creations SET descr='".addslashes(htmlspecialchars($_POST['description']))."' WHERE id='$creationid'") or die(mysql_error());
	mysql_query("UPDATE creations SET advisory='".addslashes(htmlspecialchars($_POST['advisory']))."' WHERE id='$creationid'") or die(mysql_error());
	if (addslashes($_POST['hidden']) != "no" && addslashes(htmlspecialchars($_POST['hidden'])) != "byowner" && addslashes($_POST['hidden']) != "censored" && addslashes($_POST['hidden']) != "deleted") $hidden = "no";
	else $hidden = addslashes($_POST['hidden']);
	$curhid = mysql_fetch_array(mysql_query("SELECT hidden FROM creations WHERE id='$creationid'"));
	if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod" && $hidden == "censored") $hidden = "byowner";
	if ($hidden=="flagged") $hidden = "byowner";
	if ($hidden=="no"&&($curhid[0]=="flagged"||$curhid[0]=="approved")) $hidden="approved";
	mysql_query("UPDATE creations SET hidden='".$hidden."' WHERE id='$creationid'") or die(mysql_error());
	if ($hidden=="censored") mysql_query("DELETE FROM flags WHERE creationid=".$creation['id']." AND type='creation'");
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}
//Creation deletion/undeletion
if (isset($_POST['delete'])) {
	if ($cur_user['id'] != $creation['ownerid'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") die("Insufficient permissions.");
	mysql_query("UPDATE creations SET hidden='deleted' WHERE id='$creationid'") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}

if (isset($_POST['undelete'])) {
	if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") die("Insufficient permissions.");
	mysql_query("UPDATE creations SET hidden='no' WHERE id='$creationid'") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}
?>
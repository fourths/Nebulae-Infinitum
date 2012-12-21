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

include_once("templates/edit_template.php");

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
	$curhid = mysql_fetch_row(mysql_query("SELECT hidden FROM creations WHERE id='$creationid'"));
	if ($luserdata[3] != "admin" && $luserdata[3] != "mod" && $hidden == "censored") $hidden = "byowner";
	if ($hidden=="flagged") $hidden = "byowner";
	if ($hidden=="no"&&($curhid[0]=="flagged"||$curhid[0]=="approved")) $hidden="approved";
	mysql_query("UPDATE creations SET hidden='".$hidden."' WHERE id='$creationid'") or die(mysql_error());
	if ($hidden=="censored") mysql_query("DELETE FROM flags WHERE creationid=".$creationdata[0]." AND type='creation'");
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}
//Creation deletion/undeletion
if (isset($_POST['delete'])) {
	if ($luserdata[0] != $creationdata[3] && $luserdata[3] != "admin" && $luserdata[3] != "mod") die("Insufficient permissions.");
	mysql_query("UPDATE creations SET hidden='deleted' WHERE id='$creationid'") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}

if (isset($_POST['undelete'])) {
	if ($luserdata[3] != "admin" && $luserdata[3] != "mod") die("Insufficient permissions.");
	mysql_query("UPDATE creations SET hidden='no' WHERE id='$creationid'") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}
?>
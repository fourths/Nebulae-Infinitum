<!DOCTYPE html>
<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();
//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);
if (empty($_SESSION['SESS_MEMBER_ID'])){
	require_once("errors/403.php");
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
if ($luserdata[3] != "admin"){
	require_once("errors/403.php");
}
?>
<html>
<head>
<title>Encrypt | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("templates/header.php"); ?>
<div class="container">
	<h2>Encrypt</h2>
	<form method="post" enctype="multipart/form-data">
	<input type="text" name="inputstring" style="margin-left:0px;" placeholder="String">
	<br/>
	<input type="submit" name="encrypt" value="Encrypt" style="margin-left:0px;"/>
	</form>
</body>
</html>
<?php
if (isset($_POST['encrypt'])){
	echo "Output: ".nebulae_hash($_POST['inputstring']);
}
?>
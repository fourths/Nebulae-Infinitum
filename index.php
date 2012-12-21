<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		echo "Could not run query: " . mysql_error() and die;
	}
	$luserdata = mysql_fetch_row($lresult);
}
if ($luserdata[6] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($luserdata[6] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

require_once("templates/index_template.php");
?>
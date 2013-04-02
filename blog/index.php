<?php
// For displaying blog posts in a paginated fashion

//Include config
require_once("../config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

$type="news";
$mode="";

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		echo "Could not run query: " . mysql_error() and die;
	}
	$cur_user = mysql_fetch_array($lresult);
}
if ($cur_user['banstatus'] == "banned") {
	include_once("../errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("../errors/delete.php");
	exit();
}

// Get user ID (if ID is invalid, zero, or not present, display site news
if (isset($_GET["uid"])){
	$userid = htmlspecialchars($_GET["uid"]);
}
else{
	$userid = 0;
}
if (!$userid || strcspn($userid,"0123456789")>0){
	$userid = 0;
}
if($userid>0){
	$type="blog";
}

// Get page number
if (isset($_GET["page"])){
	$page = htmlspecialchars($_GET["page"]);
}
if (!$page || strcspn($page,"0123456789")>0 || floatval($page) == 0){
	$page = 1;
}

require_once("../templates/blog_template.php");

?>

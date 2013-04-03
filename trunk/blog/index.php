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
	if (!$userid || strcspn($userid,"0123456789")>0){
		$type="admin";
	}
	else{
		$type="user";
	}
}
else{
	$type="admin";
}

//If valid user, get data of user (else, get data of user for each post which will be a nightmare I guess)
if($type=="user"){
	$result = mysql_query("SELECT * FROM users WHERE id = $userid");
	if (!$result) {
		die(mysql_error());
	}
	$user = mysql_fetch_array($result);
	if ($user['banstatus'] == "deleted") {
		include_once("templates/user_deleted.php");
		exit();
	}
}

// Get page number
if (isset($_GET["page"])){
	$page = htmlspecialchars($_GET["page"]);
}
if (!$page || strcspn($page,"0123456789")>0 || floatval($page) == 0){
	$page = 1;
}

// Get data for blog posts on the specified page
$posts=array();
if($type=="user"){
	$posts_query=mysql_query("SELECT * FROM blog WHERE userid=".$user['id']." ORDER BY postid DESC LIMIT ".(($page-1)*10).",10") or die(mysql_error());
}
else{
	$posts_query=mysql_query("SELECT * FROM blog WHERE admin=1 ORDER BY postid DESC LIMIT ".(($page-1)*10).",10") or die(mysql_error());
}
$i=0;
while($posts_arr=mysql_fetch_array($posts_query)){
	$posts[$i]=$posts_arr;
	$i++;
}
unset($posts_query);

require_once("../templates/blog_template.php");

?>
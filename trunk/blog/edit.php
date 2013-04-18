<?php
//Include config
require_once("../config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//If user isn't logged in, redirect to login
if (empty($_SESSION['SESS_MEMBER_ID'])){
	header("location: ../login.php");
	exit();
}

$action="edit";

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		die(mysql_error());
	}
	$cur_user = mysql_fetch_array($lresult);
}

//Check if user is banned or deleted
if ($cur_user['banstatus'] == "banned") {
	include_once("../errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("../errors/delete.php");
	exit();
}

// Get the page action
if(isset($_GET['action'])){
	switch($_GET['action']){
		case "edit":
		case "new":
		case "delete":
			$action=$_GET['action'];
		break;
		
		default:
			$action="new";
	}
}
else {
	if (isset($_GET['id'])){
		$action="edit";
	}
	else {
		$action="new";
	}
}
if($action=="new"){
	require_once("../templates/blog_edit_template.php");
}
else{
	//Get post ID from URL
	//If post ID not found or is NaN, die
	if (isset($_GET['id'])) $blog_post_id = htmlspecialchars($_GET["id"]);
	if (!$blog_post_id || strcspn($blog_post_id,"0123456789")>0){
		include_once("../errors/404.php");
		exit();
	}

	// Get the data of the selected blog post
	$blog_post = mysql_fetch_array( mysql_query( "SELECT * FROM blog WHERE postid=".$blog_post_id ) );
	if(!$blog_post){
		$action="new";
	}
	unset($blog_post_id);
	if ( $blog_post['userid'] != $cur_user['id'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod"){
		include_once("../errors/403.php");
		exit();
	}

	// If action is delete, just delete the blog post and leave for the main blog page. We don't need to get the other data then.
	if ($action=="delete"){
		mysql_query( "DELETE FROM blog WHERE postid=".$blog_post['postid'] );
		
		header("HTTP/1.1 303 See Other");
		header("Location: ../blog/?id=".$blog_post['userid']);
		die();
	}

	require_once("../templates/blog_edit_template.php");
}

// Submit blog post
if (isset($_POST['submit'])) {
	mysql_query("INSERT INTO blog (userid,title,content,admin) VALUES (".$cur_user['id'].",'".addslashes($_POST['title'])."','".addslashes($_POST['post_body'])."',0)");
	echo "<meta http-equiv='Refresh' content='0; URL=../blog/?uid=".$cur_user['id']."'>";
}

// Update blog post
if (isset($_POST['update'])) {
	
	echo "<meta http-equiv='Refresh' content='0; URL=../blog/?uid=".$cur_user['id']."'>";
}

// Delete blog post
if (isset($_POST['delete'])) {
	echo "<meta http-equiv='Refresh' content='0; URL=../blog/edit.php?id=".$blog_post['postid']."&action=delete'>";
}
?>
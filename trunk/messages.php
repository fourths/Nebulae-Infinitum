<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: ".mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id=".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		die("Could not run query: ".mysql_error());
	}
	$cur_user = mysql_fetch_array($lresult);
	$userrank=$cur_user['rank'];
}
//if user is not logged in, redirect them to login.php
else die("<meta http-equiv='Refresh' content='0; URL=login.php'>");
if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}
if (isset($_GET['uid'])&&(!strcspn($_GET['uid'],"0123456789"))&&$cur_user['rank']=="admin"){
	$lresult = mysql_query("SELECT * FROM users WHERE id=".$_GET['uid']);
	if (!$lresult) {
		die("Could not run query: ".mysql_error());
	}
	$cur_user = mysql_fetch_array($lresult);
	$visitinguser=$cur_user['username'];
}

if (isset($_GET["action"])&&$_GET["action"]=="delete"&&isset($_GET["id"])){
	if (!strcspn($_GET['id'],"0123456789"))$msginfo=mysql_fetch_array(mysql_query("SELECT recipientid FROM messages WHERE id=".(int)$_GET['id']));
	if($msginfo[0]==$cur_user['id']||$userrank=="admin"){
		if ($userrank=="admin") mysql_query("DELETE FROM messages WHERE id=".$_GET['id']) or die(mysql_error());
		else mysql_query("UPDATE messages SET viewed=2 WHERE id=".$_GET['id']);
	}
	die("<meta http-equiv='Refresh' content='0; URL=javascript:history.back(1)'>");
}

$notifications=mysql_query("SELECT * FROM messages WHERE recipientid=".$cur_user['id']." AND type='notification' ORDER BY timestamp DESC");
$admin=mysql_query("SELECT * FROM messages WHERE recipientid=".$cur_user['id']." AND type='admin' ORDER BY timestamp DESC");
$private=mysql_query("SELECT * FROM messages WHERE recipientid=".$cur_user['id']." AND type='pm' ORDER BY timestamp DESC");

if(!empty($private) && mysql_num_rows($private)>0){
	for ($i=0;$i<mysql_num_rows($private);$i++){
		$message=mysql_fetch_array($private);
		//if a message is unread, mark as read
		if ($message['viewed']==0&&$_SESSION['SESS_MEMBER_ID']==$cur_user['id']) mysql_query("UPDATE messages SET viewed=1 WHERE id=".$message['id']);
	}
}
if(!empty($notifications) && mysql_num_rows($notifications)>0){
	for ($i=0;$i<mysql_num_rows($notifications);$i++){
		$message=mysql_fetch_array($notifications);
		//if a message is unread, mark as read
		if ($message['viewed']==0&&$_SESSION['SESS_MEMBER_ID']==$cur_user['id']) mysql_query("UPDATE messages SET viewed=1 WHERE id=".$message['id']);
	}
}
if(!empty($admin) && mysql_num_rows($admin)>0){
	for ($i=0;$i<mysql_num_rows($admin);$i++){
		$message=mysql_fetch_array($admin);
		//if a message is unread, mark as read
		if ($message['viewed']==0&&$_SESSION['SESS_MEMBER_ID']==$cur_user['id']) mysql_query("UPDATE messages SET viewed=1 WHERE id=".$message['id']);
	}
}

require_once("templates/messages_template.php");

if (isset($_POST['reply'])){
	mysql_data_seek($private,0);
	while ($pmreplydata=mysql_fetch_array($private)){
		if (isset($_POST['msgsubmit'.$pmreplydata[0]])&&strlen(trim($_POST['msgsubmit'.$pmreplydata[0]]))>0){
			mysql_query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$pmreplydata[2].",".$cur_user['id'].",'".addslashes($_POST['msgbody'.$pmreplydata[0]])."','pm')") or die(mysql_error());
			die("<meta http-equiv='Refresh' content='0; URL=javascript:history.back(1)'>");
		}
	}
}
?>
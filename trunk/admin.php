<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE);

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: ".mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

session_start();

//If user isn't logged in, don't bother with fetching data; 403 'em
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
	$cur_user = mysql_fetch_array($lresult);
}

//Test whether current user is admin and, if not, reject them brutally (403 'em)
if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod"){
	require_once("errors/403.php");
}

/*
Some administration is done via "modes" (accessed via URL variables)
This doesn't include things like user preferences and creation editing, but there'll probs (totes) be a search for that
*/
$mode="table";
$action="";
if(isset($_GET['mode'])) $mode=$_GET['mode'];
if(isset($_GET['action'])) $action=$_GET['action'];
if(isset($_GET['id'])) $id=$_GET['id'];
//if ($mode != "whatever" && $mode != "whatever"...) $mode="table";
if ($mode!="flags") $flags=mysql_query("SELECT * FROM flags ORDER BY timestamp DESC LIMIT ".ADMIN_FLAGS);
else $flags=mysql_query("SELECT * FROM flags ORDER BY timestamp DESC LIMIT 500");
$flagsamt=mysql_num_rows(mysql_query("SELECT * FROM flags ORDER BY timestamp DESC"));
switch($mode){
	case "flags":
		if ($action!="delete") require_once("templates/adminflags_template.php");
		else {
			$id_exists=mysql_query("SELECT userid FROM flags WHERE id=".$id);
			if (mysql_query("SELECT userid FROM flags WHERE id=".$id)){
				mysql_query("DELETE FROM flags WHERE id=".$id);
			}
			die("<meta http-equiv='Refresh' content='0; URL=javascript:history.back(1)'>");
		}
		break;
	default:
		//Show the mods a different page than the admins with less stuff 'n' stuff
		if ($cur_user['rank'] == "mod") require_once("templates/mod_template.php");
		else if ($cur_user['rank'] == "admin") require_once("templates/admin_template.php");
}

//If submit pressed on preferences, redirect to that user's preferences
if (isset($_POST['prefssubmit'])){
	$prefsid = get_id_from_username(addslashes($_POST['prefsusername']));
	if (empty($prefsid)) die("Please enter a valid username.");
	die("<meta http-equiv='Refresh' content='0; URL=pref.php?id=$prefsid'>");
}

if (isset($_POST['msgsubmit'])){
	$msgid = get_id_from_username(addslashes($_POST['usernamemsg']));
	if (empty($msgid)) die("Please enter a valid username.");
	die("<meta http-equiv='Refresh' content='0; URL=messages.php?uid=$msgid'>");
}

if (isset($_POST['adminmessagesubmit'])){
	$recipientid = get_id_from_username(addslashes($_POST['recipientusername']));
	$admintype=($_POST['showuser']==1)?"generic":"specific";
	mysql_query("INSERT INTO messages (recipientid,senderid,message,type,admintype) VALUES (".$recipientid.",".$cur_user['id'].",'".addslashes($_POST['adminmessage'])."','admin','".$admintype."')");
	die("Your message has been sent.");
}
?>
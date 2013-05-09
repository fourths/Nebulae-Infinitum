<?php
//If user isn't logged in, 403 'em
if (empty($_SESSION['SESS_MEMBER_ID'])){
	require_once("errors/403.php");
	exit();
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
if ($mode!="flags") $flags=$mysqli->query("SELECT * FROM flags ORDER BY timestamp DESC LIMIT ".ADMIN_FLAGS);
else $flags=$mysqli->query("SELECT * FROM flags ORDER BY timestamp DESC LIMIT 500");
$flagsamt=$mysqli->query("SELECT * FROM flags ORDER BY timestamp DESC")->num_rows;
switch($mode){
	case "flags":
		if ($action!="delete") require_once("templates/adminflags_template.php");
		else {
			$id_exists=$mysqli->query("SELECT userid FROM flags WHERE id=".$id);
			if ($mysqli->query("SELECT userid FROM flags WHERE id=".$id)){
				$mysqli->query("DELETE FROM flags WHERE id=".$id);
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
	$prefs_user = addslashes($_POST['prefsusername']);
	if (empty($prefs_user)) die("Please enter a valid username.");
	die("<meta http-equiv='Refresh' content='0; URL=user/".$prefs_user."/preferences'>");
}

if (isset($_POST['msgsubmit'])){
	$msgid = get_id_from_username(addslashes($_POST['usernamemsg']),$mysqli);
	if (empty($msgid)) die("Please enter a valid username.");
	die("<meta http-equiv='Refresh' content='0; URL=messages/".$msgid."'>");
}

if (isset($_POST['adminmessagesubmit'])){
	$recipientid = get_id_from_username(addslashes($_POST['recipientusername']),$mysqli);
	$admintype=($_POST['showuser']==1)?"generic":"specific";
	mysql_query("INSERT INTO messages (recipientid,senderid,message,type,admintype) VALUES (".$recipientid.",".$cur_user['id'].",'".addslashes($_POST['adminmessage'])."','admin','".$admintype."')");
	die("Your message has been sent.");
}
?>
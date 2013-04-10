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

//Get post ID from URL
//If post ID not found or is NaN, die
if (isset($_GET['id'])) $blog_post_id = htmlspecialchars($_GET["id"]);
if (!$blog_post_id || strcspn($blog_post_id,"0123456789")>0){
	include_once("../errors/404.php");
	exit();
}

// Get the data of the selected blog post
$blog_post = mysql_fetch_array( mysql_query( "SELECT * FROM blog WHERE postid=".$blog_post_id ) );
unset($blog_post_id);
if ( $blog_post['userid'] != $cur_user['id'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod"){
	include_once("../errors/403.php");
	exit();
}


// Get the page action
if(isset($_GET['action'])){
	switch($_GET['action']){
		case "edit":
		case "new":
		case "delete":
			$action=$_GET['action'];
	}
}

if ($action=="delete"){
	mysql_query( "DELETE FROM blog WHERE postid=".$blog_post['postid'] );
	
	header("HTTP/1.1 303 See Other");
	header("Location: /blog/");
	die();
}



//Get current version number
$cur_version_arr = mysql_fetch_row(mysql_query("SELECT MAX(number) FROM versions WHERE creationid=".$creation['id']));
$cur_version = $cur_version_arr[0];
unset($cur_version_arr);
if (empty($cur_version)){
	$cur_version = 1;
}
$new_version = $cur_version+1;
$version_name_arr = mysql_fetch_row(mysql_query("SELECT name FROM versions WHERE creationid=".$creation['id']." AND number=".$cur_version));
$version_name = $version_name_arr[0];
unset($version_name_arr);
if (empty($cur_version)){
	$version_name = "1.0";
}

//If mode is versions, do that thing!!
if (isset($_GET['mode'])&&$_GET['mode']=="version"){
	$mode = "version";
}	

if($mode == "version"){
	$version_query = mysql_query("SELECT * FROM versions WHERE creationid=".$creation['id']." ORDER BY number DESC") or die(mysql_error());
	$versions = array();
	$j=0;
	while($version_i = mysql_fetch_array($version_query)){
		$versions[$j] = $version_i;
		$j++;
	}
	unset($version_query);
	$old_versions = scandir(BASE_DIRECTORY."/data/creations/old");
	$filenames=array();
	foreach($old_versions as $old_version){
		$hyphen_parts=explode('-', $old_version);
		if($hyphen_parts[0]==$creation['id']){
			$latter_parts = explode('.', $hyphen_parts[1]);
			$version = substr($latter_parts[0],1);
			$ext = $latter_parts[1];
			$filenames[$version]=$creation['id'].'-v'.$version.'.'.$ext;
		}
	}
	
	if (isset($_GET['action'])&&($_GET['action']=="delete"||$_GET['action']=="revert")){
		$action = $_GET['action'];
		$id = floatval($_GET['aid']);
		if($action=="revert"){
			mysql_query("INSERT INTO versions (creationid,name,number,saved) VALUES(".$creation['id'].",'".floatval($version_name+1).".0"."',".$new_version.",1)") or die(mysql_error());
			$ext = strtolower(substr(strrchr($filenames[$id], '.'), 1));
			copy("data/creations/".$creation['filename'],"data/creations/old/".$creation['id']."-v".$cur_version.".".$creation['filetype']);
			unlink("data/creations/".$creation['filename']);
			
			switch($ext){
				case "jpg":
				case "jpeg":
				case "jpe":
				case "png":
				case "apng":
				case "tif":
				case "tiff":
				case "bmp":
				case "dib":
				case "gif":
				case "svg":
					$type = "artwork";
				break;
				
				case "mp3":
					$type = "audio";
				break;
				
				case "txt":
					$type = "writing";
				break;
				
				case "sb":
				case "scratch":
				case "sb2":
					$type = "scratch";
				break;
				
				case "swf":
					$type = "flash";
				break;
			}
			
			mysql_query("UPDATE creations SET filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',type='".$type."' WHERE id=".$creation['id']) or die(mysql_error());
			copy("data/creations/old/".$filenames[$id],"data/creations/".$creation['id'].'.'.$ext);
			echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=".$creation['id']."'>";
		}
		else if($action=="delete"){
			mysql_query("UPDATE versions SET saved=0 WHERE number=".$id) or die(mysql_error());
			unlink("data/creations/old/".$filenames[$id]);
			echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=".$creation['id']."'>";
		}
	}
	include_once("templates/version_template.php");
}
else {
	include_once("templates/edit_template.php");
}

// Submit blog post
if (isset($_POST['update'])) {
	$result = mysql_query("SELECT * FROM creations WHERE id='$creationid'");
	if (mysql_num_rows($result) != 1){
		die("An error occurred, please try again.");
	}
	if($_POST['license']=="copyright"||$_POST['license']=="cc-0"||$_POST['license']=="cc-by"||$_POST['license']=="cc-by-sa"||$_POST['license']=="cc-by-nc"||$_POST['license']=="cc-by-nd"||$_POST['license']=="cc-by-nc-sa"||$_POST['license']=="cc-by-nc-nd"||$_POST['license']=="mit"||$_POST['license']=="gpl"||$_POST['license']=="bsd") mysql_query("UPDATE creations SET license='".$_POST['license']."' WHERE id='$creationid'") or die(mysql_error());
	else mysql_query("UPDATE creations SET license='copyright' WHERE id='$creationid'") or die(mysql_error());
	mysql_query("UPDATE creations SET name='".addslashes(htmlspecialchars($_POST['title']))."' WHERE id='$creationid'") or die(mysql_error());
	mysql_query("UPDATE versions SET name='".addslashes(htmlspecialchars($_POST['version']))."' WHERE creationid='$creationid' AND number=".$cur_version) or die(mysql_error());
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

// Delete blog post
if (isset($_POST['delete'])) {
	
}
?>
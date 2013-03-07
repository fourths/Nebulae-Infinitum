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

$mode = "edit";

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		die(mysql_error());
	}
	$cur_user = mysql_fetch_array($lresult);
}

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET['id'])) $creationid = htmlspecialchars($_GET["id"]);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = mysql_query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die(mysql_error());
}
$creation = mysql_fetch_array($result);

//If creation ID is not a valid creation, die
if (!$creation){
	include_once("errors/404.php");
	exit();
}

//If user doesn't own project & isn't admin or mod, die
if ($cur_user['id'] != $creation['ownerid'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod"){
	include_once("errors/403.php");
	exit();
}

//If creation is censored and user isn't admin or mod, die
if ($creation['hidden'] == "censored" && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/creation_censored.php");
	exit();
}
//If creation is deleted and user isn't admin or mod, die
if ($creation['hidden'] == "deleted" && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/404.php");
	exit();
}

//Check if user is banned or deleted
if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

//If mode is versions, do that thing!!
if (isset($_GET['mode'])||$_GET['mode']=="version"){
	$mode = "version";
}	

if($mode == "version"){
	$version_query = mysql_query("SELECT * FROM versions WHERE creationid=".$creation['id']);
	$versions = array();
	$j=0;
	while($version_i = mysql_fetch_array($version_query)){
		$versions[$j] = $version_i;
	}
	unset($version_query);
	include_once("templates/version_template.php");
}
else {
	include_once("templates/edit_template.php");
}

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
	$curhid = mysql_fetch_array(mysql_query("SELECT hidden FROM creations WHERE id='$creationid'"));
	if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod" && $hidden == "censored") $hidden = "byowner";
	if ($hidden=="flagged") $hidden = "byowner";
	if ($hidden=="no"&&($curhid[0]=="flagged"||$curhid[0]=="approved")) $hidden="approved";
	mysql_query("UPDATE creations SET hidden='".$hidden."' WHERE id='$creationid'") or die(mysql_error());
	if ($hidden=="censored") mysql_query("DELETE FROM flags WHERE creationid=".$creation['id']." AND type='creation'");
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}
//Creation deletion/undeletion
if (isset($_POST['delete'])) {
	if ($cur_user['id'] != $creation['ownerid'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") die("Insufficient permissions.");
	mysql_query("UPDATE creations SET hidden='deleted' WHERE id='$creationid'") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}

if (isset($_POST['undelete'])) {
	if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") die("Insufficient permissions.");
	mysql_query("UPDATE creations SET hidden='no' WHERE id='$creationid'") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}

//If upload button clicked, update the creation with the new version info
if (isset($_POST['upload'])) {
	if(isset($_POST['copy'])&&$_POST['copy']=="save"){
		$backup = true;
	}
	$gentxt=false;
	$ext = strtolower(substr(strrchr($_FILES['creationfile']['name'], '.'), 1));
	$timestamp = mysql_fetch_array(mysql_query("SELECT NOW()"));
	if (empty($_FILES['creationfile']) || !file_exists($_FILES['creationfile']['tmp_name'])) {
		die("Please select a file for your creation.");
	}
	if (empty($_FILES['thumbnail']) || !file_exists($_FILES['thumbnail']['tmp_name'])) {
		if ($ext != "png" && $ext != "jpg" && $ext != "tif"  && $ext != "bmp"  && $ext != "dib" && $ext != "tiff" && $ext != "jpeg" && $ext != "jpe" && $ext !="gif" && $ext != "txt"){
			if (substr(strrchr($_FILES['thumbnail']['name'], '.'), 1)!="png") die("Please select a thumbnail for your creation.");
		}
		else if ($ext=="txt") $gentxt=true;
	}
	if (empty($_POST['title'])) {
		die("Please enter a title for your creation.");
	}
	if ($ext == "gif" || $ext == "png" || $ext == "apng" || $ext == "tif" || $ext == "tiff" || $ext == "jpg" || $ext == "jpeg" || $ext == "jpe" || $ext == "bmp" || 
		$ext == "svg"){
		if ($ext == "jpg" || $ext == "jpeg" || $ext == "jpe"){
			if ($_FILES['creationfile']['type'] != "image/jpeg" && $_FILES['creationfile']['type'] != "image/pjpeg" /*for the silly IE users*/ ) die("Your JPEG file appears to be corrupted.");
			$jpeg = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($jpeg,2) != "ÿØ") die("Your JPEG file appears to be corrupted.");
		}
		else if ($ext == "png" || $ext == "apng"){
			if ($_FILES['creationfile']['type'] != "image/png" && $_FILES['creationfile']['type'] != "image/x-png" /*for the silly IE users*/ ) die("Your PNG file appears to be corrupted.");
			$png = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($png,4) != "‰PNG") die("Your PNG file appears to be corrupted.");
		}
		else if ($ext == "tif" || $ext == "tiff"){
			if ($_FILES['creationfile']['type'] != "image/tiff") die("Your TIFF file appears to be corrupted.");
			$tiff = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($tiff,3) != "II*" /* Intel-type */ && fread($tiff,3) != "MM*" /* Macintosh-type */) die("Your TIFF file appears to be corrupted.");
		}
		else if ($ext == "bmp" || $ext == "dib"){
			if ($_FILES['creationfile']['type'] != "image/bmp") die("Your BMP file appears to be corrupted.");
			$bmp = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($bmp,2) != "BM") die("Your BMP file appears to be corrupted.");
		}
		else if ($ext == "gif"){
			if ($_FILES['creationfile']['type'] != "image/gif") die("Your GIF file appears to be corrupted.");
			$gif = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($gif,3) != "GIF") die("Your GIF file appears to be corrupted.");
		}
		else if ($ext == "svg"){
			if ($_FILES['creationfile']['type'] != "image/svg+xml") die("Your SVG file appears to be corrupted.");
			$svg = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($svg,11) != "<svg xmlns=") die("Your SVG file appears to be corrupted.");
		}
		mysql_query("INSERT INTO creations (name,type,ownerid,created,filetype) VALUES ('".addslashes($_POST['title'])."','artwork',".$_SESSION['SESS_MEMBER_ID'].",'$timestamp[0]','$ext')") or die(mysql_error());
		$art=true;
	}
	else if ($ext == "mp3"){
		if ($_FILES['creationfile']['type'] != "audio/mp3" && $_FILES['creationfile']['type'] != "audio/mpeg") die("Your MP3 file appears to be corrupted. Its MIME type is ".$_FILES['creationfile']['type']);
		mysql_query("INSERT INTO creations (name,type,ownerid,created,filetype) VALUES ('".addslashes($_POST['title'])."','audio',".$_SESSION['SESS_MEMBER_ID'].",'$timestamp[0]','$ext')") or die(mysql_error());
	}
	else if ($ext == "sb" || $ext == "scratch" || $ext == "sb2"){
		if ($ext == "scratch" || $ext == "sb"){
			$scratchfile = fopen($_FILES['creationfile']['tmp_name'],"r");
			if (fread($scratchfile,9) != "ScratchV0") die("Your Scratch 1.x project appears to be corrupted.");
		}
		else if ($ext == "sb2"){
			$sb2zip = zip_open($_FILES['creationfile']['tmp_name']);
			if (is_string($sb2zip)) die("Your Scratch 2.x project appears to be corrupted.");
			if (str_replace("\n","",str_replace("\t", " ", zip_entry_read(zip_read($sb2zip),22))) != '{ "objName": "Stage",') die("Your Scratch 2.x project appears to be corrupted.");
		}
		mysql_query("INSERT INTO creations (name,type,ownerid,created,filetype) VALUES ('".addslashes($_POST['title'])."','scratch',".$_SESSION['SESS_MEMBER_ID'].",'$timestamp[0]','$ext')") or die(mysql_error());
	}
	else if ($ext == "swf"){
		if ($_FILES['creationfile']['type'] != "application/x-shockwave-flash") die("Your SWF file appears to be corrupted.");
		$swf = fopen($_FILES['creationfile']['tmp_name'],"r");
		if (fread($swf,3) != "CWS") die("Your SWF file appears to be corrupted.");
		mysql_query("INSERT INTO creations (name,type,ownerid,created,filetype) VALUES ('".addslashes($_POST['title'])."','flash',".$_SESSION['SESS_MEMBER_ID'].",'$timestamp[0]','$ext')") or die(mysql_error());
	}
	else if ($ext == "txt"){
		if (($_FILES['creationfile']['type'] != "text/plain" && $ext=="txt")) die("Your text file appears to be corrupted.");
		mysql_query("INSERT INTO creations (name,type,ownerid,created,filetype) VALUES ('".addslashes($_POST['title'])."','writing',".$_SESSION['SESS_MEMBER_ID'].",'$timestamp[0]','$ext')") or die(mysql_error());
	}
	else die("Unsupported file type.");
	$cid = mysql_fetch_array(mysql_query("SELECT id FROM creations WHERE ownerid=".$_SESSION['SESS_MEMBER_ID']." AND name='".addslashes($_POST['title'])."' AND created='$timestamp[0]'")) or die(mysql_error());
	
	if($_POST['license']=="copyright"||$_POST['license']=="cc-0"||$_POST['license']=="cc-by"||$_POST['license']=="cc-by-sa"||$_POST['license']=="cc-by-nc"||$_POST['license']=="cc-by-nd"||$_POST['license']=="cc-by-nc-sa"||$_POST['license']=="cc-by-nc-nd"||$_POST['license']=="mit"||$_POST['license']=="gpl"||$_POST['license']=="bsd") mysql_query("UPDATE creations SET license='".$_POST['license']."' WHERE id='$cid[0]'") or die(mysql_error());
	else mysql_query("UPDATE creations SET license='copyright' WHERE id='$cid[0]'") or die(mysql_error());
	
	move_uploaded_file($_FILES['creationfile']['tmp_name'], "data/creations/" .$cid[0].".".$ext);
	//thumbnail generation
	if ($ext == "gif"||$ext == "jpg"||$ext == "jpeg"||$ext == "jpe"||$ext=="png"||$ext=="dib"||$ext=="bmp"){
		$thumbimg = imagecreatefromstring(file_get_contents($_FILES['creationfile']['tmp_name']));
		$rzthumbimg = imagecreatetruecolor(133,100);
		imagecopyresampled($rzthumbimg, $thumbimg, 0, 0, 0, 0, 133, 100, imagesx($thumbimg), imagesy($thumbimg));
		imagepng($rzthumbimg,"data/thumbs/".$cid[0].".png",9);
	}
	else if ($ext == "txt" && $gentxt==true){
		$txtimg = imagecreatetruecolor(133,100);
		imagecolortransparent($txtimg, imagecolorexact($txtimg,0, 0, 0)); 
		$writdata=file_get_contents("data/creations/".$cid[0].".txt");
		$writarr=explode("\n",wordwrap($writdata,22));
		$y=0;
		for($y=0;$y<=10;$y++){
			$writ=$writarr[$y];
			imagestring($txtimg,2,0,$y*10,trim($writ),imagecolorallocate($txtimg,86,86,86));
		}
		imagepng($txtimg,"data/thumbs/".$cid[0].".png",9);
	}
	else{
		$thumbimg = imagecreatefromstring(file_get_contents($_FILES['thumbnail']['tmp_name']));
		$rzthumbimg = imagecreatetruecolor(133,100);
		imagecopyresampled($rzthumbimg, $thumbimg, 0, 0, 0, 0, 133, 100, imagesx($thumbimg), imagesy($thumbimg));
		imagepng($rzthumbimg,"data/thumbs/".$cid[0].".png",9);
	}
	
	mysql_query("UPDATE creations SET filename='".$cid[0].".".$ext."' WHERE id=$cid[0]") or die(mysql_error());
	if (!empty($_POST['description'])) mysql_query("UPDATE creations SET descr='".addslashes($_POST['description'])."' WHERE id=$cid[0]") or die(mysql_error());
	if (!empty($_POST['advisory'])) mysql_query("UPDATE creations SET advisory='".addslashes($_POST['advisory'])."' WHERE id=$cid[0]") or die(mysql_error());
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$cid[0]'>";
	exit();
}
?>
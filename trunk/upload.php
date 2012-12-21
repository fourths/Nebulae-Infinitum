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
	header("location: login.php");
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
if ($luserdata[6] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($luserdata[6] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

require_once("templates/upload_template.php");

//If form submitted, create new project
if (isset($_POST['submit'])) {
	$gentxt=false;
	$ext = strtolower(substr(strrchr($_FILES['creationfile']['name'], '.'), 1));
	$timestamp = mysql_fetch_row(mysql_query("SELECT NOW()"));
	if (empty($_FILES['creationfile']) || !file_exists($_FILES['creationfile']['tmp_name'])) {
		die("Please select a file for your creation.");
	}
	//echo (substr(strrchr($_FILES['thumbnail']['name'], '.'), 1));
	if (empty($_FILES['thumbnail']) || !file_exists($_FILES['thumbnail']['tmp_name'])) {
		if ($ext != "png" && $ext != "jpg" && $ext != "tif"  && $ext != "bmp"  && $ext != "dib" && $ext != "tiff" && $ext != "jpeg" && $ext != "jpe" && $ext !="gif" && $ext != "txt"){
			if (substr(strrchr($_FILES['thumbnail']['name'], '.'), 1)!="png") die("Please select a PNG thumbnail for your creation.");
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
		if ($_FILES['creationfile']['type'] != "audio/mp3") die("Your MP3 file appears to be corrupted.");
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
	$cid = mysql_fetch_row(mysql_query("SELECT id FROM creations WHERE ownerid=".$_SESSION['SESS_MEMBER_ID']." AND name='".addslashes($_POST['title'])."' AND created='$timestamp[0]'")) or die(mysql_error());
	
	if($_POST['license']=="copyright"||$_POST['license']=="cc-0"||$_POST['license']=="cc-by"||$_POST['license']=="cc-by-sa"||$_POST['license']=="cc-by-nc"||$_POST['license']=="cc-by-nd"||$_POST['license']=="cc-by-nc-sa"||$_POST['license']=="cc-by-nc-nd"||$_POST['license']=="mit"||$_POST['license']=="gpl"||$_POST['license']=="bsd") mysql_query("UPDATE creations SET license='".$_POST['license']."' WHERE id='$cid[0]'") or die(mysql_error());
	else mysql_query("UPDATE creations SET license='copyright' WHERE id='$cid[0]'") or die(mysql_error());
	
	move_uploaded_file($_FILES['creationfile']['tmp_name'], "data/projects/" .$cid[0].".".$ext);
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
		$writdata=file_get_contents("data/projects/".$cid[0].".txt");
		$writarr=explode("\n",wordwrap($writdata,22));
		$y=0;
		for($y=0;$y<=10;$y++){
			$writ=$writarr[$y];
			imagestring($txtimg,2,0,$y*10,trim($writ),imagecolorallocate($txtimg,86,86,86));
		}
		imagepng($txtimg,"data/thumbs/".$cid[0].".png",9);
	}
	else{
		$thumbimg = imagecreatefrompng($_FILES['thumbnail']['tmp_name']);
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
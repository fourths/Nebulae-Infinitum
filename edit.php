<?php
$mode = "edit";

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET['id'])) $creationid = htmlspecialchars($_GET["id"]);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = $mysqli->query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die( $mysqli->error );
}
$creation = $result->fetch_array();

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
//Get current version number
$cur_version_arr = $mysqli->query("SELECT MAX(number) FROM versions WHERE creationid=".$creation['id'])->fetch_array();
$cur_version = $cur_version_arr[0];
unset($cur_version_arr);
if (empty($cur_version)){
	$cur_version = 1;
}
$new_version = $cur_version+1;
$version_name_arr = $mysqli->query("SELECT name FROM versions WHERE creationid=".$creation['id']." AND number=".$cur_version)->fetch_array();
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
	$version_query = $mysqli->query("SELECT * FROM versions WHERE creationid=".$creation['id']." ORDER BY number DESC") or die( $mysqli->error );
	$versions = array();
	$j=0;
	while ( $version_i = $version_query->fetch_array() ){
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
			$mysqli->query("INSERT INTO versions (creationid,name,number,saved) VALUES(".$creation['id'].",'".floatval($version_name+1).".0"."',".$new_version.",1)") or die( $mysqli->error );
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
			
			$mysqli->query("UPDATE creations SET filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',type='".$type."' WHERE id=".$creation['id']) or die( $mysqli->error );
			copy("data/creations/old/".$filenames[$id],"data/creations/".$creation['id'].'.'.$ext);
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creation/".$creation['id']."'>" );
		}
		else if($action=="delete"){
			$mysqli->query("UPDATE versions SET saved=0 WHERE number=".$id) or die( $mysqli->error );
			unlink("data/creations/old/".$filenames[$id]);
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creation/".$creation['id']."'>" );
		}
	}
	include_once("templates/version_template.php");
}
else {
	include_once("templates/edit_template.php");
}

//Update database values upon form submission
if (isset($_POST['update'])) {
	$result = $mysqli->query("SELECT * FROM creations WHERE id='$creationid'");
	if ( $result->num_rows != 1 ){
		die("An error occurred; please try again.");
	}
	if($_POST['license']=="copyright"||$_POST['license']=="cc-0"||$_POST['license']=="cc-by"||$_POST['license']=="cc-by-sa"||$_POST['license']=="cc-by-nc"||$_POST['license']=="cc-by-nd"||$_POST['license']=="cc-by-nc-sa"||$_POST['license']=="cc-by-nc-nd"||$_POST['license']=="mit"||$_POST['license']=="gpl"||$_POST['license']=="bsd") $mysqli->query("UPDATE creations SET license='".$_POST['license']."' WHERE id='$creationid'") or die( $mysqli->error );
	else $mysqli->query("UPDATE creations SET license='copyright' WHERE id='$creationid'") or die( $mysqli->error );
	$mysqli->query("UPDATE creations SET name='".addslashes(htmlspecialchars($_POST['title']))."' WHERE id='$creationid'") or die( $mysqli->error );
	$mysqli->query("UPDATE versions SET name='".addslashes(htmlspecialchars($_POST['version']))."' WHERE creationid='$creationid' AND number=".$cur_version) or die( $mysqli->error );
	$mysqli->query("UPDATE creations SET descr='".addslashes(htmlspecialchars($_POST['description']))."' WHERE id='$creationid'") or die( $mysqli->error );
	$mysqli->query("UPDATE creations SET advisory='".addslashes(htmlspecialchars($_POST['advisory']))."' WHERE id='$creationid'") or die( $mysqli->error );
	if (addslashes($_POST['hidden']) != "no" && addslashes(htmlspecialchars($_POST['hidden'])) != "byowner" && addslashes($_POST['hidden']) != "censored" && addslashes($_POST['hidden']) != "deleted") $hidden = "no";
	else $hidden = addslashes($_POST['hidden']);
	$curhid = $mysqli->query("SELECT hidden FROM creations WHERE id='$creationid'")->fetch_array();
	if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod" && $hidden == "censored") $hidden = "byowner";
	if ($hidden=="flagged") $hidden = "byowner";
	if ($hidden=="no"&&($curhid[0]=="flagged"||$curhid[0]=="approved")) $hidden="approved";
	$mysqli->query("UPDATE creations SET hidden='".$hidden."' WHERE id='$creationid'") or die( $mysqli->error );
	if ($hidden=="censored") $mysqli->query("DELETE FROM flags WHERE creationid=".$creation['id']." AND type='creation'");
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}
//Creation deletion/undeletion
if (isset($_POST['delete'])) {
	if ($cur_user['id'] != $creation['ownerid'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") die("Insufficient permissions.");
	$mysqli->query("UPDATE creations SET hidden='deleted' WHERE id='$creationid'") or die( $mysqli->error );
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}

if (isset($_POST['undelete'])) {
	if ($cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") die("Insufficient permissions.");
	$mysqli->query("UPDATE creations SET hidden='no' WHERE id='$creationid'") or die( $mysqli->error );
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
}

//If upload button clicked, update the creation with the new version info
if (isset($_POST['upload'])) {
	$version_number = "2";
	if(isset($_POST['copy'])&&$_POST['copy']=="save"){
		$backup = true;
	}
	$ext = strtolower(substr(strrchr($_FILES['creationfile']['name'], '.'), 1));
	$timestamp = $mysqli->query("SELECT NOW()")->fetch_array();
	if (empty($_FILES['creationfile']) || !file_exists($_FILES['creationfile']['tmp_name'])) {
		die("Please select a file for your creation.");
	}
	if ($ext == "gif" || $ext == "png" || $ext == "apng" || $ext == "tif" || $ext == "tiff" || $ext == "jpg" || $ext == "jpeg" || $ext == "jpe" || $ext == "bmp" || 
		$ext == "svg"){
		//do different things for each image type!
		switch($ext){
			case "jpg":
			case "jpeg":
			case "jpe":
				if ($_FILES['creationfile']['type'] != "image/jpeg" && $_FILES['creationfile']['type'] != "image/pjpeg" /*for the silly IE users*/ ) die("Your JPEG file appears to be corrupted.");
				$jpeg = fopen($_FILES['creationfile']['tmp_name'],"r");
				if (fread($jpeg,2) != "ÿØ") die("Your JPEG file appears to be corrupted.");
			break;

			case "png":
			case "apng":
				if ($_FILES['creationfile']['type'] != "image/png" && $_FILES['creationfile']['type'] != "image/x-png" /*for the silly IE users*/ ) die("Your PNG file appears to be corrupted.");
				$png = fopen($_FILES['creationfile']['tmp_name'],"r");
				if (fread($png,4) != "‰PNG") die("Your PNG file appears to be corrupted.");
			break;
			
			case "tif":
			case "tiff":
				if ($_FILES['creationfile']['type'] != "image/tiff") die("Your TIFF file appears to be corrupted.");
				$tiff = fopen($_FILES['creationfile']['tmp_name'],"r");
				if (fread($tiff,3) != "II*" /* Intel-type */ && fread($tiff,3) != "MM*" /* Macintosh-type */) die("Your TIFF file appears to be corrupted.");
			break;
			
			case "bmp":
			case "dib":
				if ($_FILES['creationfile']['type'] != "image/bmp") die("Your BMP file appears to be corrupted.");
				$bmp = fopen($_FILES['creationfile']['tmp_name'],"r");
				if (fread($bmp,2) != "BM") die("Your BMP file appears to be corrupted.");
			break;
			
			case "gif":
				if ($_FILES['creationfile']['type'] != "image/gif") die("Your GIF file appears to be corrupted.");
				$gif = fopen($_FILES['creationfile']['tmp_name'],"r");
				if (fread($gif,3) != "GIF") die("Your GIF file appears to be corrupted.");
			break;
			
			case "svg":
				if ($_FILES['creationfile']['type'] != "image/svg+xml") die("Your SVG file appears to be corrupted.");
				$svg = fopen($_FILES['creationfile']['tmp_name'],"r");
				if (fread($svg,11) != "<svg xmlns=") die("Your SVG file appears to be corrupted.");
			break;
		}
		$mysqli->query("UPDATE creations SET type='artwork',filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',modified='".$timestamp."' WHERE id=".$creation['id']."") or die( $mysqli->error );
	}
	else if ($ext == "mp3"){
		if ($_FILES['creationfile']['type'] != "audio/mp3" && $_FILES['creationfile']['type'] != "audio/mpeg") die("Your MP3 file appears to be corrupted. Its MIME type is ".$_FILES['creationfile']['type']);
		$mysqli->query("UPDATE creations SET type='audio',filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',modified='".$timestamp."' WHERE id=".$creation['id']."") or die( $mysqli->error );
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
		$mysqli->query("UPDATE creations SET type='scratch',filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',modified='".$timestamp."' WHERE id=".$creation['id']."")  or die( $mysqli->error );
	}
	else if ($ext == "swf"){
		if ($_FILES['creationfile']['type'] != "application/x-shockwave-flash") die("Your SWF file appears to be corrupted.");
		$swf = fopen($_FILES['creationfile']['tmp_name'],"r");
		if (fread($swf,3) != "CWS") die("Your SWF file appears to be corrupted.");
		$mysqli->query("UPDATE creations SET type='flash',filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',modified='".$timestamp."' WHERE id=".$creation['id']."") or die( $mysqli->error );
	}
	else if ($ext == "txt"){
		if (($_FILES['creationfile']['type'] != "text/plain" && $ext=="txt")) die("Your text file appears to be corrupted.");
		$mysqli->query("UPDATE creations SET type='writing',filetype='".$ext."',filename='".$creation['id'].'.'.$ext."',modified='".$timestamp."' WHERE id=".$creation['id']."") or die( $mysqli->error );
	}
	else die("Unsupported file type.");
	
	$mysqli->query("INSERT INTO versions (creationid,name,number,saved) VALUES(".$creation['id'].",'".addslashes(htmlspecialchars($_POST['newversion']))."',".$new_version.",1)") or die( $mysqli->error );
	//if user said to save file, back it up
	if($backup==true){
		copy("data/creations/".$creation['filename'],"data/creations/old/".$creation['id']."-v".$cur_version.".".$creation['filetype']);
		unlink("data/creations/".$creation['filename']);
		move_uploaded_file($_FILES['creationfile']['tmp_name'], "data/creations/" .$creation['id'].".".$ext);
		//since saved will be 1 when uploaded, it doesn't need to be updated
	}
	else{
		unlink("data/creations/".$creation['filename']);
		move_uploaded_file($_FILES['creationfile']['tmp_name'], "data/creations/" .$creation['id'].".".$ext);
		$mysqli->query("UPDATE versions SET saved=0 WHERE number=".$cur_version) or die( $mysqli->error );
	}
	echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=".$creation['id']."'>";
	exit();
}
?>
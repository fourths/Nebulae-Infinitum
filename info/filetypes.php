<?php
//Include config
require_once("../config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database
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
	if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
	}
	else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
	}
}
?>
<html>
<head>
<title>Allowed filetypes | <?=SITE_NAME?></title>
<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
</head>
<body>
<? include_once("../templates/header.php");  ?>

<div class="container" style="padding-bottom:20px;">
<h1 style="display:inline;margin-left:0px;">Allowed filetypes</h1>
<div style="padding-top:10px;font-size:12px;">
<?=SITE_NAME?> allows its users to upload a variety of different media types.<br/>
Currently, we support the following filetypes:
<ul>
<li>PNG images</li>
<li>GIF images and animations</li>
<li>JPEG images</li>
<li>TIFF images</li>
<li>BMP images</li>
<li>SVG vector images</li>
<li>MP3 audio</li>
<li>Raw text files, extension TXT</li>
<li>SWF Flash files</li>
<li>Scratch 1.0-1.4 files, extension SB</li>
<li>Scratch 2.0 files, extension SB2</li>
</ul>
We are also planning support for the following types in the near future:
<ul>
<?//<li>Processing sketches, ran with Processing.js (extension PDE or PJS)</li>-->?>
<li>Rich text files, extension RTF</li>
<li>An on-site text editor with possible BBCode support</li>
</ul>
<a href="/upload.php">Back to upload</a>
</div>
</div>
</body>
</html>
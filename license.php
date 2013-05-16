<?php
//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET['id'])) $creationid = htmlspecialchars($_GET['id']);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = $mysqli->query( "SELECT * FROM creations WHERE id = " . $creationid );
if (!$result) {
    die( $mysqli->error );
}
$creation = $result->fetch_array();

//If creation ID is not a valid creation, die
if (!$creation){
	include_once("errors/404.php");
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

if($creation['license']!="mit"&&$creation['license']!="gpl"&&$creation['license']!="bsd"){
	die("<meta http-equiv='Refresh' content='0; URL=.'>");
}

include_once("templates/license_template.php");
?>
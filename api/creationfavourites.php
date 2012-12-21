<?php
//Include config
require_once("../config/config.php");
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET["id"])) $creationid = htmlspecialchars($_GET["id"]);
else die("");
if (!$creationid || strcspn($creationid,"0123456789")>0){
	die("");
}

//Get creation info from database
$result = mysql_query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die("");
}
$creationdata = mysql_fetch_row($result);

//If creation ID is not a valid creation, die
if (!$creationdata){
	die("");
}

$favourites = mysql_num_rows(mysql_query("SELECT * FROM favourites WHERE creationid=$creationdata[0]"));

echo $favourites;

?>
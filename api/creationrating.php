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
$creation = mysql_fetch_array($result);

//If creation ID is not a valid creation, die
if (!$creation){
	die("");
}

//Get ratings
$result = mysql_query("SELECT rating FROM ratings WHERE creationid=$creation['id']");
$i=0;
while($row = mysql_fetch_array($result)){
	$ratings[$i] = $row[0];
	$i++;
}
if (empty($ratings[0])) $ratings[0] = 0;

if (number_format(array_sum($ratings)/count($ratings),1)==0.0) echo "unrated";
else echo number_format(array_sum($ratings)/count($ratings),1);

?>
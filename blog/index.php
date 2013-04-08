<?php
// For displaying blog posts in a paginated fashion

//Include config
require_once("../config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database specified in config
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
}
if ($cur_user['banstatus'] == "banned") {
	include_once("../errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("../errors/delete.php");
	exit();
}

// Get user ID (if ID is invalid, zero, or not present, display site news
if (isset($_GET["uid"])){
	$userid = htmlspecialchars($_GET["uid"]);
	if (!$userid || strcspn($userid,"0123456789")>0){
		$type="admin";
	}
	else{
		$type="user";
	}
}
else{
	$type="admin";
}

// If valid user, get data of user
// In the case that it's the admin blog, we don't need to do this for each post because all it needs is the username (which can be extraced from the ID with the get_username_from_id() function)
if($type=="user"){
	$result = mysql_query("SELECT * FROM users WHERE id = $userid");
	if (!$result) {
		die(mysql_error());
	}
	$user = mysql_fetch_array($result);
	if ($user['banstatus'] == "deleted") {
		include_once("templates/user_deleted.php");
		exit();
	}
}

// Get page number
if (isset($_GET["page"])){
	$page = htmlspecialchars($_GET["page"]);
}
if (!$page || strcspn($page,"0123456789")>0 || floatval($page) == 0){
	$page = 1;
}

$post_counts=array();
// Get amounts of posts posted each month the blog has been active
if($type=="user"){
	$post_count_query=mysql_query("SELECT timestamp FROM blog WHERE userid=".$user['id']." LIMIT 0,1");
}
else{
	$post_count_query=mysql_query("SELECT timestamp FROM blog WHERE admin=1 LIMIT 0,1");
}
$i=0;
while($post_count = mysql_fetch_row($post_count_query)){
	$post_counts[$i] = [ strtotime($post_count[0]), date("Y",strtotime($post_count[0])), date("n",strtotime($post_count[0])) ];
	$i++;
}
sort($post_counts);

// Cycle through each year there's posts
$calendar=array();
for($k=0;$k<count($post_counts);$k++){
	if(!in_array($post_counts[$k][1],$calendar)){
		array_push($calendar,$post_counts[$k][1]);
	}
}
for($i=0;$i<=date("Y")-$post_counts[0][1];$i++){
	// WHAT: Why isn't the below line working?
	if(in_array(date("Y")-$i,$calendar)){
		print_r($post_counts[$i]);
		// Cycle through each month of the year
		for($j=1;$j<=12;$j++){
			
			if($post_counts[$i][2]==$j){
				$calendar[date("Y")-$i][$j]++;
			}
		}
	}
}
foreach($calendar as &$year){
	if(is_string($year)){
		$year = null;
	}
}
print_r($calendar);


// Get data for blog posts on the specified page
$posts=array();
if($type=="user"){
	$posts_query=mysql_query("SELECT * FROM blog WHERE userid=".$user['id']." ORDER BY postid DESC LIMIT ".(($page-1)*10).",10") or die(mysql_error());
}
else{
	$posts_query=mysql_query("SELECT * FROM blog WHERE admin=1 ORDER BY postid DESC LIMIT ".(($page-1)*10).",10") or die(mysql_error());
}
$i=0;
while($posts_arr=mysql_fetch_array($posts_query)){
	$posts[$i]=$posts_arr;
	$i++;
}
unset($posts_query);

require_once("../templates/blog_template.php");

?>
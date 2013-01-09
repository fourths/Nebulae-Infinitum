<?
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE);

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: ".mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

session_start();

//Initialise login/register page variable
$lr = "login";

if(isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) != '')) {
	if (isset($_GET["action"]) && $_GET["action"] == "logout") {
		session_destroy();
		header("location: .");
		exit();
	}
	header("location: user.php?id=".$_SESSION['SESS_MEMBER_ID']);
	exit();
}


if (isset($_GET["action"]) && $_GET["action"] == "register") {
	$lr = "register";
}


//Display login/register page
if ($lr == "login") require_once("templates/login_template.php");
if ($lr == "register") require_once("templates/register_template.php");

//Get information from login form when submitted
if (isset($_POST['submit'])) {
	$result = mysql_query("SELECT * FROM users WHERE username='".$_POST['user']."' AND password='".nebulae_hash($_POST['pass'])."'") or die(mysql_error());
	if (mysql_num_rows($result) != 1){
		die("Invalid username or password.");
	}
	session_regenerate_id();
    $user_info=mysql_fetch_assoc($result);
    $_SESSION['SESS_MEMBER_ID'] = $user_info['id'];
    session_write_close();
    header("location: user.php?id=".$_SESSION['SESS_MEMBER_ID']);
    exit();
}

//Get information from register form when submitted
if (isset($_POST['rsubmit'])) {
	if (empty($_POST['pass']) || empty($_POST['cpass'])){
		die("Please enter a password.");
	}
	if (strlen($_POST['pass'])<6){
		die("Please enter a password at least six characters long.");
	}
	if (empty($_POST['user'])){
		die("Please enter a username.");
	}
	if (strlen($_POST['user'])<4){
		die("Please enter a username at least four characters long.");
	}
	##abcdefghijklmnopqrstuvwxyz0123456789-_
	if (strcspn($_POST['user'],"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_")>0){
		die("Only alphanumeric characters, dashes, and underscores are allowed in usernames.");
	}	
	if (empty($_POST['email'])){
		die("Please enter an email.");
	}
	if (nebulae_hash($_POST['pass']) != nebulae_hash($_POST['cpass'])){
		die("The passwords do not match.");
	}
	if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='$_POST[user]'"))>0) die("That username is already in use.");
	$max = mysql_fetch_array(mysql_query("SELECT MAX(id) FROM users")) or die(mysql_error());
	$userip = $_SERVER['REMOTE_ADDR'];
	mysql_query("INSERT INTO users (id,username,password,email,registerip) VALUES ($max[0]+1,'".addslashes($_POST[user])."','".nebulae_hash($_POST[pass])."','$_POST[email]','$userip')");
	//Inserting optional values
	if(!empty($_POST['age'])) mysql_query("UPDATE users SET age='".addslashes($_POST['age'])."' WHERE id=$max[0]+1") or die(mysql_error());
	if(!empty($_POST['gender'])) mysql_query("UPDATE users SET gender='".addslashes($_POST['gender'])."' WHERE id=$max[0]+1") or die(mysql_error());
	if(!empty($_POST['location'])) mysql_query("UPDATE users SET location='".addslashes($_POST['location'])."' WHERE id=$max[0]+1") or die(mysql_error());
	
	$result = mysql_query("SELECT * FROM users WHERE username='$_POST[user]'") or die(mysql_error());
	if (mysql_num_rows($result) != 1){
		die("<br/>An error occured. Please try again.");
	}
	session_regenerate_id();
    $user_info=mysql_fetch_assoc($result);
    $_SESSION['SESS_MEMBER_ID'] = $user_info['id'];
    session_write_close();
    header("location: user.php?id=".$_SESSION['SESS_MEMBER_ID']);
    exit();
}
?>
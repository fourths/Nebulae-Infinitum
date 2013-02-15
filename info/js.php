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

//Change player if specified
if (isset($_GET["player"])) {
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("location: js.php");
		exit();
	}
	if ($_GET["player"]!="js" && $_GET["player"]!="flash"){
		header("location: js.php");
		exit();
	}
	mysql_query("UPDATE users SET sb2player='".$_GET["player"]."' WHERE id='".$cur_user['id']."'") or die(mysql_error());
	header("location: js.php");
	exit();
}

?>
<html>
	<head>
		<title>
			sb2.js | <?php echo SITE_NAME; ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
	</head>
	<body>
		<?php include_once("../templates/header.php");  ?>

		<div class="container" style="padding-bottom:20px;">
			<img src="../data/info/sb2js.png" style="background-color:white;padding:10px;border-radius:15px;margin:auto;display:block;margin-top:15px;width:350px;"/>
			<div style="padding-top:20px;font-size:16px;padding-left:20px;padding-right:20px;">
				sb2.js is a brand new player for Scratch projects, created by RHY3756547. It compiles projects made with the Scratch 2.0 editor to Javascript, and then runs them. In general, it is much faster than the Flash player currently in use on Scratch, but it's still in development and may have bugs and features yet to be added. Despite this, we're allowing users to choose whether they'd like to use it to view Scratch 2.0 projects.
				<?php
				if (!empty($_SESSION['SESS_MEMBER_ID'])) {echo '<br/><br/><div>You are currently using '; if ($cur_user['sb2player'] == "js") echo 'sb2.js. If you would like to switch back to the Flash player, click <a href="js.php?player=flash">here</a>.</div><br/>'; else echo 'the Flash player. If you would like to switch to sb2.js, click <a href="js.php?player=js">here</a>.</div><br/>';
				}
				?>

				<div>
					<strong>Why doesn't the player appear?</strong>
				</div>
				<div>
					This is generally caused by having Javascript disabled or using a browser that doesn't support HTML5 canvas. This can be fixed by either enabling Javascript in the first case, or updating your browser to the latest version or getting a new browser in the second. (We recommend Google Chrome or Mozilla Firefox.)
				</div>
				<br/>
				<div>
					<strong>Why can't I use it for Scratch 1.x projects?</strong>
				</div>
				<div>
					sb2.js doesn't support sb files yet, although it is planned for the future once Scratch 2.0 projects are completely finished.
				</div>
				<br/>
				<div>
					<strong>Why doesn't this feature work like in the Flash player?</strong>
				</div>
				<div>
					sb2.js is still in active development, and there are several things that need to be done still. It is recommended that you report bugs with the player <a href="http://code.google.com/p/sb2-js/issues/list">here</a> so that RHY is aware of them. This will help with the development of the player!
				</div>
				<br/>
				<div>
					<strong>There's a feature that works on RHY's Dropbox site but not here!</strong>
				</div>
				<div>
					This means that RHY has probably updated the player and we just haven't gotten around to updating it on this site yet. <a href="mailto:<?php echo ADMIN_EMAIL; ?>">Email us</a> or send a message to one of the admins.
				</div>
				<br/>
				<div>
					<strong>Can I force users to use sb2.js on one of my projects?</strong>
				</div>
				<div>
					No, and we currently don't have any plans to add that. Many people still use browsers without canvas support and we also don't want to force people to use a developmental player instead of a relatively stable official Scratch player. You can encourage users to use sb2.js in the description of your creation, though!
				</div>
			</div>
		</div>
	</body>
</html>
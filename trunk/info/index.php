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
		<title>
			Information &amp; about | <?php echo SITE_NAME; ?>
		</title>
		<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
	</head>
	
	<body>
		<?php
		include_once("../templates/header.php");
		?>

		<div class="container" style="padding-bottom:20px;">
			<h1>Information &amp; about</h1>
			<p>
				This page collects all the various different information pages on this site, in alphabetical order, for leisurely perusal (as well as if you want to know how to do something). More information can often be found in the <a href="../forums/">forums</a>. Items marked with an asterisk are coming soon.
			</p>
			
			<div>
				<ul>
					<li>
						<a href="about.php">About us</a>
					</li>
					<li>
						<a href="admin.php">Administration</a>*
					</li>
					<li>
						<a href="bbcode.php">BBCode</a>*
					</li>
					<li>
						<a href="comments.php">Comments</a>*
					</li>
					<li>
						<a href="copyright.php">Copyright</a>*
					</li>
					<li>
						<a href="creations.php">Creations</a>*
					</li>
					<li>
						<a href="editing.php">Creation editing</a>*
					</li>
					<li>
						<a href="faq.php">Frequently asked questions</a>*
					</li>
					<li>
						<a href="favourites.php">Favourites and ratings</a>*
					</li>
					<li>
						<a href="filetypes.php">Filetypes</a>
					</li>
					<li>
						<a href="flagging.php">Flagging</a>*
					</li>
					<li>
						<a href="forums.php">Forums guide</a>*
					</li>
					<li>
						<a href="licenses.php">Licenses</a>*
					</li>
					<li>
						<a href="messages.php">Messages &amp; notifications</a>*
					</li>
					<li>
						<a href="js.php">sb2.js</a>
					</li>
					<li>
						<a href="code.php">Technical&mdash;code repository</a>*
					</li>
					<li>
						<a href="changes.php">Technical&mdash;site changes</a>*
					</li>
					<li>
						<a href="setup.php">Technical&mdash;using &amp; modifying the site's software</a>*
					</li>
					<li>
						<a href="tou.php">Terms of use</a>*
					</li>
					<li>
						<a href="userprefs.php">User preferences</a>*
					</li>
				</ul>
			</div>
		</div>
	</body>
</html>
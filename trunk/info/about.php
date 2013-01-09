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
<title>About us | <?=SITE_NAME?></title>
<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
</head>
<body>
<? include_once("../templates/header.php");  ?>

<div class="container" style="padding-bottom:20px;">
<img src="../data/info/staff.png" alt="TheGalaxyBox and friends" style="display:block;width:800px;margin:auto;"/>

<h1 style="text-align:center;margin:0px;">Who are you and why'd you make this site?</h1>
<p>We are a congregation of a bunch of teenagers who talk and make creative things together. Our origins are centered around the community of <a href="http://scratch.mit.edu/">Scratch</a>, an educational block-based programming language. We each found our way to the site individually, sometime in between 2008 and 2011. Over time, mostly on the forums, we've formed friendships, worked on projects together, etc.</p>
<p>In fall 2011, Alternatives created <a href="http://xat.com/TheGalaxyBox">TheGalaxyBox</a>, a chatroom on xat in which he could talk to various people on Scratch who he invited. TheDancingDonut, bananaman114, soupoftomato, and various others were amongst those included. While the original two, Alt and TDD, gradually stopped using it, others who were invited started to invite guests of their own&mdash;Wickmen, 777w, veggieman001 and his brother puppetadventurer, rufflebee, and ProgrammingFreak. Eventually, around the beginning of 2012, the core group of TheGalaxyBox was mostly Wicki, soup, 777w, and veggie, with ruffle on occasion.</p>
<p>At some point, we decided that perhaps we'd like to find another online community besides Scratch to bother. However, after some searching, we found none suitable. This was when the idea to create our own was born, devised in June 2012 mainly by 777w and soup. Dubbed Nebulae Infinitum ("infinite cloud" in Latin), they enlisted veggie's help and his programming knowledge to create the site. This was when the creation of the site began.</p>
<p>However, we no longer use TheGalaxyBox for communication. Since a technical difficulty that arose with one of our core members, we switched to Gmail and Google Docs in August. luiysia joined not long after, and we began working on another project, <a href="http://nebbins.netai.net/">Nebbins Magazine</a>.</p>

<h1 style="text-align:center;margin:0px;">Who did what?</h1>
<p>veggie programmed about 95% of the HTML, CSS, PHP, and Javascript. The other 5% comes from libraries and such. This site uses <a href="http://nbbc.sourceforge.net/">NBBC</a> to parse BBCode, <a href="http://code.google.com/p/sb2-js/">sb2.js</a> for the Javascript Scratch player (thanks RHY!), and the <a href="http://wpaudioplayer.com/">WordPress Audio Player</a> for audio creations. It is expected PF will also contribute code in the future.</p>
<p>Layout and design is mostly by veggie, but based on mockups from soup, Wicki, and 777w, who have also done various artworks such as the error images and the logo.</p>
<p>Prototype/alpha testing was done by Wicki, soup, 777w, lui, puppet, ruffle, PF, banana, Maxy, and various other people. Beta testing is done by a <a href="testers.php">whole slew of people</a>.</p>

<h1 style="text-align:center;margin:0px;">How can I contact you?</h1>
<p>For general inquiries that you don't want to PM us about (or if you want it to be discussed by multiple members of the staff), feel free to email us at <a href="mailto:<? echo ADMIN_EMAIL ?>"><? echo ADMIN_EMAIL ?></a>. Otherwise, shoot a PM (or a comment, if it's not really private) to a staff member; we're always happy to help you. If it's a question you think other people would have too, it might be a good idea to post it on the <a href="../forums/">forums</a> so that other people can see the answer too.</p>
</div>
</body>
</html>
<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Initialise variable
$creationid = null;

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		echo "Could not run query: " . mysql_error() and die;
	}
	$cur_user = mysql_fetch_array($lresult);
}

//Get creation ID from URL
//If creation ID not found or is NaN, die
if (isset($_GET["id"])) $creationid = htmlspecialchars($_GET["id"]);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = mysql_query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die(mysql_error());
}
$creation = mysql_fetch_array($result);

//If creation ID is not a valid creation, die
if (!$creation){
	include_once("errors/404.php");
	exit();
}

//Test if the creation has enough flags to be auto-censored and censor it if it does
//If creation is marked as alright even after three flags, the creation still shows
$i=0;
$fresult = mysql_query("SELECT * FROM flags WHERE parentid = $creationid") or die(mysql_error());
while($row = mysql_fetch_array($fresult)){
	$flags[$i] = $row[2];
	$i++;
}
$farray=mysql_fetch_array(mysql_query("SELECT hidden FROM creations WHERE id = ".$creation['id']));
if (!empty($flags)){
	if (count(array_unique($flags))>=FLAGS_REQUIRED&&$farray[0]=="no") {
		mysql_query("UPDATE creations SET hidden='flagged' WHERE id='".$creation['id']."'") or die(mysql_error());
		mysql_query("DELETE FROM flags WHERE parentid=".$creation['id']." AND type='creation'");
	}
}


if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}
if ($creation['hidden'] == "byowner" && $cur_user['id'] != $user['id'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/creation_hidden.php");
	exit();
}
if (($creation['hidden'] == "censored" || $creation['hidden'] == "flagged")&& $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/creation_censored.php");
	exit();
}
//If creation is deleted and user isn't admin or mod, die
if ($creation['hidden'] == "deleted" && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod") {
	include_once("errors/404.php");
	exit();
}
if(empty($_SESSION['SESS_MEMBER_ID'])&&($creation['hidden']=="flagged"||$creation['hidden']=="censored")){
	include_once("errors/creation_censored.php");
	exit();
}
if(empty($_SESSION['SESS_MEMBER_ID'])&&$creation['hidden']=="byowner"){
	include_once("errors/creation_hidden.php");
	exit();
}


if (!empty($_SESSION['SESS_MEMBER_ID'])){
	if (mysql_num_rows(mysql_query("SELECT * FROM views WHERE viewip='".$_SERVER['REMOTE_ADDR']."' AND creationid=".$creation['id']))==0){
		mysql_query("INSERT INTO views (creationid, viewip) VALUES (".$creation['id'].", '".$_SERVER['REMOTE_ADDR']."')");
	}

	if (mysql_num_rows(mysql_query("SELECT * FROM favourites WHERE creationid=".$creation['id']." AND userid=".$cur_user['id']))!=0){
		$favourited = true;
	}
	else $favourited = false;
}

//Get creation owner info from database
$result = mysql_query("SELECT * FROM users WHERE id = ".$creation['ownerid']);
if (!$result) {
    die(mysql_error());
}
$user = mysql_fetch_array($result);

//Get if the action is favouriting
if (isset($_GET["action"])) if ($_GET["action"] == "favourite") {
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("location: creation.php?id=$creationid");
		exit();
	}
	if (!$favourited){
		mysql_query("INSERT INTO favourites (creationid, userid) VALUES (".$creation['id'].", ".$cur_user['id'].")");
		$favourited = true;
		header("location: creation.php?id=$creationid");
		exit();
	}
	else if ($favourited){
		mysql_query("DELETE FROM favourites WHERE creationid=".$creation['id']." AND userid=".$cur_user['id']);
		$favourited = false;
		header("location: creation.php?id=$creationid");
		exit();
	}
}

//Get if the action is rating
if (isset($_GET["action"])) if ($_GET["action"] == "rate") {
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("location: creation.php?id=$creationid");
		exit();
	}
	else if (empty($_GET["rating"])){
		header("location: creation.php?id=$creationid");
		exit();
	}
	else if ($_GET["rating"]<1 || $_GET["rating"]>5){
		header("location: creation.php?id=$creationid");
		exit();
	}
	else if (mysql_num_rows(mysql_query("SELECT * FROM ratings WHERE userid='".$cur_user['id']."' AND creationid='".$creation['id']."'"))==0){
		mysql_query("INSERT INTO ratings (creationid, userid, rating) VALUES (".$creation['id'].", ".$cur_user['id'].", ".$_GET['rating'].")") or die(mysql_error());
		header("location: creation.php?id=$creationid");
	}
	mysql_query("UPDATE ratings SET rating='".$_GET["rating"]."' WHERE userid='".$cur_user['id']."' AND creationid='".$creation['id']."'") or die(mysql_error());
	header("location: creation.php?id=$creationid");
	exit();
}

//Get if the action is changing the player
if (isset($_GET["action"])) if ($_GET["action"] == "player"){
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("location: creation.php?id=$creationid");
		exit();
	}
	else if (empty($_GET["player"])){
		header("location: creation.php?id=$creationid");
		exit();
	}
	elseif ($_GET["player"]!="js" && $_GET["player"]!="flash"){
		header("location: creation.php?id=$creationid");
		exit();
	}
	mysql_query("UPDATE users SET sb2player='".$_GET["player"]."' WHERE id='".$cur_user['id']."'") or die(mysql_error());
	header("location: creation.php?id=$creationid");
	exit();
}

$views = mysql_num_rows(mysql_query("SELECT * FROM views WHERE creationid=".$creation['id']));
mysql_query("UPDATE creations SET views=".$views." WHERE id=".$creation['id']);
$favourites = mysql_num_rows(mysql_query("SELECT * FROM favourites WHERE creationid=".$creation['id']));
mysql_query("UPDATE creations SET favourites=".$favourites." WHERE id=".$creation['id']);
$i = 0;
//Get ratings
$result = mysql_query("SELECT rating FROM ratings WHERE creationid=".$creation['id']);
while($row = mysql_fetch_array($result)){
	$ratings[$i] = $row[0];
	$i++;
}
if (empty($ratings[0])) $ratings[0] = 0;
if (isset($cur_user['id'])) $lrating = mysql_fetch_array(mysql_query("SELECT rating FROM ratings WHERE creationid=".$creation['id']." AND userid=".$cur_user['id']));
$comments = mysql_query("SELECT * FROM comments WHERE creationid=".$creation['id']." ORDER BY timestamp DESC,userid DESC");
//Get current version
$cur_version_arr = mysql_fetch_row(mysql_query("SELECT MAX(number) FROM versions WHERE creationid=".$creation['id']));
$cur_version = $cur_version_arr[0];
unset($cur_version_arr);
if (empty($cur_version)){
	$cur_version = 1;
}

//If creation ID is a number and corresponds to valid data in the database, display creation
require_once("templates/creation_template.php");



if (isset($_POST['newcomment'])) {
	if (!empty($_POST['commenttext']) && strlen(trim($_POST['commenttext']))>0) {
		if (!empty($_SESSION['SESS_MEMBER_ID'])){
			mysql_query("INSERT INTO comments (creationid, userid, comment) VALUES (".$creation['id'].", ".$cur_user['id'].", '".strip_tags(trim(addslashes($_POST[commenttext]))." "."')")) or die(mysql_error());
			$commentid=mysql_insert_id();
			//send notification about the comment
			if($cur_user['id']!=$user['id']){
				$setting=get_notification_setting_from_id($creation['ownerid']);
				if($setting!="none"&&$setting!="nocomments"){
					$notificationmessage='You have received a new comment by [url=user.php?id='.$cur_user['id'].']'.$cur_user['username'].'[/url] on your creation [url=creation.php?id='.$creation['id'].'#'.$commentid.']'.$creation['name'].'[/url]!';
					mysql_query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$creation['ownerid'].",".$cur_user['id'].",'".addslashes($notificationmessage)."','notification')");
				}
			}
			echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
			exit();
		}
	}
}
if (isset($_POST['reply'])){
	mysql_data_seek($comments,0);
	while ($comment=mysql_fetch_array($comments)){
		if (isset($_POST['msgsubmit'.$comment['id']])&&strlen(trim($_POST['msgsubmit'.$comment['id']]))>0){
			if (!empty($_SESSION['SESS_MEMBER_ID'])){
				mysql_query("INSERT INTO comments (creationid, userid, comment) VALUES (".$creation['id'].", ".$cur_user['id'].", '".trim(addslashes($_POST["msgbody".$comment['id']]))." "."')") or die(mysql_error());
				$commentid=mysql_insert_id();
				//send notification about the comment
				if($cur_user['id']!=$user['id']){
					$setting=get_notification_setting_from_id($creation['ownerid']);
					if($setting!="none"&&$setting!="nocomments"){
						$notificationmessage='You have received a new comment by [url=user.php?id='.$cur_user['id'].']'.$cur_user['username'].'[/url] on your creation [url=creation.php?id='.$creation['id'].'#'.$commentid.']'.$creation['name'].'[/url]!';
						mysql_query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$creation['ownerid'].",".$cur_user['id'].",'".addslashes($notificationmessage)."','notification')");
					}
				}
				$com_user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=".$comment['id']));
				if($com_user['id']!=$user['id']){
					if($com_user['notifications']!="none"&&$com_user['notifications']!="noreplies"){
						$notificationmessage='Your comment on the creation [url=creation.php?id='.$creation['id'].'#'.$commentid.']'.addslashes($creation['name']).'[/url] has been replied to by [url=user.php?id='.$cur_user['id'].']'.$cur_user['username'].'[/url]!';
						mysql_query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$com_user['id'].",".$cur_user['id'].",'".$notificationmessage."','notification')");
					}
				}
				echo "<meta http-equiv='Refresh' content='0; URL=creation.php?id=$creationid'>";
				exit();
			}
		}
	}
}

//function used to set the light-up rating globes to their default
function globesToCurrentRating($crating_arr){
	$crating=$crating_arr[0];
	if ($crating>=1) echo '$("#rating1").css("background-image","url(\'data/icons/prostar.png\')");';
	else echo '$("#rating1").css("background-image","url(\'data/icons/antistar.png\')");';
	if ($crating>=2) echo '$("#rating2").css("background-image","url(\'data/icons/prostar.png\')");';
	else echo '$("#rating2").css("background-image","url(\'data/icons/antistar.png\')");';
	if ($crating>=3) echo '$("#rating3").css("background-image","url(\'data/icons/prostar.png\')");';
	else echo '$("#rating3").css("background-image","url(\'data/icons/antistar.png\')");';
	if ($crating>=4) echo '$("#rating4").css("background-image","url(\'data/icons/prostar.png\')");';
	else echo '$("#rating4").css("background-image","url(\'data/icons/antistar.png\')");';
	if ($crating>=5) echo '$("#rating5").css("background-image","url(\'data/icons/prostar.png\')");';
	else echo '$("#rating5").css("background-image","url(\'data/icons/antistar.png\')");';
}

// Get info for related creations
function getRelatedCreations($creation,$amount){
	//Set the amount to the number below it so it can be used for arrays
	$amount--;
	//Initialise array
	for($i=0;$i<$amount;$i++){
		$related_creations[$i] = '';
	}
	$user_amount = ceil(0.50*$amount);	
	$favourites_amount = ceil(0.25*$amount);
	$similar_amount = ceil(0.25*$amount);
	
	//Get IDs of all creations by this user that aren't this one
	$user_creations_query=mysql_query("SELECT id FROM creations WHERE ownerid=".$creation['ownerid']." AND hidden='no' AND NOT id=".$creation['id']);
	if (mysql_num_rows($user_creations_query)>=$user_amount){
		//Put all those IDs in an array
		$i=0;
		while($user_creation=mysql_fetch_row($user_creations_query)){
			$user_creations[$i]=$user_creation[0];
			$i++;
		}

		//Randomly choose creations from the same user's to display, putting them in random slots
		for ($i=0;$i<$user_amount;$i++){
			$random_pos = rand(0,$amount);
			if(empty($related_creations[$random_pos])){
				$random_id = rand(min($user_creations),max($user_creations));
				if (in_array($random_id, $related_creations)){
					$i--;
				}
				else {
					$related_creations[$random_pos] = rand(min($user_creations),max($user_creations));
				}
			}
			//If position is already taken, rewind and try that index again
			else $i--;
		}
	}	
	else {
		$favourites_amount+=$user_amount;
	}
	
	//Get IDs of all creations in this user's favourites
	$user_favourites_query=mysql_query("SELECT creationid FROM favourites WHERE userid=".$creation['ownerid']." AND NOT creationid=".$creation['id']);
	if (mysql_num_rows($user_favourites_query)>=$favourites_amount){
		//Put all those IDs in an array
		$i=0;
		while($user_favourite=mysql_fetch_row($user_favourites_query)){
			$user_favourites_temp[$i]=$user_favourite[0];
			$i++;
		}
		$user_favourites = array();
		//Construct a new array excluding creations by the creation owner and discard the old one
		for($i=0;$i<count($user_favourites_temp);$i++){
			$user_check_query = mysql_fetch_row(mysql_query("SELECT ownerid FROM creations WHERE id=".$i));
			if($user_check_query=$creation['ownerid']){
				array_push($user_favourites,$user_favourites_temp[$i]);
			}
		}
		unset($user_favourites_temp);

		//Randomly choose creations from the user's favourites's to display, putting them in random slots
		for ($i=0;$i<$favourites_amount;$i++){
			$random_pos = rand(0,$amount);
			if(empty($related_creations[$random_pos])){
				$random_id = rand(min($user_favourites),max($user_favourites));
				if (in_array($random_id, $related_creations)){
					$i--;
				}
				else {
					$related_creations[$random_pos] = rand(min($user_favourites),max($user_favourites));
				}
			}
			//If position is already taken, rewind and try that index again
			else $i--;
		}
	}
	else {
		$similar_amount+=$favourites_amount;
	}
	
	//For now, find a random creation for the remaining 25%
	//Once the search is made, find items with similar titles
	
	//Get IDs of all creations
	$similar_query=mysql_query("SELECT id FROM creations WHERE NOT id=".$creation['id']." AND hidden='no' AND NOT ownerid=".$creation['ownerid']);
	//Put all those IDs in an array
	$i=0;
	while($similar_iterator=mysql_fetch_row($similar_query)){
		$similar[$i]=$similar_iterator[0];
		$i++;
	}
	//Randomly choose creations from the same user's to display, putting them in random slots
	for ($i=0;$i<$similar_amount;$i++){
		$random_pos = rand(0,$amount);
		if(empty($related_creations[$random_pos])){
			$random_id = rand(min($similar),max($similar));
			if (in_array($random_id, $related_creations)){
				$i--;
			}
			else {
				$related_creations[$random_pos] = rand(min($similar),max($similar));
			}
		}
		//If position is already taken, rewind and try that index again
		else $i--;
	}
	//Return IDs for now
	//TODO: Return all creation data in 2D array
	for($i=0;$i<=$amount;$i++){
		$related_creations[$i] =  mysql_fetch_array(mysql_query("SELECT * FROM creations WHERE id=".$related_creations[$i]));
	}
	return $related_creations;
}
?>
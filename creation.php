<?php
//Initialise variable
$creationid = null;

//Get creation ID from URL
//If creation ID not found or is NaN, die
if ( isset( $_GET["id"] ) ) $creationid = htmlspecialchars($_GET["id"]);
if (!$creationid || strcspn($creationid,"0123456789")>0){
	include_once("errors/404.php");
	exit();
}

//Get creation info from database
$result = $mysqli->query("SELECT * FROM creations WHERE id = $creationid");
if (!$result) {
    die( $mysqli->error );
}
$creation = $result->fetch_array();

//If creation ID is not a valid creation, die
if (!$creation){
	include_once("errors/404.php");
	exit();
}

//Test if the creation has enough flags to be auto-censored and censor it if it does
//If creation is marked as alright even after three flags, the creation still shows
$i=0;
$fresult = $mysqli->query("SELECT * FROM flags WHERE parentid = $creationid") or die( $mysqli->error );
while ( $row = $fresult->fetch_array() ){
	$flags[$i] = $row[2];
	$i++;
}
$farray = $mysqli->query( "SELECT hidden FROM creations WHERE id = " . $creation['id'] )->fetch_array();
if (!empty($flags)){
	if (count(array_unique($flags))>=FLAGS_REQUIRED&&$farray[0]=="no") {
		$mysqli->query("UPDATE creations SET hidden='flagged' WHERE id='".$creation['id']."'") or die( $mysqli->error );
		$mysqli->query("DELETE FROM flags WHERE parentid=".$creation['id']." AND type='creation'");
	}
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
	if ( $mysqli->query("SELECT * FROM views WHERE viewip='" . $_SERVER['REMOTE_ADDR'] . "' AND creationid=" . $creation['id'] )->num_rows == 0 ){
		$mysqli->query( "INSERT INTO views (creationid, viewip) VALUES (" . $creation['id'] . ", '" . $_SERVER['REMOTE_ADDR'] . "')" );
	}

	if ( $mysqli->query( "SELECT * FROM favourites WHERE creationid=" . $creation['id'] . " AND userid=" . $cur_user['id'] )->num_rows != 0 ){
		$favourited = true;
	}
	else $favourited = false;
}

//Get creation owner info from database
$result = $mysqli->query( "SELECT * FROM users WHERE id = " . $creation['ownerid'] );
if (!$result) {
    die( $mysqli->error );
}
$user = $result->fetch_array();

//Get if the action is favouriting
if (isset($_GET["action"])) if ($_GET["action"] == "favourite") {
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("Location: .");
		exit();
	}
	if (!$favourited){
		$mysqli->query("INSERT INTO favourites (creationid, userid) VALUES (".$creation['id'].", ".$cur_user['id'].")");
		$favourited = true;
		header("Location: .");
		exit();
	}
	else if ($favourited){
		$mysqli->query("DELETE FROM favourites WHERE creationid=".$creation['id']." AND userid=".$cur_user['id']);
		$favourited = false;
		header("Location: .");
		exit();
	}
}

//Get if the action is rating
if (isset($_GET["action"])) if ($_GET["action"] == "rate") {
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("Location: ..");
		exit();
	}
	else if (empty($_GET["rating"])){
		header("Location: .");
		exit();
	}
	else if ($_GET["rating"]<1 || $_GET["rating"]>5){
		header("Location: ..");
		exit();
	}
	else if ( $mysqli->query( "SELECT * FROM ratings WHERE userid='" . $cur_user['id'] . "' AND creationid='" . $creation['id'] . "'" )->num_rows == 0 ){
		$mysqli->query("INSERT INTO ratings (creationid, userid, rating) VALUES (".$creation['id'].", ".$cur_user['id'].", ".$_GET['rating'].")") or die( $mysqli->error );
		header("Location: ..");
	}
	$mysqli->query("UPDATE ratings SET rating='".$_GET["rating"]."' WHERE userid='".$cur_user['id']."' AND creationid='".$creation['id']."'") or die( $mysqli->error );
	header("Location: ..");
	exit();
}

//Get if the action is changing the player
if (isset($_GET["action"])) if ($_GET["action"] == "player"){
	if (empty($_SESSION['SESS_MEMBER_ID'])){
		header("Location: .");
		exit();
	}
	else if (empty($_GET["player"])){
		header("Location: .");
		exit();
	}
	elseif ($_GET["player"]!="js" && $_GET["player"]!="flash"){
		header("Location: .");
		exit();
	}
	$mysqli->query("UPDATE users SET sb2player='".$_GET["player"]."' WHERE id='".$cur_user['id']."'") or die( $mysqli->error );
	header("location: creation.php?id=$creationid");
	exit();
}

$views = $mysqli->query("SELECT * FROM views WHERE creationid=".$creation['id'])->num_rows;
$mysqli->query("UPDATE creations SET views=".$views." WHERE id=".$creation['id']);
$favourites = $mysqli->query("SELECT * FROM favourites WHERE creationid=".$creation['id'])->num_rows;
$mysqli->query("UPDATE creations SET favourites=".$favourites." WHERE id=".$creation['id']);
$i = 0;
//Get ratings
$result = $mysqli->query("SELECT rating FROM ratings WHERE creationid=".$creation['id']);
while ( $row = $result->fetch_array() ){
	$ratings[$i] = $row[0];
	$i++;
}
if (empty($ratings[0])) $ratings[0] = 0;
if (isset($cur_user['id'])) $lrating = $mysqli->query("SELECT rating FROM ratings WHERE creationid=".$creation['id']." AND userid=".$cur_user['id'])->fetch_array();
$comments = $mysqli->query("SELECT * FROM comments WHERE creationid=".$creation['id']." ORDER BY timestamp DESC,userid DESC");
//Get current version
$cur_version_arr = $mysqli->query("SELECT MAX(number) FROM versions WHERE creationid=".$creation['id'])->fetch_array();
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
			$mysqli->query("INSERT INTO comments (creationid, userid, comment) VALUES (".$creation['id'].", ".$cur_user['id'].", '".strip_tags(trim(addslashes($_POST[commenttext]))." "."')")) or die( $mysqli->error );
			$commentid = $mysqli->insert_id;
			//send notification about the comment
			if($cur_user['id']!=$user['id']){
				$setting=get_notification_setting_from_id($creation['ownerid']);
				if($setting!="none"&&$setting!="nocomments"){
					$notificationmessage='You have received a new comment by [url=user.php?id='.$cur_user['id'].']'.$cur_user['username'].'[/url] on your creation [url=creation.php?id='.$creation['id'].'#'.$commentid.']'.$creation['name'].'[/url]!';
					$mysqli->query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$creation['ownerid'].",".$cur_user['id'].",'".addslashes($notificationmessage)."','notification')");
				}
			}
			echo "<meta http-equiv='Refresh' content='0'>";
			exit();
		}
	}
}
if (isset($_POST['reply'])){
	$comments->data_seek( 0 );
	while ($comment = $comments->fetch_array() ){
		if (isset($_POST['msgsubmit'.$comment['id']])&&strlen(trim($_POST['msgsubmit'.$comment['id']]))>0){
			if (!empty($_SESSION['SESS_MEMBER_ID'])){
				$mysqli->query("INSERT INTO comments (creationid, userid, comment) VALUES (".$creation['id'].", ".$cur_user['id'].", '".trim(addslashes($_POST["msgbody".$comment['id']]))." "."')") or die( $mysqli->error );
				$commentid = $mysqli->insert_id;
				//send notification about the comment
				if($cur_user['id']!=$user['id']){
					$setting = get_notification_setting_from_id( $creation['ownerid'], $mysqli );
					if($setting!="none"&&$setting!="nocomments"){
						$notificationmessage='You have received a new comment by [url=user.php?id='.$cur_user['id'].']'.$cur_user['username'].'[/url] on your creation [url=creation.php?id='.$creation['id'].'#'.$commentid.']'.$creation['name'].'[/url]!';
						$mysqli->query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$creation['ownerid'].",".$cur_user['id'].",'".addslashes($notificationmessage)."','notification')");
					}
				}
				$com_user = $mysqli->query("SELECT * FROM users WHERE id=".$comment['id'])->fetch_array();
				if($com_user['id']!=$user['id']){
					if($com_user['notifications']!="none"&&$com_user['notifications']!="noreplies"){
						$notificationmessage='Your comment on the creation [url=creation.php?id='.$creation['id'].'#'.$commentid.']'.addslashes($creation['name']).'[/url] has been replied to by [url=user.php?id='.$cur_user['id'].']'.$cur_user['username'].'[/url]!';
						$mysqli->query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$com_user['id'].",".$cur_user['id'].",'".$notificationmessage."','notification')");
					}
				}
				echo "<meta http-equiv='Refresh' content='0'>";
				exit();
			}
		}
	}
}

//function used to set the light-up rating globes to their default
function globesToCurrentRating($crating_arr){
	$crating=$crating_arr[0];
	if ($crating>=1) echo '$("#rating1").css("background-image","url(\'../data/icons/prostar.png\')");';
	else echo '$("#rating1").css("background-image","url(\'../data/icons/antistar.png\')");';
	if ($crating>=2) echo '$("#rating2").css("background-image","url(\'../data/icons/prostar.png\')");';
	else echo '$("#rating2").css("background-image","url(\'../data/icons/antistar.png\')");';
	if ($crating>=3) echo '$("#rating3").css("background-image","url(\'../data/icons/prostar.png\')");';
	else echo '$("#rating3").css("background-image","url(\'../data/icons/antistar.png\')");';
	if ($crating>=4) echo '$("#rating4").css("background-image","url(\'../data/icons/prostar.png\')");';
	else echo '$("#rating4").css("background-image","url(\'../data/icons/antistar.png\')");';
	if ($crating>=5) echo '$("#rating5").css("background-image","url(\'../data/icons/prostar.png\')");';
	else echo '$("#rating5").css("background-image","url(\'../data/icons/antistar.png\')");';
}

// Get info for related creations
function getRelatedCreations( $creation, $amount, $mysqli ){
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
	$user_creations_query=$mysqli->query("SELECT id FROM creations WHERE ownerid=".$creation['ownerid']." AND hidden='no' AND NOT id=".$creation['id']);
	if ( $user_creations_query->num_rows >= $user_amount ){
		//Put all those IDs in an array
		$i=0;
		while( $user_creation=$user_creations_query->fetch_array() ){
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
		$favourites_amount += $user_amount;
	}
	
	//Get IDs of all creations in this user's favourites
	$user_favourites_query=$mysqli->query("SELECT creationid FROM favourites WHERE userid=".$creation['ownerid']." AND NOT creationid=".$creation['id']);
	if ( $user_favourites_query->num_rows >= $favourites_amount ){
		//Put all those IDs in an array
		$i=0;
		while ( $user_favourite = $user_favourites_query->fetch_array() ) {
			$user_favourites_temp[$i]=$user_favourite[0];
			$i++;
		}
		$user_favourites = array();
		//Construct a new array excluding creations by the creation owner and discard the old one
		for($i=0;$i<count($user_favourites_temp);$i++){
			$user_check_query = $mysqli->query("SELECT ownerid FROM creations WHERE id=".$i)->fetch_row();
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
	$similar_query=$mysqli->query("SELECT id FROM creations WHERE NOT id=".$creation['id']." AND hidden='no' AND NOT ownerid=".$creation['ownerid']);
	//Put all those IDs in an array
	$i=0;
	while( $similar_iterator = $similar_query->fetch_array() ){
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
	for( $i=0; $i <= $amount; $i++ ){
		$related_creations[$i] =  $mysqli->query( "SELECT * FROM creations WHERE id=" . $related_creations[$i] )->fetch_array();
	}
	return $related_creations;
}
?>
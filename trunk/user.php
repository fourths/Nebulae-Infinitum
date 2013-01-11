<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Initialise variable
$userid = null;


//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		echo "Could not run query: " . mysql_error() and die;
	}
	$cur_user = mysql_fetch_array($lresult);
}
if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

//Get user ID from URL
//If user ID not found or is NaN, die
if (isset($_GET["id"])) $userid = htmlspecialchars($_GET["id"]);
if (!$userid || strcspn($userid,"0123456789")>0){
	include_once("errors/404.php");
}

//Get user info from database
$result = mysql_query("SELECT * FROM users WHERE id = $userid");
if (!$result) {
    die(mysql_error());
}
$user = mysql_fetch_array($result);
if ($user['banstatus'] == "deleted") {
	include_once("templates/user_deleted.php");
	exit();
}

//If user ID is not a valid user, die
if (!$user){
	include_once("errors/404.php");
	exit();
}
$favourites=mysql_query("SELECT creationid FROM favourites WHERE userid=".$userid." ORDER BY timestamp DESC");
$writing=mysql_query("SELECT id FROM creations WHERE ownerid=".$userid." AND type='writing' ORDER BY created DESC");
$artwork=mysql_query("SELECT id FROM creations WHERE ownerid=".$userid." AND type='artwork' ORDER BY created DESC");
$audio=mysql_query("SELECT id FROM creations WHERE ownerid=".$userid." AND type='audio' ORDER BY created DESC");
$other=mysql_query("SELECT id FROM creations WHERE ownerid=".$userid." AND type='flash' OR ownerid=".$userid." AND type='scratch' ORDER BY created DESC");

$i=0;

//If user ID is a number and corresponds to valid data in the database, display userpage
require_once("templates/user_template.php");

/*
show_creations()
Function for displaying creations on the page

Arguments:
$creationlist (MySQL resource) - List of all the IDs of creations to be displayed, stored in a MySQL query result format.
$cur_user (array) - Array of info from database about logged-in user. Always called $cur_user in my code here.
$user (array) - Like $cur_user, this is an array of user info. It, however, is data of the user whose userpage is being viewed.
$favourites (boolean) - Specifies whether this should display favourites thumbnails, which include a remove from favourites icon instead of the standard edit and delete ones.
*/
function show_creations($creationlist,$cur_user,$user,$favourites=false){
	if (isset($creationlist)){
		if((int) mysql_fetch_array($creationlist) == 0) echo "This user has no creations of this type.";
		//reset pointer so it displays all creations
		else{
			mysql_data_seek($creationlist,0);
			while ($creation = mysql_fetch_array($creationlist)){
				$creationcondition="";
				$creation=mysql_fetch_array(mysql_query("SELECT * FROM creations WHERE id=".$creation['id']));
				//set the background colour of the thumbnails
				switch ($creation['type']){
					case "artwork":
						$thumbcolour="#FC8888";
						break;
					case "scratch":
						$thumbcolour="#FFBA70";
						break;
					case "flash":
						$thumbcolour="#FCFFA8";
						break;
					case "writing":
						$thumbcolour="#A8E5FF";
						break;
					case "audio":
						$thumbcolour="#87FF91";
						break;
					default:
						$thumbcolour="#FFFFFF";
				}
				if ($favourites==true){
					if ($creation && (($creation['hidden']=="approved"||$creation['hidden']=="no")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")){
						if(!file_exists('data/thumbs/'.$creation['id'].'.png')){
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creation['id'].'"><img class="creationthumbimg" src="data/thumbs/default.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="creation.php?id='.$creation['id'].'&action=favourite"></a>';
							echo '</a><a href="creation.php?id='.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a><br/><span class="creationthumbcaption" style="font-size:9px;display:inline;">by <a href="user.php?id='.$creation['ownerid'].'">'.get_username_from_id($creation['ownerid']).'</a></div>';
						}
						else{
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creation['id'].'"><img class="creationthumbimg" src="data/thumbs/'.$creation['id'].'.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="creation.php?id='.$creation['id'].'&action=favourite"></a>';
							echo '</a><a href="creation.php?id='.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a><br/><span class="creationthumbcaption" style="font-size:9px;display:inline;">by <a href="user.php?id='.$creation['ownerid'].'">'.get_username_from_id($creation['ownerid']).'</a></div>';
						}
						$num_creations++;
					}
				if ($num_creations==0) echo "This user has no favourites.";
				}
				else {
					if ($creation && (($creation['hidden']=="approved"||$creation['hidden']=="no")||(($cur_user['id']==$user['id']&&$creation['hidden']!="deleted"&&$creation['hidden']!="censored")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod"))){
						if ($creation['filetype']=="svg"&&$creation['filetype']=="tif"||$creation['filetype']=="tiff") {
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creation['id'].'"><img class="creationthumbimg" src="data/creations/'.$creation['filename'].'"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="delete.php?id='.$creation['id'].'"></a><a class="editbutton" href="edit.php?id='.$creation['id'].'">';
							if (($creation['hidden']!="no")&&($cur_user['rank']=="admin"||$cur_user['rank']=="mod")){
								switch($creation['hidden']){
									case "byowner":
										$creationcondition = "<strong>(hidden)</strong>";
										break;
									default:
										$creationcondition = "<strong>(".$creation['hidden'].")</strong>";
										break;
								}
							}
							else if (($creation['hidden']=="byowner")&&($cur_user['id']==$user['id'])){
								$creationcondition = "<strong>(hidden)</strong>";
							}
							echo '</a><a href="creation.php?id='.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
						else if (!file_exists('data/thumbs/'.$creation['id'].'.png')){
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creation['id'].'"><img class="creationthumbimg" src="data/thumbs/default.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="delete.php?id='.$creation['id'].'"></a><a class="editbutton" href="edit.php?id='.$creation['id'].'">';
							if (($creation['hidden']!="no")&&($cur_user['rank']=="admin"||$cur_user['rank']=="mod")){
								switch($creation['hidden']){
									case "byowner":
										$creationcondition = "<strong>(hidden)</strong>";
										break;
									default:
										$creationcondition = "<strong>(".$creation['hidden'].")</strong>";
								}
							}
							else if (($creation['hidden']=="byowner")&&($cur_user['id']==$user['id'])){
								$creationcondition = "<strong>(hidden)</strong>";
							}
							echo '</a><a href="creation.php?id='.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
						else{
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creation['id'].'"><img class="creationthumbimg" src="data/thumbs/'.$creation['id'].'.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="delete.php?id='.$creation['id'].'"></a><a class="editbutton" href="edit.php?id='.$creation['id'].'">';
							if (($creation['hidden']!="no")&&($cur_user['rank']=="admin"||$cur_user['rank']=="mod")){
								switch($creation['hidden']){
									case "byowner":
										$creationcondition = "<strong>(hidden)</strong>";
										break;
									default:
										$creationcondition = "<strong>(".$creation['hidden'].")</strong>";
								}
							}
							else if (($creation['hidden']=="byowner")&&($cur_user['id']==$user['id'])){
								$creationcondition = "<strong>(hidden)</strong>";
							}
							echo '</a><a href="creation.php?id='.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
					}
					if ($num_creations==0) echo "This user has no creations of the selected type.";
				}
			}
		}
		echo '<div style="clear:both;width:100%;height:5px;"></div>';
	}
	else echo "This user has no creations of the selected type.";
}


if (isset($_POST['pmsubmit'])){
	mysql_query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$user['id'].",".$cur_user['id'].",'".addslashes($_POST['pmbody'])."','pm')");
	die("Your message has been sent.");
}
?>
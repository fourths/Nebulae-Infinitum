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
	$luserdata = mysql_fetch_row($lresult);
}
if ($luserdata[6] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($luserdata[6] == "deleted") {
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
$userdata = mysql_fetch_row($result);
if ($userdata[6] == "deleted") {
	include_once("templates/user_deleted.php");
	exit();
}

//If user ID is not a valid user, die
if (!$userdata){
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
$luserdata (array) - Array of info from database about logged-in user. Always called $luserdata in my code here.
$userdata (array) - Like $luserdata, this is an array of user info. It, however, is data of the user whose userpage is being viewed.
$favourites (boolean) - Specifies whether this should display favourites thumbnails, which include a remove from favourites icon instead of the standard edit and delete ones.
*/
function show_creations($creationlist,$luserdata,$userdata,$favourites){
	if (isset($creationlist)){
		if((int) mysql_fetch_row($creationlist) == 0) echo "This user has no creations of this type.";
		//reset pointer so it displays all creations
		else{
			mysql_data_seek($creationlist,0);
			while ($creation = mysql_fetch_row($creationlist)){
				$creationcondition="";
				$creationdata=mysql_fetch_row(mysql_query("SELECT * FROM creations WHERE id=".$creation[0]));
				//set the background colour of the thumbnails
				switch ($creationdata[2]){
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
					if ($creationdata && (($creationdata[6]=="approved"||$creationdata[6]=="no")||$luserdata[3]=="admin"||$luserdata[3]=="mod")){
						if(!file_exists('data/thumbs/'.$creationdata[0].'.png')){
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creationdata[0].'"><img class="creationthumbimg" src="data/thumbs/default.png"/></a>';
							if ((($luserdata[0]==$userdata[0]&&$creationdata[6]!="censored"&&$creationdata[6]!="deleted")||$luserdata[3]=="admin"||$luserdata[3]=="mod")) echo '<a class="deletebutton" href="creation.php?id='.$creationdata[0].'&action=favourite"></a>';
							echo '</a><a href="creation.php?id='.$creationdata[0].'" class="creationthumbcaption">'.stripslashes($creationdata[1]).'</a><br/><span class="creationthumbcaption" style="font-size:9px;display:inline;">by <a href="user.php?id='.$creationdata[3].'">'.get_username_from_id($creationdata[3]).'</a></div>';
						}
						else{
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creationdata[0].'"><img class="creationthumbimg" src="data/thumbs/'.$creationdata[0].'.png"/></a>';
							if ((($luserdata[0]==$userdata[0]&&$creationdata[6]!="censored"&&$creationdata[6]!="deleted")||$luserdata[3]=="admin"||$luserdata[3]=="mod")) echo '<a class="deletebutton" href="creation.php?id='.$creationdata[0].'&action=favourite"></a>';
							echo '</a><a href="creation.php?id='.$creationdata[0].'" class="creationthumbcaption">'.stripslashes($creationdata[1]).'</a><br/><span class="creationthumbcaption" style="font-size:9px;display:inline;">by <a href="user.php?id='.$creationdata[3].'">'.get_username_from_id($creationdata[3]).'</a></div>';
						}
						$num_creations++;
					}
				if ($num_creations==0) echo "This user has no favourites.";
				}
				else {
					if ($creationdata && (($creationdata[6]=="approved"||$creationdata[6]=="no")||(($luserdata[0]==$userdata[0]&&$creationdata[6]!="deleted"&&$creationdata[6]!="censored")||$luserdata[3]=="admin"||$luserdata[3]=="mod"))){
						if ($creationdata[7]=="svg"&&$creationdata[7]=="tif"||$creationdata[7]=="tiff") {
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creationdata[0].'"><img class="creationthumbimg" src="data/projects/'.$creationdata[8].'"/></a>';
							if ((($luserdata[0]==$userdata[0]&&$creationdata[6]!="censored"&&$creationdata[6]!="deleted")||$luserdata[3]=="admin"||$luserdata[3]=="mod")) echo '<a class="deletebutton" href="delete.php?id='.$creationdata[0].'"></a><a class="editbutton" href="edit.php?id='.$creationdata[0].'">';
							if (($creationdata[6]!="no")&&($luserdata[3]=="admin"||$luserdata[3]=="mod")){
								switch($creationdata[6]){
									case "byowner":
										$creationcondition = "<strong>(hidden)</strong>";
										break;
									default:
										$creationcondition = "<strong>(".$creationdata[6].")</strong>";
										break;
								}
							}
							else if (($creationdata[6]=="byowner")&&($luserdata[0]==$userdata[0])){
								$creationcondition = "<strong>(hidden)</strong>";
							}
							echo '</a><a href="creation.php?id='.$creationdata[0].'" class="creationthumbcaption">'.stripslashes($creationdata[1]).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
						else if (!file_exists('data/thumbs/'.$creationdata[0].'.png')){
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creationdata[0].'"><img class="creationthumbimg" src="data/thumbs/default.png"/></a>';
							if ((($luserdata[0]==$userdata[0]&&$creationdata[6]!="censored"&&$creationdata[6]!="deleted")||$luserdata[3]=="admin"||$luserdata[3]=="mod")) echo '<a class="deletebutton" href="delete.php?id='.$creationdata[0].'"></a><a class="editbutton" href="edit.php?id='.$creationdata[0].'">';
							if (($creationdata[6]!="no")&&($luserdata[3]=="admin"||$luserdata[3]=="mod")){
								switch($creationdata[6]){
									case "byowner":
										$creationcondition = "<strong>(hidden)</strong>";
										break;
									default:
										$creationcondition = "<strong>(".$creationdata[6].")</strong>";
								}
							}
							else if (($creationdata[6]=="byowner")&&($luserdata[0]==$userdata[0])){
								$creationcondition = "<strong>(hidden)</strong>";
							}
							echo '</a><a href="creation.php?id='.$creationdata[0].'" class="creationthumbcaption">'.stripslashes($creationdata[1]).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
						else{
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creationdata[0].'"><img class="creationthumbimg" src="data/thumbs/'.$creationdata[0].'.png"/></a>';
							if ((($luserdata[0]==$userdata[0]&&$creationdata[6]!="censored"&&$creationdata[6]!="deleted")||$luserdata[3]=="admin"||$luserdata[3]=="mod")) echo '<a class="deletebutton" href="delete.php?id='.$creationdata[0].'"></a><a class="editbutton" href="edit.php?id='.$creationdata[0].'">';
							if (($creationdata[6]!="no")&&($luserdata[3]=="admin"||$luserdata[3]=="mod")){
								switch($creationdata[6]){
									case "byowner":
										$creationcondition = "<strong>(hidden)</strong>";
										break;
									default:
										$creationcondition = "<strong>(".$creationdata[6].")</strong>";
								}
							}
							else if (($creationdata[6]=="byowner")&&($luserdata[0]==$userdata[0])){
								$creationcondition = "<strong>(hidden)</strong>";
							}
							echo '</a><a href="creation.php?id='.$creationdata[0].'" class="creationthumbcaption">'.stripslashes($creationdata[1]).'</a> '.$creationcondition.'</div>';
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
	mysql_query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$userdata[0].",".$luserdata[0].",'".addslashes($_POST['pmbody'])."','pm')");
	die("Your message has been sent.");
}
?>
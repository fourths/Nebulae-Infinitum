<?php
//Get user ID from URL
//If user ID not found or is NaN, die
if ( isset( $_GET["id"] ) ){
	$userid = htmlspecialchars($_GET["id"]);
}
if ( !$userid || strcspn( $userid, "0123456789" ) > 0 ){
	include_once("errors/404.php");
}

//Get user info from database
$user_query = $mysqli->query( "SELECT * FROM users WHERE id=" . $userid );
if ( $mysqli->errno ) {
	die( "Could not read user data from database: " . $mysqli->error );
}
$user = $user_query->fetch_array();
unset($user_query);


//If user ID is not a valid user, die
if ( !isset( $user ) ){
	include_once("errors/404.php");
	exit();
}

$amounts = array();
$creations=array(
	"favourites" => $mysqli->query( "SELECT creationid FROM favourites WHERE userid=".$userid." ORDER BY timestamp DESC" ),
	"writing"    =>$mysqli->query( "SELECT id FROM creations WHERE ownerid=".$userid." AND type='writing' ORDER BY created DESC" ),
	"artwork"    => $mysqli->query( "SELECT id FROM creations WHERE ownerid=".$userid." AND type='artwork' ORDER BY created DESC" ),
	"audio"      => $mysqli->query( "SELECT id FROM creations WHERE ownerid=".$userid." AND type='audio' ORDER BY created DESC" ),
	"other"      => $mysqli->query( "SELECT id FROM creations WHERE ownerid=".$userid." AND type='flash' OR ownerid=".$userid." AND type='scratch' ORDER BY created DESC" )
);
$creation_types = array_keys($creations);
$j=0;
foreach($creations as $creation){
	$amounts[$j] = $creation->num_rows;
	$j++;
}

//If user ID is a number and corresponds to valid data in the database, display userpage
require_once("templates/user_template.php");

/*
show_creations()
Function for displaying creations on the page

Arguments:
$mysqli (mysqli connection) - Database connection for the function to use
$creationlist (MySQL resource) - List of all the IDs of creations to be displayed, stored in a MySQL query result format.
$cur_user (array) - Array of info from database about logged-in user. Always called $cur_user in my code here.
$user (array) - Like $cur_user, this is an array of user info. It, however, is data of the user whose userpage is being viewed.
$favourites (boolean) - Specifies whether this should display favourites thumbnails, which include a remove from favourites icon instead of the standard edit and delete ones.
*/
function show_creations( $mysqli, $creationlist, $cur_user, $user, $page, $favourites = false ){
	if ( isset( $creationlist ) ){
		
		$temp_arr =  $creationlist->fetch_array();
		if( empty ( $temp_arr ) ){
			if ($favourites == true){
				echo "This user has no favourites.";
			}
			else{
				echo "This user has no creations of this type.";
			}
			
		}
		//reset pointer so it displays all creations
		else{
			
			$offset = $page*16;
			$creations = array();
			$creationlist->data_seek( $offset );
			for( $i = $offset; $i < $offset + 16; $i++){
				$creations[$i] = $creationlist->fetch_array();
			}
			//echo "<pre>".print_r($creations,true)."</pre>";
			$creationlist->data_seek(0);
			foreach ($creations as $creation){
				$creationcondition="";
				// If there's no creation there, get out of the foreach
				if (!$creation){
					break;
				}
				if($favourites){
					$creation=$mysqli->query("SELECT * FROM creations WHERE id=".$creation['creationid'])->fetch_array();
				}
				else{
					$creation=$mysqli->query("SELECT * FROM creations WHERE id=".$creation['id'])->fetch_array();
				}
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
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="../creation/'.$creation['id'].'"><img class="creationthumbimg" src="../data/thumbs/default.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="../creation/'.$creation['id'].'/favourite"></a>';
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
							echo '</a><a href="../creation/'.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'<br/><span class="creationthumbcaption" style="font-size:9px;display:inline;">by <a href="../user/' . get_username_from_id( $creation['ownerid'], $mysqli ) . '">'.get_username_from_id( $creation['ownerid'], $mysqli ).'</a></div>';
						}
						else{
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="../creation/'.$creation['id'].'"><img class="creationthumbimg" src="../data/thumbs/'.$creation['id'].'.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="../creation/'.$creation['id'].'/favourite"></a>';
							echo '</a><a href="../creation/'.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'<br/><span class="creationthumbcaption" style="font-size:9px;display:inline;">by <a href="../user/' . get_username_from_id( $creation['ownerid'], $mysqli ) . '">'.get_username_from_id( $creation['ownerid'], $mysqli ).'</a></div>';
						}
						$favs++;
					}
				if ($favs==0) echo "This user has no favourites.";
				}
				else {
					if ($creation && (($creation['hidden']=="approved"||$creation['hidden']=="no")||(($cur_user['id']==$user['id']&&$creation['hidden']!="deleted"&&$creation['hidden']!="censored")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod"))){
						if ($creation['filetype']=="svg"&&$creation['filetype']=="tif"||$creation['filetype']=="tiff") {
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="creation.php?id='.$creation['id'].'"><img class="creationthumbimg" src="../data/creations/'.$creation['filename'].'"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="../creation/'.$creation['id'].'/delete"></a><a class="editbutton" href="../creation/'.$creation['id'].'/edit">';
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
							echo '</a><a href="../creation/'.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
						else if (!file_exists('data/thumbs/'.$creation['id'].'.png')){
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="../creation/'.$creation['id'].'"><img class="creationthumbimg" src="../data/thumbs/default.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="../creation/'.$creation['id'].'/delete"></a><a class="editbutton" href="../creation/'.$creation['id'].'/edit">';
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
							echo '</a><a href="../creation/'.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
						else{
							echo '
							<div class="creationthumb" style="background-color:'.$thumbcolour.'"><a href="../creation/'.$creation['id'].'"><img class="creationthumbimg" src="../data/thumbs/'.$creation['id'].'.png"/></a>';
							if ((($cur_user['id']==$user['id']&&$creation['hidden']!="censored"&&$creation['hidden']!="deleted")||$cur_user['rank']=="admin"||$cur_user['rank']=="mod")) echo '<a class="deletebutton" href="../creation/'.$creation['id'].'/delete"></a><a class="editbutton" href="../creation/'.$creation['id'].'/edit">';
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
							echo '</a><a href="../creation/'.$creation['id'].'" class="creationthumbcaption">'.stripslashes($creation['name']).'</a> '.$creationcondition.'</div>';
							$num_creations++;
						}
					}
					if ($num_creations==0) echo "This user has no creations of this type.";
				}
			}
		}
		//echo '<div style="clear:both;width:100%;height:5px;"></div>';
	}
	else{
		if (!$favourites) echo "This user has no creations of this type.";
		else echo "This user has no favourites.";
	}
}


if (isset($_POST['pmsubmit'])){
	$mysql->query("INSERT INTO messages (recipientid,senderid,message,type) VALUES (".$user['id'].",".$cur_user['id'].",'".addslashes($_POST['pmbody'])."','pm')");
	die("Your message has been sent.");
}
?>
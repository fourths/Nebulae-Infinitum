<?php
$passupdate = null;

//Check if user is admin/mod or is the user whose preferences these are
//If not, die
if (isset($_GET["id"])) {	
	
	//Get user (whose preferences these are) info from database
	//If user does not exist, throw 404
	$result = $mysqli->query("SELECT * FROM users WHERE id =" .  $_GET["id"] );
	if (!$result) {
		require_once( "errors/404.php" );
		exit();
	}
	$user = $result->fetch_array();
	
	//If the user query or array has an error or there are non-numeric characters in the id, die
	if ( $result->num_rows != 1 || !$user || strcspn($_GET["id"], "0123456789") > 0 ){
		require_once("errors/404.php");
		exit();
	}
	
	//If the user isn't the owner or an admin/mod, throw 403
	if ($cur_user['id'] != $user['id'] && $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod" ){
		require_once("errors/403.php");
		exit();
	}
	
	//If all goes well, include the 
	require_once( "templates/pref_template.php" );
}
else{
	require_once( "errors/404.php" );
	exit();
}

//If the password change form is submitted
if ( isset ( $_POST['passchange'] ) ) {

	//Get the current password for checking
	$result = $mysqli->query( "SELECT * FROM users WHERE id='" . $user['id'] . "' AND password='" . nebulae_hash( $_POST['curpass'] ) . "'" );
	
	//If the current password entered doesn't match the stored one, die
	if ( $result->num_rows != 1 ){
		die( "Invalid current password." );
	}
	
	//If the two new passwords don't match, die
	if ( nebulae_hash( $_POST['newpass'] ) != nebulae_hash( $_POST['cnewpass'] ) ) {
		die( "The new passwords do not match." );
	}
	
	//Otherwise, enter the new password into the database
	$mysqli->query( "UPDATE users SET password='" . nebulae_hash( $_POST['newpass'] ) . "' WHERE id='" . $user['id'] . "'") or die( $mysqli->error );
	
	//Redirect to the user's userpage
	echo "<meta http-equiv='Refresh' content='0; URL=.'>";
}

//If the email change form is submitted, enter the new email into the database and redirect to the user's userpage
if ( isset( $_POST['emailchange'] ) ) {
	$mysqli->query("UPDATE users SET email='" . addslashes( $_POST['newemail'] )."' WHERE id='" . $user['id'] . "'") or die( $mysqli->error );
	echo "<meta http-equiv='Refresh' content='0; URL=.'>";
}

//If the icon change form is submitted
if (isset($_POST['iconchange'])) {
	//TO-DO: check if a valid image (PNG, GIF, JPG, BMP?, TIFF?)
	//If there is a path in the upload field and it is a valid file
	if ( !empty($_FILES['newicon'] ) && file_exists( $_FILES['newicon']['tmp_name'] ) ) {
		//Get the image from the temporary upload
		$thumbimg = imagecreatefromstring( file_get_contents( $_FILES['newicon']['tmp_name'] ) );
		//Create a new blank 180x180 image
		$rzthumbimg = imagecreatetruecolor( 180, 180 );
		//Rescale the new icon to 180x180 and insert in the new blank image
		imagecopyresampled($rzthumbimg, $thumbimg, 0, 0, 0, 0, 180, 180, imagesx( $thumbimg ), imagesy( $thumbimg ) );
		//Save the resized icon as a png
		imagepng( $rzthumbimg, "data/usericons/" . $user['id'] . ".png", 9);
		//Insert the path to the new icon in the database
		$mysqli->query( "UPDATE users SET icon='" . $user['id'] . ".png' WHERE id='" . $user['id'] . "'") or die( $mysqli->error );
		//Redirect to the user's userpage
		echo "<meta http-equiv='Refresh' content='0; URL=.'>";
		exit();
	}
	else die( $mysqli->error );
}

//If the userpage preferences form is submitted, update the relevant values and then redirect to the user's userpage
if ( isset($_POST['userchange'] ) ) {
	$mysqli->query("UPDATE users SET age='" . htmlspecialchars( addslashes( $_POST['age'] ) ) . "' WHERE id='" . $user['id'] . "'" ) or die( $mysqli->error );
	$mysqli->query("UPDATE users SET gender='" . htmlspecialchars( addslashes( $_POST['gender'] ) ) . "' WHERE id='" . $user['id'] . "'" ) or die( $mysqli->error );
	$mysqli->query("UPDATE users SET location='" . htmlspecialchars( addslashes( $_POST['location'] ) ) . "' WHERE id='" . $user['id'] . "'" ) or die( $mysqli->error );
	$mysqli->query("UPDATE users SET about='" . htmlspecialchars( addslashes( $_POST['about'] ) ) ."' WHERE id='" . $user['id'] . "'" ) or die( $mysqli->error);
	echo "<meta http-equiv='Refresh' content='0; URL=.'>";
	exit();
}

//If the admin preferences form is submitted
if ( isset( $_POST['adminchange'] ) ) {
	//If the current user is an admin and they set the "rank" attribute to a valid value, update it accordingly in the database
	if ( $cur_user['rank'] == "admin" && ( $_POST['rank'] == 'admin' || $_POST['rank']=='mod' || $_POST['rank']=='user' ) ) {
		$mysqli->query( "UPDATE users SET rank='" . $_POST['rank']."' WHERE id='" . $user['id'] . "'" ) or die( $mysqli->error );
	}
	
	//If the current user is an admin or a mod, update the ban status, ban date, and ban reason
	if ( $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod" ){
		$mysqli->query( "UPDATE users SET banstatus='" . $_POST['ban'] . "' WHERE id='" . $user['id'] . "'" ) or die( $mysqli->error );
		$bandate = $mysqli->query( "SELECT DATE_ADD(CURDATE(),INTERVAL " . addslashes( abs ( (int) $_POST['banneduntil'] ) ) . " DAY)" )->fetch_array();
		
		//catch for year 2038 problem---DOES NOT WORK
		//due to a limitation in strtotime function... is 64-bit machine needed?
		if (strtotime($bandate[0])<strtotime("2038/01/19")){
			$mysqli->query("UPDATE users SET bandate=CURDATE(), banneduntil=date_add(CURDATE(),INTERVAL ".addslashes(abs((int) $_POST['banneduntil']))." DAY) WHERE id='" . $user['id'] . "'") or die($mysqli->error);
		}
		else {
			$mysqli->query("UPDATE users SET bandate=CURDATE(), banneduntil='2038-01-19' WHERE id='" . $user['id'] . "'") or die($mysqli->error);
		}
		$mysqli->query("UPDATE users SET banreason='".addslashes($_POST['banreason'])."' WHERE id='" . $user['id'] . "'") or die($mysqli->error);
	}
	echo "<meta http-equiv='Refresh' content='0; URL=.'>";
	exit();
}

//If the notification change form is submitted
if ( isset( $_POST['notificationchange'] ) ) {
	$boxes = $_POST['notifications'];
	//If at least one box is checked
	if( count( $boxes ) > 0 ) {
		//If both boxes are checked, enable all notifications
		if( count( $boxes ) > 1 )
			$setting = "all";
		else {
			//If only comment notifications are checked, disable reply notifications
			if ($boxes[0] == "comments" ) {
				$setting="noreplies";
			}
			//If only reply notifications are checked, disable comment notifications
			else if ( $boxes[0] == "replies" ) {
				$setting="nocomments";
			}
			//If an invalid value is found, disable all notifications
			else {
				$setting = "none";
			}
		}
	}
	//Otherwise, disable all notifications
	else {
		$setting = "none";
	}
	//Update notification settings in the database and redirect to the user's userpage
	$mysqli->query( "UPDATE users SET notifications='" . $setting . "' WHERE id='" . $user['id'] . "'") or die( $mysqli->error );
	die("<meta http-equiv='Refresh' content='0; URL=.");
}

?>
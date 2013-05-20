<?php
// If user isn't logged in, 403 'em
if ( empty( $_SESSION['SESS_MEMBER_ID'] ) ) {
	require_once( "errors/403.php" );
	exit();
}

// Test whether current user is admin and, if not, reject them brutally (403 'em)
if ( $cur_user['rank'] != "admin" && $cur_user['rank'] != "mod" ){
	require_once( "errors/403.php" );
}


// Some administration is done via "modes" (accessed via the URL)
// This doesn't include things like user preferences and creation editing, but there'll probs (totes) be a search for that

if ( isset( $_GET['mode'] ) ) {
	$mode = $_GET['mode'];
}
else {
	$mode = "table";
}

if ( isset( $_GET['action'] ) ) {
	$action = $_GET['action'];
}

if ( isset( $_GET['id'] ) ) {
	$id = $_GET['id'];
}

// If the designated mode is flags, get a whole bunch; otherwise, only get a few (like 10 or so)
if ( $mode != "flags") {
	$flags = $mysqli->query( "SELECT * FROM flags ORDER BY timestamp DESC LIMIT " . ADMIN_FLAGS );
}
else {
	$flags = $mysqli->query( "SELECT * FROM flags ORDER BY timestamp DESC LIMIT 500" );
}

// Get the number of flags to determine whether the link to see the rest of the flags should be shown
$flags_amount = $mysqli->query( "SELECT * FROM flags ORDER BY timestamp DESC" )->num_rows;

// Figure out what the page is going to do
switch ( $mode ) {
	case "flags":
		// If you're not deleting a flag but the mode is flags, show the page with a whole bunch of flags
		if ( $action != "delete" ) {
			require_once( "templates/adminflags_template.php" );
		}
		// If you're deleting a flag, then delete it
		else {
			if ( $mysqli->query("SELECT userid FROM flags WHERE id=" . $id) ) {
				$mysqli->query( "DELETE FROM flags WHERE id=" . $id );
			}
			die( "<meta http-equiv='Refresh' content='0; URL=javascript:history.back(1)'>" );
		}
	break;
	
	default:
		// Show the mods a different page than the admins with less stuff 'n' stuff
		if ( $cur_user['rank'] == "mod" ){
			require_once( "templates/mod_template.php" );
		}
		else if ( $cur_user['rank'] == "admin" ) {
			require_once( "templates/admin_template.php" );
		}
}

// If submit pressed on preferences, redirect to that user's preferences
if ( isset( $_POST['prefssubmit'] ) ) {
	$prefs_user = addslashes( $_POST['prefsusername'] );
	if ( empty( $prefs_user ) ){
		die( "Please enter a valid username." );
	}
	die( "<meta http-equiv='Refresh' content='0; URL=user/".$prefs_user."/preferences'>" );
}

// Same thing for viewing a certain user's messages
if ( isset($_POST['msgsubmit'] ) ) {
	$user_message_id = get_id_from_username( addslashes( $_POST['usernamemsg'] ), $mysqli );
	if ( $user_message_id == "invalidUser" ) {
		die( "Please enter a valid username." );
	}
	die( "<meta http-equiv='Refresh' content='0; URL=messages/" . $user_message_id . "'>" );
}

// Send an admin private message if it's clicked on the page
if ( isset( $_POST['adminmessagesubmit'] ) ) {
	$recipient_id = get_id_from_username( addslashes( $_POST['recipientusername'] ), $mysqli );
	
	// Determine whether the admin message should show as generic (no associated user) or not
	if ( $_POST['showuser'] == 1 ) {
		$admintype = "generic";
	}
	else {
		$admintype = "specific";
	}
	
	$mysqli->query( "INSERT INTO messages (recipientid,senderid,message,type,admintype) VALUES (" . $recipient_id . "," . $cur_user['id'] . ",'" . addslashes( $_POST['adminmessage'] ) . "','admin','".$admintype."')");
	die( "Your message has been sent." );
}
?>
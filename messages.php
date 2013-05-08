<?php
if( !isset( $cur_user ) ){
	die("<meta http-equiv='Refresh' content='0; URL=login.php'>");
}

if ( isset($_GET['uid'] ) && ( !strcspn( $_GET['uid'], "0123456789" ) ) && $cur_user['rank'] == "admin" ){
	$lresult = $mysqli->query( "SELECT * FROM users WHERE id=" . $_GET['uid'] );
	if (!$lresult){
		die( "Could not run query: " . $mysqli->error );
	}
	$cur_user = $lresult->fetch_array();
	if( !$cur_user ){
		require( BASE_DIRECTORY . "/errors/404.php" );
		die();
	}
	$visitinguser = $cur_user['username'];
}

if ( isset( $_GET["action"] ) && $_GET["action"] == "delete" && isset( $_GET["id"] ) ){
	if ( !strcspn($_GET['id'], "0123456789" ) ){
		$msginfo = $mysqli->query( "SELECT recipientid FROM messages WHERE id=" . (int) $_GET['id'] )->fetch_array();
	}
	if( $msginfo[0] == $cur_user['id'] || $userrank == "admin" ){
		if ( $userrank=="admin" ){
			$mysqli->query( "DELETE FROM messages WHERE id=" . $_GET['id'] ) or die( $mysqli->error );
		}
		else{
			$mysqli->query( "UPDATE messages SET viewed=2 WHERE id=" . $_GET['id'] );
		}
	}
	die( "<meta http-equiv='Refresh' content='0; URL=javascript:history.back(1)'>" );
}

$notifications = $mysqli->query("SELECT * FROM messages WHERE recipientid=".$cur_user['id']." AND type='notification' ORDER BY timestamp DESC");
$admin = $mysqli->query("SELECT * FROM messages WHERE recipientid=".$cur_user['id']." AND type='admin' ORDER BY timestamp DESC");
$private = $mysqli->query("SELECT * FROM messages WHERE recipientid=".$cur_user['id']." AND type='pm' ORDER BY timestamp DESC");

if( !empty( $private ) && $private->num_rows>0 ){
	for ( $i = 0; $i < $private->num_rows; $i++ ){
		$message = $private->fetch_array();
		//if a message is unread, mark as read
		if ( $message['viewed'] == 0 && $_SESSION['SESS_MEMBER_ID'] == $cur_user['id'] ){
			$mysqli->query( "UPDATE messages SET viewed=1 WHERE id=" . $message['id'] );
		}
	}
}
if( !empty( $notifications ) && $notifications->num_rows > 0 ){
	for ( $i=0; $i < $notifications->num_rows; $i++ ){
		$message = $notifications->fetch_array();
		//if a message is unread, mark as read
		if ( $message['viewed'] == 0 && $_SESSION['SESS_MEMBER_ID'] == $cur_user['id'] ){
			$mysqli->query( "UPDATE messages SET viewed=1 WHERE id=" . $message['id'] );
		}
	}
}
if( !empty( $admin ) && $admin->num_rows > 0 ){
	for ($i = 0; $i < $admin->num_rows; $i++){
		$message = $admin->fetch_array();
		//if a message is unread, mark as read
		if ( $message['viewed'] == 0 && $_SESSION['SESS_MEMBER_ID'] == $cur_user['id'] ){
			$mysqli->query( "UPDATE messages SET viewed=1 WHERE id=" . $message['id'] );
		}
	}
}

require_once("templates/messages_template.php");

if ( isset( $_POST['reply'] ) ){
	$private->data_seek(0);
	while ( $pmreplydata = $private->fetch_array() ){
		if ( isset( $_POST['msgsubmit' . $pmreplydata[0]] ) && strlen( trim( $_POST['msgsubmit' . $pmreplydata[0]] ) ) > 0 ){
			$mysqli->query( "INSERT INTO messages (recipientid,senderid,message,type) VALUES (" . $pmreplydata[2] . ", " . $cur_user['id'] . ", '" . addslashes( $_POST['msgbody' . $pmreplydata[0]] ) . "', 'pm')" ) or die($mysqli->error);
			die( "<meta http-equiv='Refresh' content='0; URL=javascript:history.back(1)'>" );
		}
	}
}
?>
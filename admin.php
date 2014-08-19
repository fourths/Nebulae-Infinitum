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
?>

<!DOCTYPE html>
<html>
	<head>
		<title>
			Administration / Flags | <?php echo SITE_NAME; ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="../include/style.css" media="screen" />
	</head>
	<body>
		<?php
		require_once( "templates/header.php" ); 
		?>
		<div class="container">
			<h1>Recent flags</h1>
			<div class="adminblock">
				<div class="flagblock">
					<table>
					<?php
					// Exactly like in admin_template except it doesn't cut off the names ever
					if ( isset( $flags ) && ( (int) $flags->fetch_array() ) != 0) {
						$flags->data_seek( 0 );
						while ( $flag = $flags->fetch_array() ) {
							switch ( $flag['type'] ) {
							
								case "creation":
									$item_name = $mysqli->query( "SELECT name FROM creations WHERE id=" . $flag['parentid'] )->fetch_array();
									
									if ( $item_name[0] == "") {
										$item_name[0] = '<span style="color:#E00">Deleted creation</span>';
									}
									else {
										$item_name[0] = '<a class="td" href="creation/' . $flag['parentid'] . '">' . trim( strip_bbcode( $item_name[0] ) ) . '</a>';
									}
								break;
								
								case "comment":
									$item_name = $mysqli->query( "SELECT comment FROM comments WHERE id=" . $flag['parentid'] )->fetch_array();
									
									if ( strlen( $item_name[0] ) > $display_chars ) {
										$addendum = "&hellip;";
									}
									
									if ( $item_name[0] == "") {
										$item_name[0] = '<span style="color:#E00">Deleted comment</span>';
									}
									else {
										$item_name[0] = '<a class="td" href="creation/' . get_creation_from_comment( $flag['parentid'], $mysqli ) . '#' . $flag['parentid'] . '">' . trim( strip_bbcode( $item_name[0] ) ) . '</a>';
									}
								break;
								
								case "message":
									$item_name = $mysqli->query( "SELECT message FROM messages WHERE id=" . $flag['parentid'] )->fetch_array();
									
									if ( strlen( $item_name[0] ) > $display_chars ) {
										$addendum = "&hellip;";
									}
									
									if ( $item_name[0] == "") {
										$item_name[0] = '<span style="color:#E00">Deleted message</span>';
									}
									else {
										$item_name[0] = '<a class="td" href="messages/' . get_sender_from_message( $flag['parentid'], $mysqli ) . '#' . $flag['parentid'] . '">' . trim( strip_bbcode( $item_name[0]) ) . '</a>';
									}
								break;
							}
							echo '<tr id="' . $flag['id']. '">
								<td class="' . $flag['type'] . '" style="width:200px;">' . $item_name[0] . '</td>
								<td class="' . $flag['type'] . '" style="width:80px;"><a class="td" href="user/' . get_username_from_id( $flag['userid'], $mysqli ) . '">' . get_username_from_id( $flag['userid'], $mysqli ) . '</a></td>
								<td class="' . $flag['type'] . '" style="width:80px;">' . date( "m/d/Y", strtotime( $flag['timestamp'] ) ) . '</td>
								<td class="' . $flag['type'] . '" style="width:400px;">' . $flag['content'] . '<a class="deletebutton" href="admin/flags/' . $flag['id'] . '/delete"></a></td>
							</tr>';
						}
					}
					?>
					
					</table>
				</div>
				<a href="../admin" class="td">&lt; Back</a>
			</div>
		</div>
	</body>
</html>

<?php
		}
		// If you're deleting a flag, then delete it
		else {
			if ( $mysqli->query("SELECT userid FROM flags WHERE id=" . $id) ) {
				$mysqli->query( "DELETE FROM flags WHERE id=" . $id );
			}
			header( "Location: ". BASE_URL . "/admin" );
			exit();
		}
	break;
	
	default:
		// Show the mods a different page than the admins with less stuff 'n' stuff
		if ( $cur_user['rank'] == "mod" ){
			require_once( "templates/mod_template.php" );
		}
		else if ( $cur_user['rank'] == "admin" ) {
?>
			
<!DOCTYPE html>
<html>
	<head>
		<title>
			Administration | <?php echo SITE_NAME; ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="include/style.css" media="screen" />
	</head>

	<body>
	<?php
	require_once( "templates/header.php" );
	?>
		<div class="container">
			<h1>Administration</h1>
			<div class="adminblock">
				<h2 style="margin:0px;">Recent flags</h2>
				<div class="flagblock">
					<table>
						<?php
						// Set the amount of characters for it to display in each box (and it will cut off w/ ellipses if it's longer)
						$display_chars = 100;
						
						//Sequentially display the flags in the table
						if ( isset( $flags ) && ( (int) $flags->fetch_array() ) != 0) {
							$flags->data_seek( 0 );
							while ( $flag = $flags->fetch_array() ) {
								unset( $addendum );
								switch ( $flag['type'] ) {
									// Determine the type of the flag
									case "creation":
										$item_name = $mysqli->query( "SELECT name FROM creations WHERE id=".$flag['parentid'] )->fetch_array();
										
										// If the flag name is too long, use an ellipses to cut it off
										if ( strlen( $item_name[0] ) > $display_chars ) {
											$addendum = "&hellip;";
										}
										
										// If the item name isn't set, just assume that the item is deleted
										if ( $item_name[0] == "") {
											$item_name[0] = '<span style="color:#E00">Deleted creation</span>';
										}
										else {
											$item_name[0] = '<a class="td" href="creation/' . $flag['parentid'] . '">' . trim( substr( strip_bbcode( $item_name[0] ), 0, $display_chars ) ) . $addendum . '</a>';
										}
									break;
									
									case "comment":
										$item_name = $mysqli->query( "SELECT comment FROM comments WHERE id=" . $flag['parentid'] )->fetch_array();
										
										if ( strlen( $item_name[0] ) > $display_chars ) {
											$addendum = "&hellip;";
										}
										
										if ( $item_name[0] == "") {
											$item_name[0] = '<span style="color:#E00">Deleted comment</span>';
										}
										else {
											$item_name[0] = '<a class="td" href="creation/' . get_creation_from_comment( $flag['parentid'], $mysqli ) . '#' . $flag['parentid'] . '">' . trim( substr( strip_bbcode( $item_name[0] ), 0, $display_chars ) ) . $addendum . '</a>';
										}
									break;
									
									case "message":
										$item_name = $mysqli->query( "SELECT message FROM messages WHERE id=" . $flag['parentid'] )->fetch_array();
										
										if ( strlen( $item_name[0] ) > $display_chars ) {
											$addendum = "&hellip;";
										}
										
										if ( $item_name[0] == "") {
											$item_name[0] = '<span style="color:#E00">Deleted message</span>';
										}
										else {
											$item_name[0] = '<a class="td" href="messages/' . get_sender_from_message( $flag['parentid'], $mysqli ) . '#' . $flag['parentid'] . '">' . trim( substr( strip_bbcode( $item_name[0]), 0, $display_chars ) ) . $addendum . '</a>';
										}
									break;
								}
								echo '<tr id="' . $flag['id']. '">
									<td class="' . $flag['type'] . '" style="width:200px;">' . $item_name[0] . '</td>
									<td class="' . $flag['type'] . '" style="width:80px;"><a class="td" href="user/' . get_username_from_id( $flag['userid'], $mysqli ) . '">' . get_username_from_id( $flag['userid'], $mysqli ) . '</a></td>
									<td class="' . $flag['type'] . '" style="width:80px;">' . date( "m/d/Y", strtotime( $flag['timestamp'] ) ) . '</td>
									<td class="' . $flag['type'] . '" style="width:400px;">' . $flag['content'] . '<a class="deletebutton" href="admin/flags/' . $flag['id'] . '/delete"></a></td>
								</tr>';
							}
						}
						?>
					</table>
				</div>
				<?php
				// Display a link for the big flags page if there are more flags than will display here
				if ( $flags_amount > ADMIN_FLAGS ) {
					echo '<a href="?mode=flags" class="td">Show more &gt;</a>';
				}
				?>
			</div>
			<div class="adminblock">
				<h2 style="margin:0px;">User preferences</h2>
				Enter a username and press submit to go to their preferences page.
				<form method="post">
					<input type="text" name="prefsusername" placeholder="Username"/>
					<input type="submit" name="prefssubmit" value="Submit"/>
				</form>
			</div>
			<div class="adminblock">
				<h2 style="margin:0px;">User message history</h2>
				Enter a username and press submit to view their entire saved message history.
				<form method="post">
					<input type="text" name="usernamemsg" placeholder="Username"/>
					<input type="submit" name="msgsubmit" value="Submit"/>
				</form>
			</div>
			<div class="adminblock">
				<h2 style="margin:0px;">Admin messages</h2>
				Use this form to send administrator messages.
				<form method="post">
					<input type="text" name="recipientusername" placeholder="Recipient username"/><br/>
					
					<textarea name="adminmessage" placeholder="Message" style="height:100px;width:200px;max-width:500px;max-height:300px;margin-left:2px;"></textarea><br/>
					
					<input type="checkbox" name="showuser" value="1"/>
					Don't show sender username<br/>
					
					<input type="submit" name="adminmessagesubmit" value="Submit"/>
				</form>
			</div>
		</div>
		</div>
	</body>
</html>	

<?php
		}
}

// If submit pressed on preferences, redirect to that user's preferences
if ( isset( $_POST['prefssubmit'] ) ) {
	$prefs_user = addslashes( $_POST['prefsusername'] );
	if ( empty( $prefs_user ) ){
		die( "Please enter a valid username." );
	}
	header( "Location: ". BASE_URL . "/user/" . $prefs_user. "/preferences"	);
}

// Same thing for viewing a certain user's messages
if ( isset( $_POST['msgsubmit'] ) ) {
	$user_message_id = get_id_from_username( addslashes( $_POST['usernamemsg'] ), $mysqli );
	if ( $user_message_id == "invalidUser" ) {
		die( "Please enter a valid username." );
	}
	header( "Location: ". BASE_URL . "messages/" . $user_message_id );
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
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
	require_once( "header.php" );
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
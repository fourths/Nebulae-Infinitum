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
		require_once( "header.php" ); 
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
									$item_name = $mysqli->query( "SELECT name FROM creations WHERE id=".$flag['parentid'] )->fetch_array();
									
									if ( $item_name[0] == "") {
										$item_name[0] = '<span style="color:#E00">Deleted creation</span>';
									}
									else {
										$item_name[0] = '<a class="td" href="creation/' . $flag['parentid'] . '">' . trim( substr( strip_bbcode( $item_name[0] ), 0, $display_chars ) ) . '</a>';
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
										$item_name[0] = '<a class="td" href="creation/' . get_creation_from_comment( $flag['parentid'], $mysqli ) . '#' . $flag['parentid'] . '">' . trim( substr( strip_bbcode( $item_name[0] ), 0, $display_chars ) ) . '</a>';
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
										$item_name[0] = '<a class="td" href="messages/' . get_sender_from_message( $flag['parentid'], $mysqli ) . '#' . $flag['parentid'] . '">' . trim( substr( strip_bbcode( $item_name[0]), 0, $display_chars ) ) . '</a>';
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
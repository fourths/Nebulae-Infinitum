<?php
//If the current user is logged in
if ( !empty( $_SESSION['SESS_MEMBER_ID'] ) ) {
	//Determine whether the user has unread messages and, if so, get the number of unread messages they have
	if( $mysqli->query( "SELECT id FROM messages WHERE viewed=0 AND recipientid=" . $cur_user['id'] ) == false ) {
		$msg = 0;
	}
	else {
		$msg = $mysqli->query( "SELECT id FROM messages WHERE viewed=0 AND recipientid=" . $cur_user['id'] )->num_rows;
	}
}
?>
<div class="header">
	<a class="headtext" href="<?php echo BASE_URL; ?>/">
		<?php echo strtolower( SITE_NAME );?>
	</a>
	<br/>
	<div class="headlinks">
		<a class="head" href="<?php echo BASE_URL; ?>/">home</a> &bull; 
		<a class="head" href="<?php echo BASE_URL; ?>/creations">creations</a> &bull; 
		<a class="head" href="<? echo BASE_URL; ?>/about/">about</a> &bull; 
		<a class="head" href="<? echo BASE_URL; ?>/forums/">forums</a> 
		<?php
		//If the current user is an admin or a mod, show the header link to the admin panel
		if ( $cur_user['rank']=="admin" || $cur_user['rank']=="mod" ) {
			echo ' &bull; <a class="head" href="' . BASE_URL . '/admin">admin</a>';
		}
		if ( BASE_FOLDER != "" ){
			$return_url = str_replace( "/" . BASE_FOLDER, "", $_SERVER['REQUEST_URI'] ); //use if there's a base folder
		}
		else {
			$return_url = $_SERVER['REQUEST_URI']; // use if the base is root
		}
		
		
		//If the current user is logged in, show the header links to their userpage (and messages) as well as the upload & logout pages
		if ( isset( $_SESSION['SESS_MEMBER_ID'] ) || ( trim( $_SESSION['SESS_MEMBER_ID'] ) != '' ) ) {
			echo '
			<div style="padding-top:5px;">logged in as <a class="head" href="' . BASE_URL . '/user/' . $cur_user['username'] . '">' . $cur_user['username'] . '</a> (<a href="' . BASE_URL . '/messages" class="head">&#9993;</a>) &bull;
			<a class="head" href="' . BASE_URL . '/upload">upload</a> &bull;
			<a class="head" href="' . BASE_URL . '/logout?returnto=' . $return_url . '">logout</a></div>';
		}
		//Otherwise, show the header link to login
		else{
			if ( $url_array[1] == "login" ) {
				echo '&bull; <a class="head" href="' . BASE_URL . '/login">login</a>';
			}
			else {
				echo '&bull; <a class="head" href="' . BASE_URL . '/login?returnto=' . $return_url . '">login</a>';
			}
		}
		?>
	</div>
</div>
<?php
//If there is at least one unread message, display the message alert box
if ( $msg > 0 ) {
	echo '<div class="msgalert"><a href="' . BASE_URL . '/messages" class="msgalertlink">You have ' . $msg. ' new message';
	if ( $msg > 1 ){
		echo 's';
	}
	echo '.</a></div>';
}
?>
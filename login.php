<?php
//Dump the "return to" parameter into a variable
if ( isset($_GET['returnto'] ) ){
	$return_to = BASE_URL . $_GET['returnto'];
}

//Initialise login/register page variable
$lr = "login";

//If the user is already logged in
if( isset( $_SESSION['SESS_MEMBER_ID'] ) || ( trim( $_SESSION['SESS_MEMBER_ID'] ) != '') ) {
	//If the action is set to "logout", destroy the session information and return to either the "return to" parameter or the homepage (by default)
	if ( isset( $_GET['action'] ) && $_GET['action'] == "logout") {
		session_destroy();
		if( isset( $return_to ) ){
			header( "Location: " . $return_to );
		}
		else{
			header( "Location: ." );
		}
		exit();
	}
	//If the action is not "logout", redirect the user to their userpage
	header( "Location: user/" . get_username_from_id( $_SESSION['SESS_MEMBER_ID'], $mysqli ) );
	exit();
}


//Set the action to "register" if specified in the url
if (isset($_GET['action']) && $_GET['action'] == "register") {
	$lr = "register";
}

//Display login or register page
if ( $lr == "login" || $lr == "register" ){
	require_once( "templates/" . $lr . "_template.php" );
}

//Get information from login form when submitted
if ( isset($_POST['submit'] ) ) {
	$result = $mysqli->query("SELECT * FROM users WHERE username='".$_POST['user']."' AND password='".nebulae_hash($_POST['pass'])."'") or die( $mysqli->error );
	if ( $result->num_rows != 1 ) {
		die( "Invalid username or password." );
	}
	session_regenerate_id();
    $user_info = $result->fetch_assoc();
    $_SESSION['SESS_MEMBER_ID'] = $user_info['id'];
    session_write_close();
	if( isset( $return_to ) ){
		header( "Location: " . $return_to );
	}
    else{
		header( "Location: user/" . get_username_from_id( $_SESSION['SESS_MEMBER_ID'], $mysqli ) );
	}
    exit();
}

//Get information from register form when submitted
if ( isset( $_POST['rsubmit'] ) ) {

	//Replace any spaces in the username with underscores
	$_POST['user'] = str_replace( " ", "_", $_POST['user'] );
	
	//If no username is entered, die
	if ( empty( $_POST['user'] ) ){
		die( "Please enter a username." );
	}
	
	//If the entered username is fewer than the minimum, die
	if ( strlen( $_POST['user'] ) < USERNAME_MIN ) {
		die( "Please enter a username at least " . USERNAME_MIN . " characters long.");
	}
	
	//If the entered username is greater than the maximum, die
	if ( strlen( $_POST['user'] ) > USERNAME_MAX ) {
		die( "Please enter a username fewer than " . USERNAME_MAX . " characters long.");
	}
	
	//If the username contains characters that aren't allowed, die
	if ( strcspn( $_POST['user'], USERNAME_STRING ) > 0 ) {
		die( "Only alphanumeric characters, dashes, and underscores are allowed in usernames." );
	}
	
	//If the username already exists in the database, die
	if ( $mysqli->query( "SELECT * FROM users WHERE username='" . strtolower( $_POST['user']."'") )->num_rows > 0 ) {
		die( "That username is already in use." );
	}
	
	//If no password is entered, die
	if ( empty( $_POST['pass'] ) || empty( $_POST['cpass'] ) ) {
		die( "Please enter a password." );
	}
	
	//If entered password is fewer than the minimum, die
	if ( strlen( $_POST['pass'] ) < PASSWORD_MIN ) {
		die( "Please enter a password at least " . PASSWORD_MIN . " characters long." );
	}
	
	//If entered password is fewer than the minimum, die
	if ( strlen( $_POST['pass'] ) > PASSWORD_MAX ) {
		die( "Please enter a password at least " . PASSWORD_MAX . " characters long." );
	}
	
	//If the two passwords do not match, die
	if ( nebulae_hash( $_POST['pass'] ) != nebulae_hash( $_POST['cpass'] ) ) {
		die( "The passwords do not match." );
	}
	
	//If no email address has been given, die
	if ( empty( $_POST['email'] ) ) {
		die("Please enter an email address.");
	}
	
	//Get the current highest user id and calculate the new user's id
	$max = $mysqli->query( "SELECT MAX(id) FROM users" )->fetch_array() or die( $mysqli->error );
	$newid = $max[0] + 1;
	
	//Get the ip address of the client
	$userip = $_SERVER['REMOTE_ADDR'];
	
	//Insert the id, username, hashed password, email address, and client's ip into the database
	$mysqli->query( "INSERT INTO users (id,username,password,email,registerip) VALUES (" . $newid . ",'" . addslashes( $_POST['user'] ) . "','" . nebulae_hash( $_POST['pass'] ) . "','" . $_POST['email']."','" . $userip . "')" );
	
	//Inserting optional values: age, gender, location
	if( !empty( $_POST['age'] ) ) {
		$mysqli->query( "UPDATE users SET age='" . addslashes( $_POST['age'] ) . "' WHERE id=" . $newid ) or die( $mysqli->error );
	}
	if( !empty( $_POST['gender'] ) ) {
		$mysqli->query( "UPDATE users SET gender='" . addslashes( $_POST['gender'] ) . "' WHERE id=" . $newid ) or die( $mysqli->error );
	}
	if( !empty( $_POST['location'] ) ) {
		$mysqli->query( "UPDATE users SET location='" . addslashes( $_POST['location'] ) . "' WHERE id=" . $newid ) or die( $mysqli->error );
	}
	
	//Get information about new user from database and, if it doesn't appear as expected, throw an error.
	$result = $mysqli->query( "SELECT * FROM users WHERE username='" . $_POST['user'] . "'" ) or die( $mysqli->error );
	if ( $result->num_rows != 1 ){
		die( "<br/>An error occured. Please try again." );
	}
	
	//Generate a new user session
	session_regenerate_id();
	
	//Dump the new user info into an array
    $user_info = $result->fetch_assoc();
	
	//Set the member id for the session to that of the new user
    $_SESSION['SESS_MEMBER_ID'] = $user_info['id'];
	
	//End user session and save session data
    session_write_close();
	
	//If the user came from another page, return them to that page
    if( isset( $return_to ) ) {
		header( "location: " . $return_to );
	}
	
	//Otherwise, redirect them to their userpage
    header( "location: user/" . get_username_from_id( $_SESSION['SESS_MEMBER_ID'] ) );
    exit();
}
?>
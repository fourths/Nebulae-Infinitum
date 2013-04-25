<?php
/*
Nebulae Infinitum dispatcher file
Used to display pages; all URLs redirect here to be processed based on the URL entered
*/

require_once( "config/config.php" );
error_reporting( E_ALL ^ E_NOTICE ); 
session_start();

//Connect to database
$mysqli = new mysqli( MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE );
if ( $mysqli->connect_errno ) {
	die( "Could not connect to database: " . $mysqli->connect_error );
}

//Get current user info from database
if ( !empty( $_SESSION['SESS_MEMBER_ID'] ) ){
	$cur_user_query = $mysqli->query( "SELECT * FROM users WHERE id=" . $_SESSION['SESS_MEMBER_ID'] );
	if ( $mysqli->errno ) {
		die( "Could not read user data from database: " . $mysqli->error );
	}
	$cur_user = $cur_user_query->fetch_array();
	unset($cur_user_query);
}

//Get URL
$url = str_replace( "/" . BASE_FOLDER, "", $_SERVER['REQUEST_URI'] );
$url_array = explode( "/", $url );

//Determine which page to load based on the URL
if ( $url != "/"){
	switch( $url_array[1] ){
		case "user":
		
		break;
		
		case "creation":
		
		break;
		
		case "creations":
		
		break;
		
		case "comment":
		
		break;
		
		case "admin":
		
		break;
		
		case "message":
		
		break;
		
		case "messages":
		
		break;
		
		case "tools":
			if( isset( $url_array[2] ) ){
				switch( $url_array[2] ){
					case "api":
						if( file_exists( "api/" . $url_array[3] . ".php" ) ){
							require_once( "api/" . $url_array[3] . ".php" );
						}
						else {
							require_once( "errors/404.php" );
						}
					break;
					
					case "encrypt":
						require_once( "encrypt.php" );
					break;
				}
			}
			else{
				require_once( "about/tools.php" );
			}
		break;
		
		case "upload":
		
		break;
		
		case "about":
		
		break;
		
		case "login":
		
		break;
		
		case "logout":
		
		break;
		
		case "register":
		
		break;
		
		default:
			require_once("errors/404.php");
	}
}
else {
	require_once("index.php");
}

?>
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

if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}

else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

//Get URL and explode into pieces for determination of desired page
$url = str_replace( "/" . BASE_FOLDER, "", $_SERVER['REQUEST_URI'] );
$url_array = explode( "/", $url );

//Determine which page to load based on the URL
if ( $url != "/"){
	switch( $url_array[1] ){
		// If the URL starts with user (e.g. example.com/user/username), do this
		case "user":
			// Escape the URL for added security
			$escaped_url_chunk[0] = addslashes( $url_array[2] );
			// If there's something after the user part of the URL (e.g. username in the URL above)
			if( isset( $escaped_url_chunk[0] ) && $escaped_url_chunk[0] != ""){
				// Set the ID of the user the page should display information from based on the username in the URL
				$_GET['id'] = get_id_from_username( $escaped_url_chunk[0], $mysqli );
				// Escape the URL for added security
				$escaped_url_chunk[1] = addslashes( $url_array[3] );
				// If there's something after the username part (e.g. example.com/user/username/action)...
				if( isset( $escaped_url_chunk[1] ) && $escaped_url_chunk[1] != ""){
					switch( $url_array[3] ){
						// If the URL is of the format (e.g. example.com/user/username/preferences), display their preferences page rather than the userpage
						// This is done to keep URLs organised & structured
						case "preferences":
							require_once( "pref.php" );
						break;
						
						// If there's something besides "preferences" there (or other actions I may add), spit out a 404 error
						default:
							require_once( "errors/404.php" );
					}
				}
				// Otherwise, if the URL is simply like example.com/user/username...
				else{
					// If the page ends in a / (e.g. example.com/user/username/), redirect them to the page w/out the slash so it doesn't mess up the CSS
					if( substr( $_SERVER['REQUEST_URI'], strlen( $escaped_url_chunk[1] ) - 1, 1 ) == "/"){
						header( "Location: ../" . $escaped_url_chunk[0] );
					}
					// Otherwise, just output the regular userpage
					else{
						require_once( "user.php" );
					}
				}
			}
			// If there's nothing after the user part (e.g. example.com/user/), give a 404 error
			// There may in the future be a userlist here
			else{
				require_once( "errors/404.php" );
			}
		break;
		
		case "creation":
		
		break;
		
		case "creations":
			if ( isset( $url_array[2] ) && $url_array[2] != "" ){
				if ( !isset( $url_array[3] ) || $url_array[3] == "" ){
					$url_array[3] = 1;
				}
				if ( isset ( $url_array[4] ) ){
					$_GET['id'] = $url_array[3];
					$_GET['action'] = $url_array[4];
					$_GET['mode'] = "action";
				}
				else{
					$_GET['page'] = $url_array[3];
					$_GET['mode'] = $url_array[2];
				}
				require_once( "creations.php" );
			}
			else{
				header( "Location: " . BASE_URL . "/creations/newest/1");
			}
		break;
		
		case "comment":
			if( isset( $url_array[2] ) ){
				if( $url_array[2] == "" ){
					require_once( "errors/404.php" );
					break;
				}
				$comment_data = $mysqli->query("SELECT * FROM comments WHERE id = " . addslashes($url_array[2]) );
				if( isset( $comment_data ) ){
					if( $comment["status"] == "censored" ){
						if( $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod"){
							header( "Location: creation/" . $comment['creationid'] . "#". $comment['id'] );
						}
						else{
							require_once( "errors/403.php" );
						}
					}
				}
				else{
					require_once( "errors/404.php" );
				}
			}
			else{
				require_once( "errors/404.php" );
			}
		break;
		
		case "admin":
			if( isset( $url_array[2] ) ){
				switch( $url_array[2] ){
					case "flags":
						$_GET['mode'] = "flags";
						if( isset( $url_array[4] ) ){
							switch( $url_array[4] ){
								case "delete":
									$_GET['action'] = "delete";
									$_GET['id'] = addslashes($url_array[3]);
								
								default:
									require_once( "admin.php");
							}
						}
						else{
							require_once( "admin.php");
						}
					break;
					
					default:
						header( "Location: ../admin" );
				}
			}
			else{
				require_once( "admin.php" );
			}
		
		break;
		
		case "message":
			if( isset( $url_array[3] ) ){
				switch( $url_array[3] ){
					case "delete":
						if( isset( $url_array[2] ) ){
							$_GET['id'] = $url_array[2];
							$_GET['action'] = "delete";
							require_once( "messages.php" );
						}
						else{
							require_once( "errors/404.php" );
						}
					break;
					
					case "flag":
						if( isset( $url_array[2] ) ){
							$_GET['id'] = $url_array[2];
							$_GET['type'] = "message";
							require_once( "flag.php" );
						}
						else{
							require_once( "errors/404.php" );
						}
					break;
					
					default:
						require_once( "errors/404.php" );
				}
			}
			else{
				require_once( "errors/404.php");
			}
		break;
		
		case "messages":
			if( substr( $_SERVER['REQUEST_URI'], strlen( $_SERVER['REQUEST_URI'] ) - 1, 1 ) == "/" ){
				if( BASE_FOLDER != ""){
					header( "Location: ../" . str_replace( "/" . BASE_FOLDER . "/", "", substr( $_SERVER['REQUEST_URI'], 0, strlen( $_SERVER['REQUEST_URI'] ) - 1 ) ) );
				}
				else{
					header( "Location: /" . substr( $_SERVER['REQUEST_URI'], 0, strlen( $_SERVER['REQUEST_URI'] ) - 1 ) );
				}
			}
			else{
				if( isset( $url_array[2] ) ){
					$_GET['uid'] = $url_array[2];
				}
				require_once( "messages.php" );
			}
		break;
		
		case "tools":
			if( isset( $url_array[2] ) ){
				switch( $url_array[2] ){
					case "api":
						if(  file_exists( "api/" . $url_array[3] . ".php" ) ){
							require_once( "api/" . $url_array[3] . ".php" );
						}
						else {
							require_once( "errors/404.php" );
						}
					break;
					
					case "encrypt":
						require_once( "encrypt.php" );
					break;
					
					default:
						require_once( "errors/404.php" );
				}
			}
			else{
				require_once( "about/tools.php" );
			}
		break;
		
		case "upload":
			require_once( "upload.php" );
		break;
		
		case "about":
			if ( isset ( $url_array[2] ) && $url_array[2] != "" ){
				if( file_exists( "info/" . addslashes( $url_array[2] ) . ".php" ) ){
					require_once( "info/" . addslashes( $url_array[2] ) . ".php" );
				}
			}
			else{
				require_once( "info/index.php" );
			}
		break;
		
		case "login":
			require_once( "login.php" );
		break;
		
		case "login?returnto=":
			$return_to = "";
			for ( $i = 2; $i < count( $url_array ); $i++ ){
				$return_to .= "/".$url_array[$i];
			}
			$_GET['returnto'] = "/" . BASE_FOLDER . $return_to;
			require_once( "login.php" );
		break;
		
		case "logout":
			$_GET['action'] = "logout";
			require_once( "login.php" );
		break;
		
		case "logout?returnto=":
			$return_to = "";
			for ( $i = 2; $i < count( $url_array ); $i++ ){
				$return_to .= "/".$url_array[$i];
			}
			$_GET['returnto'] = "/" . BASE_FOLDER . $return_to;
			$_GET['action'] = "logout";
			require_once( "login.php" );
		break;
		
		case "register":
			$_GET['action'] = "register";
			require_once( "login.php" );
		break;
		
		case "register?returnto=":
			$return_to = "";
			for ( $i = 2; $i < count( $url_array ); $i++ ){
				$return_to .= "/".$url_array[$i];
			}
			$_GET['returnto'] = "/" . BASE_FOLDER . $return_to;
			$_GET['action'] = "register";
			require_once( "login.php" );
		break;
		
		case "include":
			if( isset( $url_array[2] ) ){
				switch( $url_array[2] ){
					case "style.css":
						require_once( "templates/style.php" );
					break;
					
					default:
						require_once( "errors/403.php" );
				}
			}
			else{
				require_once( "errors/403.php" );
			}
		break;
		
		case "data":
			if( isset( $url_array[2] ) ){
				if( $url_array[2] == "errors" ){
					$extension = explode( ".", addslashes( $url_array[3] ) );
					if( file_exists( "errors/" . addslashes( $url_array[3] ) ) && $extension[1] == "png" ){
						header( "Content-type: image/png" );
						echo file_get_contents( "errors/" .  $url_array[3] );
					}
				}
				else{
					$data_path = "";
					for ( $i = 2; $i < count( $url_array ); $i++ ){
						$data_path .= "/".$url_array[$i];
					}
					$extension = explode( ".", addslashes( $data_path ) );
					if( file_exists( "data" . addslashes( $data_path ) ) ){
						$go = true;
						switch( $extension[1] ){
							case "png":
								header( "Content-type: image/gif" );
							break;
							
							case "gif":
								header( "Content-type: image/gif" );
							break;
							
							case "jpg":
								header( "Content-type: image/jpeg" );
							break;
							
							case "swf":
								header( "Content-type: application/x-shockwave-flash" );
							break;
							
							case "zip":
							case "sb":
							case "sb2":
								header( "Content-type: application/octet-stream" );
							break;
							
							case "mp3":
								header( "Content-type: audio/mpeg" );
							break;
							
							case "txt":
								header( "Content-type: text/plain" );
							break;
							
							case "ttf":
								header( "Content-type: application/x-font-ttf" );
							break;
							
							case "js":
								header( "Content-type: application/javascript" );
							break;
							
							default:
								$go = false;
						}
						if ( $go == true ){
							echo file_get_contents( "data" .  $data_path );
						}
					}
					
					else{
						require_once( "errors/404.php" );
					}
				}
			}
			else{
				require_once( "errors/404.php" );
			}
		break;
		
		default:
			require_once( "errors/404.php" );
	}
}
else {
	require_once( "index.php" );
}

?>
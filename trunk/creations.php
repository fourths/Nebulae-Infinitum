<?php
$mode = "newest";
$action = "";
$next = false;
$previous = false;
$page = 1;
if ( $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod" ){
	$admin=true;
}

if ( isset( $_GET['mode'] ) ){
	$mode = $_GET['mode'];
}

if ( isset( $_GET['page'] ) ){
	$page = (int) $_GET['page'];
}

if ( isset( $_GET['action'] ) ){
	$action = $_GET['action'];
}

if( isset( $_GET['id'] ) ){
	$id = $_GET['id'];
}

//load different types of creations based on mode (except for action, for performing actions which won't load the page at all)
switch ( $mode ){
	case "action":
		if ( $admin ){
			$id_test = $mysqli->query( "SELECT name FROM creations WHERE id='$id'" ) or die( $mysqli->error );
			if ( !$idtest ){
				switch ( $action ){
					case "delete":
						$mysqli->query( "UPDATE creations SET hidden='deleted' WHERE id='$id'" ) or die( $mysqli->error );
						die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
					case "hide":
						$mysqli->query( "UPDATE creations SET hidden='byowner' WHERE id='$id'" ) or die( $mysqli->error );
						die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
					case "censor":
						$mysqli->query( "UPDATE creations SET hidden='censored' WHERE id='$id'" ) or die( $mysqli->error );
						die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
					default:
						die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
				}
			}
		}
		//intentional non-breaking; if invalid creation, go on to default
	case "views":
		$typetext = "Top viewed";
		$creations = $mysqli->query( "SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY views DESC LIMIT " . ( $page * 10 - 10 ) . ",10" );
		if ( empty( $creations ) ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		if ( (int) $creations->fetch_array() == 0 ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		$creations->data_seek( 0 );
	break;
	case "rating":
		$typetext = "Top rated";
		$creations = $mysqli->query( "SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY rating DESC LIMIT " . ( $page * 10 - 10 ) . ",10");
		if ( empty( $creations ) ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		if( (int) $creations->fetch_array() == 0 ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		$creations->data_seek( 0 );
	break;
	case "random":
		$typetext = "Random";
		$creations = $mysqli->query( "SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY RAND() DESC LIMIT " . ( $page * 10 - 10 ) . ",10");
		if ( empty( $creations ) ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		if ( (int) $creations->fetch_array() == 0 ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		$creations->data_seek( 0 );
	break;
	case "favourites":
		$typetext = "Most favourited";
		$creations = $mysqli->query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY favourites DESC LIMIT " . ( $page * 10 - 10 ) . ",10");
		if ( empty( $creations ) ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		if ( (int) $creations->fetch_array() == 0 ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		$creations->data_seek( 0 );
	break;
	case "newest":
	default:
		$typetext = "Newest";
		$creations = $mysqli->query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY created DESC LIMIT " . ( $page * 10 - 10 ) . ",10");
		if ( empty( $creations ) ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		if ( (int) $creations->fetch_array() == 0 ){
			die( "<meta http-equiv='Refresh' content='0; URL=" . BASE_URL . "/creations/newest/1'>" );
		}
		$creations->data_seek( 0 );
}
if ( $page > 1 ){
	$previous = true;	
}

if ( $creations->num_rows == 10 ){
	if( $mysqli->query( "SELECT id FROM creations WHERE hidden='no' OR hidden='approved'" )->num_rows > ( $page * 10 ) ){
		$next=true;
	}
}

//display the page
require_once( "templates/creations_template.php" );

function displayCreations( $mysql, $cur_user, $admin, $mysqli ){
	if ( isset($mysql ) ){
		$rows = $mysql->num_rows;
		while ( $creation = $mysql->fetch_array() ){
			$user = $mysqli->query( "SELECT * FROM users WHERE id=" . $creation['ownerid'] )->fetch_array();
			
			echo '<div class="creationblock">';
			
			if ( file_exists( 'data/thumbs/' . $creation['id'] . '.png' ) ){
				echo '<a href="' . BASE_URL . '/creation/' . $creation['id'] . '"><img class="creationblockthumb" src="' . BASE_URL . '/data/thumbs/' . $creation['id'] . '.png"/></a>';
			}
			else{
				echo '<a href="' . BASE_URL . '/creation/' . $creation['id'] . '"><img class="creationblockthumb" src="' . BASE_URL . '/data/thumbs/default.png"/></a>';
			}
			
			if ( strlen( stripslashes( $creation['name'] ) ) > 20 ){
				$creationtitle = substr( stripslashes( $creation['name'] ), 0, 20 ) . "&hellip;";
			}
			else{
				$creationtitle = stripslashes( $creation['name'] );
			}
			
			echo '<div class="creationblockhead"><a href="' . BASE_URL . '/creation/' . $creation['id'] . '" class="creationblocktitle">' . $creationtitle . '</a>';
			
			echo '<div><a href="' . BASE_URL . '/user/'.$user['username'].'">' . $user['username'] . '</a>';
			if ($user['rank'] == "admin" || $user['rank'] == "mod"){
				echo '<a href="' . BASE_URL . '/info/staff.php" style="text-decoration:none;">' . STAFF_SYMBOL . '</a>';
			}
			echo '</div>';
			
			echo '<div>' . date( "F jS, Y", strtotime( $creation['created'] ) ) . '</div>';
			
			switch ( $creation['views'] ){
				case 1:
					$views = "1 view";
					break;
				case 0:
					$views = "No views";
					break;
				default:
					$views = $creation['views'] . " views";
			}
			echo '<div>' . $views . '</div>';
			
			if ( $creation['rating'] == 0 ){
				$rating = "No rating";
			}
			else{
				$rating = "Rated " . $creation['rating'];
			}
			
			echo '<div>' . $rating . '</div>';
			
			switch ( $creation['favourites'] ){
				case 1:
					$favourites = "1 favourite";
					break;
				case 0:
					$favourites = "No favourites";
					break;
				default:
					$favourites = $creation['favourites'] . " favourites";
			}
			echo '<div>' . $favourites . '</div></div>';
			
			if ( isset( $creation['descr'] ) && trim( $creation['descr'] ) != "" ){
				$creationdesc = str_replace( "<br />\n<br />\n", " ", bbcode_parse_description( stripslashes( $creation['descr'] ) ) );
				echo '<div class="creationblockdesc"><strong style="display:block">Description</strong>' . $creationdesc . '</div>';
			}
			if( isset( $creation['advisory'] ) && trim( $creation['advisory'] ) !="" ){
				if ( strlen( stripslashes( $creation['advisory'] ) ) > 100 ){
					$creationadv = substr( stripslashes( $creation['advisory'] ), 0, 100 ) . "&hellip;";
				}
				else{
					$creationadv = stripslashes( $creation['advisory'] );
				}
				echo '<div class="creationblockadv"><strong>Content advisory:</strong>' . $creationadv . '</div>';
			}
			if( $admin ){
				echo '<div style="position:absolute;top:0;right:0;"><a href="' . BASE_URL . '/creations/' . $creation['id'] . '/hide">H</a> <a href="' . BASE_URL . '/creations/' . $creation['id'] . '/censor">C</a> <a href="' . BASE_URL . '/creations/' . $creation['id'] . '/delete">D</a></div>';
			}
			echo '</div>';
		}
		echo '<div style="clear:both;"></div>';
	}
	else{
		echo 'An error occurred. Please try reloading the page or, if the error continues to occur, contact a <a href="' . BASE_URL . '/info/admin.php">site administrator</a>.';
	}
}
?>
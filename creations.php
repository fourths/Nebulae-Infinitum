<?php
//Include config
require_once("config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();

//Connect to database specified in config
$connection = mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS);
if (!$connection){die("Could not connect to database: " . mysql_error());}
mysql_select_db(MYSQL_DATABASE, $connection);

//Get current user info from database
if (!empty($_SESSION['SESS_MEMBER_ID'])){
	$lresult = mysql_query("SELECT * FROM users WHERE id = ".$_SESSION['SESS_MEMBER_ID']);
	if (!$lresult) {
		echo "Could not run query: " . mysql_error() and die;
	}
	$cur_user = mysql_fetch_array($lresult);
}
if ($cur_user['banstatus'] == "banned") {
	include_once("errors/ban.php");
	exit();
}
else if ($cur_user['banstatus'] == "deleted") {
	include_once("errors/delete.php");
	exit();
}

$mode="newest";
$action="";
$next=false;
$previous=false;
if($cur_user['rank']=="admin"||$cur_user['rank']=="mod") $admin=true;
if(isset($_GET['mode'])) $mode=$_GET['mode'];
if(isset($_GET['page'])) $page=(int)$_GET['page'];
else $page=1;
if(isset($_GET['action'])) $action=$_GET['action'];
if(isset($_GET['id'])) $id=$_GET['id'];
//load different types of creations based on mode (except for action, for performing actions which won't load the page at all)
switch($mode){
	case "action":
		if($admin){
			$id_test=mysql_query("SELECT name FROM creations WHERE id='$id'") or die(mysql_error());
			if(!$idtest){
				switch($action){
					case "delete":
						mysql_query("UPDATE creations SET hidden='deleted' WHERE id='$id'") or die(mysql_error());
						die("<meta http-equiv='Refresh' content='0; URL=creations.php'>");
					case "hide":
						mysql_query("UPDATE creations SET hidden='byowner' WHERE id='$id'") or die(mysql_error());
						die("<meta http-equiv='Refresh' content='0; URL=creations.php'>");
					case "censor":
						mysql_query("UPDATE creations SET hidden='censored' WHERE id='$id'") or die(mysql_error());
						die("<meta http-equiv='Refresh' content='0; URL=creations.php'>");
					default:
						die("<meta http-equiv='Refresh' content='0; URL=creations.php'>");
				}
			}
		}
		//intentional non-breaking; if invalid, go on to default
	case "views":
		$typetext="Top viewed";
		$creations=mysql_query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY views DESC LIMIT ".($page*10-10).",10");
		if((int) mysql_fetch_array($creations)==0){
			die("<meta http-equiv='Refresh' content='0; URL=creations.php?mode=newest'>");
		}
		mysql_data_seek($creations,0);
	break;
	case "rating":
		$typetext="Top rated";
		$creations=mysql_query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY rating DESC LIMIT ".($page*10-10).",10");
		if((int) mysql_fetch_array($creations)==0){
			die("<meta http-equiv='Refresh' content='0; URL=creations.php?mode=newest'>");
		}
		mysql_data_seek($creations,0);
	break;
	case "random":
		$typetext="Random";
		$creations=mysql_query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY RAND() DESC LIMIT ".($page*10-10).",10");
		if((int) mysql_fetch_array($creations)==0){
			die("<meta http-equiv='Refresh' content='0; URL=creations.php?mode=newest'>");
		}
		mysql_data_seek($creations,0);
	break;
	case "favourites":
		$typetext="Most favourited";
		$creations=mysql_query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY favourites DESC LIMIT ".($page*10-10).",10");
		if((int) mysql_fetch_array($creations)==0){
			die("<meta http-equiv='Refresh' content='0; URL=creations.php?mode=newest'>");
		}
		mysql_data_seek($creations,0);
	break;
	case "newest":
	default:
		$typetext="Newest";
		$creations=mysql_query("SELECT * FROM creations WHERE hidden='no' OR hidden='approved' ORDER BY created DESC LIMIT ".($page*10-10).",10");
		if((int) mysql_fetch_array($creations)==0){
			die("<meta http-equiv='Refresh' content='0; URL=creations.php?mode=newest'>");
		}
		mysql_data_seek($creations,0);
}
if($page>1)$previous=true;
if(mysql_num_rows($creations)==10){
	if(mysql_num_rows(mysql_query("SELECT id FROM creations WHERE hidden='no' OR hidden='approved'"))>($page-1*10)+mysql_num_rows($creations)) $next=true;
}

//display the page
require_once("templates/creations_template.php");

function displayCreations($mysql,$cur_user,$admin){
	if(isset($mysql)){
		$rows=mysql_num_rows($mysql);
		while($creation=mysql_fetch_array($mysql)){
			$user=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=".$creation[3]));
			echo '<div class="creationblock">';
			if(file_exists('data/thumbs/'.$creation[0].'.png')) echo '<a href="creation.php?id='.$creation[0].'"><img class="creationblockthumb" src="data/thumbs/'.$creation[0].'.png"/></a>';
			else echo '<a href="creation.php?id='.$creation[0].'"><img class="creationblockthumb" src="data/thumbs/default.png"/></a>';
			$creationtitle=strlen(stripslashes($creation[1]))>20?substr(stripslashes($creation[1]),0,20)."&hellip;":stripslashes($creation[1]);
			echo '<div class="creationblockhead"><a href="creation.php?id='.$creation[0].'" class="creationblocktitle">'.$creationtitle.'</a>';
			echo '<div><a href="user.php?id='.$user[0].'">'.$user[1].'</a>';if ($user[3] == "admin" || $user[3] == "mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';echo "</div>";
			echo '<div>'.date("F jS, Y", strtotime($creation[4])).'</div>';
			switch($creation[11]){
				case 1:
					$views="1 view";
					break;
				case 0:
					$views="No views";
					break;
				default:
					$views=$creation[11]." views";
			}
			echo '<div>'.$views.'</div>';
			$rating=($creation[13]==0)?"No rating":"Rated ".$creation[13];
			echo '<div>'.$rating.'</div>';
			switch($creation[12]){
				case 1:
					$favourites="1 favourite";
					break;
				case 0:
					$favourites="No favourites";
					break;
				default:
					$favourites=$creation[12]." favourites";
			}
			echo '<div>'.$favourites.'</div></div>';
			if(isset($creation['descr'])&&trim($creation['descr'])!=""){
				$creationdesc=strlen(stripslashes($creation['descr']))>120?substr(str_replace("<br />\n<br />\n"," ",bbcode_parse_description(stripslashes($creation['descr']))),0,120)."&hellip;":str_replace("<br />\n<br />\n"," ",bbcode_parse_description(stripslashes($creation['descr'])));
				echo '<div class="creationblockdesc"><strong style="display:block">Description</strong>'.$creationdesc.'</div>';
			}
			if(isset($creation['advisory'])&&trim($creation['advisory'])!=""){
				$creationadv=strlen(stripslashes($creation['advisory']))>100?substr(stripslashes($creation['advisory']),0,100)."&hellip;":stripslashes($creation['advisory']);
				echo '<div class="creationblockadv"><strong style="display:block">Content advisory</strong>This creation contains '.$creationadv.'</div>';
			}
			if($admin){
				echo '<div style="position:absolute;top:0;right:0;"><a href="creations.php?mode=action&action=hide&id='.$creation[0].'">H</a> <a href="creations.php?mode=action&action=censor&id='.$creation[0].'">C</a> <a href="creations.php?mode=action&action=delete&id='.$creation[0].'">D</a></div>';
			}
			echo '<div style="clear:both;"></div></div>';
		}
		echo '<div style="clear:both;"></div>';
	}
	else echo 'An error occurred. Please try reloading the page or, if the error continues to occur, contact a <a href="info/admin.php">site administrator</a>.';
}
?>
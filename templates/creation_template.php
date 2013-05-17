<!DOCTYPE html>
<?php
if ($creation['type']=="artwork"||$creation['type']=="flash"){
	$imgsize=getimagesize('data/creations/'.$creation['filename']);
	if ($creation['filetype']=="svg"){
		$xmlget = simplexml_load_file('data/creations/'.$creation['filename']);
		$xmlattributes = $xmlget->attributes();
		$imgwidth = (string) $xmlattributes->width; 
		$imgheight = (string) $xmlattributes->height;
	}
}
?>
<html>
<head>
<title><?php echo stripslashes($creation['name']); ?> | <?php echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="../include/style.css" media="screen" />
<script src="../include/creation.js" type="text/javascript"></script>
<script src="../data/jquery.js" type="text/javascript"></script>
<?php
if ($creation['type']=="audio"){
	echo '<script type="text/javascript" src="../data/audio-player.js"></script>';
}
else if ($creation['type']=="scratch"&&$creation['filetype']=="sb2"&&$cur_user['sb2player']=="js"){
	echo '<script type="text/javascript" src="../data/sb2js/script/ZipFile.complete.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../data/sb2js/script/sb2.js"></script>';
}
?>
<script type="text/javascript">
<?php
if ($creation['type']=="flash")
	//to add: change size to size of flash or max in screen resolution keeping ratio
	echo 'function expand(){
	window.open(\'' . $creation['id'] . '/viewer/play\', \'Image\', \'location=yes,resizable=yes,scrollbars=yes,height=600,width=600\', false);
}
function download(){
	window.open(\'' . $creation['id'] . '/viewer\', \'Image\', \'location=yes,resizable=yes,scrollbars=yes,height=600,width=600\', false);
}';

else echo 'function expand(){
	window.open(\'' . $creation['id'] . '/viewer\', \'Image\', \'location=yes,resizable=yes,scrollbars=yes,height=600,width=600\', false);
}';
?>

//lighting up the planets
$(document).ready(function(){
	$("#rating1").hover(function(){
	  $("#rating1").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('../data/icons/antistar.png')");
	  $("#rating3").css("background-image","url('../data/icons/antistar.png')");
	  $("#rating4").css("background-image","url('../data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('../data/icons/antistar.png')");
	  },function(){
	  <?php globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating2").hover(function(){
	  $("#rating1").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('../data/icons/antistar.png')");
	  $("#rating4").css("background-image","url('../data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('../data/icons/antistar.png')");
	  },function(){
	<?php globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating3").hover(function(){
	  $("#rating1").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('../data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('../data/icons/antistar.png')");
	  },function(){
	  <?php globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating4").hover(function(){
	  $("#rating1").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating5").css("background-image","url('../data/icons/antistar.png')");
	  },function(){
	  <?php globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating5").hover(function(){
	  $("#rating1").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('../data/icons/prostar.png')");
	  $("#rating5").css("background-image","url('../data/icons/prostar.png')");
	  },function(){
	  <?php globesToCurrentRating($lrating[0]); ?>
	});
});

<?php
if ($creation['type']=="audio"){
	echo "//audio player
AudioPlayer.setup(\"../data/player.swf\", {  
	width: 100,  
	initialvolume: 100,  
	transparentpagebg: \"yes\"
});";
}
else if ($creation['type']=="scratch"&&$creation['filetype']=="sb2"&&$cur_user['sb2player']=="js"){
	echo '//sb2js init
autoLoad = "../data/creations/'.$creation['filename'].'";
basedir = "../data/sb2js/"';
}
else if ($creation['type']=="writing"){
	echo 'function resize(amount){
	writing=document.getElementById("resizeable");
	if(parseInt(writing.style.fontSize)>8 && Math.abs(amount)!=amount){
		writing=document.getElementById("resizeable");
		writing.style.fontSize=(parseInt(writing.style.fontSize)+amount)+"px";
	}
	else if (parseInt(writing.style.fontSize)<28 && Math.abs(amount)==amount){
		writing=document.getElementById("resizeable");
		writing.style.fontSize=(parseInt(writing.style.fontSize)+amount)+"px";
	}
}';
}
?>
</script>
</head>

<body onload="javascript:illuminate();">
<?php require_once("header.php"); ?>
<div class="container" style="min-height:700px;">

<div class="cleft">
	<div class="ccontainer" <?php if($creation['type']=="scratch") echo 'style="width:486px;margin:auto;margin-top:5px;"'; ?>>
		<div class="creation">
			<?php
			switch($creation['type']){
				case "artwork":
					if($creation['filetype']=="svg"){
						// QUESTION: What does this do?
						if ($imgwidth>473){
							$svgwidth = 'style="width:500px;"';
						}
					}
					echo '<img src="../data/creations/'.$creation['filename'].'" class="cimg" '.$svgwidth.'/>';
				break;
				case "audio":
					echo '<div id="audioplayer">You need the Flash player to view this content.</div>
				<script type="text/javascript">
					AudioPlayer.embed("audioplayer", {
						soundFile: "../data/creations/'.$creation['filename'].'",
						titles: "'.$creation['name'].'",
						artists: "'.$user['username'].'"
					});
				</script>';
				break;
				case "flash":
					echo '<div class="flashblock" style="margin-bottom:10px;">
				<div class="flashwrapper" style="padding-bottom:'.($imgsize[1]/$imgsize[0])*100 .'%">
					<object style="border:1px solid;" class="flash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="editorObj" 
					codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
						<param name="movie" value="../data/creations/'.$creation['filename'].'" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#ffffff" />
						<embed id="editor" src="../data/creations/'.$creation['filename'].'"
							quality="high" 
							bgcolor="#ffffff"
							scale="exactfit"
							play="true"
							loop="false"
							quality="high"
							type="application/x-shockwave-flash"
							pluginspage="http://www.adobe.com/go/getflashplayer">
						</embed>
					</object>
				</div>
			</div>';
				break;
				case "scratch":
					if($creation['filetype']=="sb2"){
						if(!empty($_SESSION['SESS_MEMBER_ID'])&&$cur_user['sb2player']=="js"){
							echo '
			<canvas id="scratch" width="486" height="391" tabindex="1">
				<div style="padding:10px;">Your browser doesn\'t support sb2.js.</div>
			</canvas>';
						}
						else{
							echo '<object style="width:482px;height:387px;position:relative;left:0px;top:0px;margin-left:3px;margin-top:2px;" type="application/x-shockwave-flash" data="../data/PlayerOnly.swf">
				<param name="allowScriptAccess" value="always"><param name="allowFullScreen" value="true">
				<param name="flashvars" value="project=data/creations/'.$creation['filename'].'">
			</object>';
						}
					}
					else{
						echo '<object style="width:482px;height:387px;position:relative;left:0px;top:0px;margin-left:3px;margin-top:2px;" type="application/x-shockwave-flash" data="../data/PlayerOnly.swf">
				<param name="allowScriptAccess" value="always"><param name="allowFullScreen" value="true">
				<param name="flashvars" value="project=data/creations/'.$creation['filename'].'">
			</object>';
					}
				break;
				case "writing":
					echo '<div class="wcontent" style="font-size:13px;" id="resizeable">
					<div class="resizebuttons">
						<a href="javascript:resize(2);" class="plus"></a>
						<a href="javascript:resize(-2);" class="minus"></a>
					</div>
					<div class="wtext">
						'.bbcode_parse(mb_convert_encoding(stripslashes(file_get_contents("../data/creations/".$creation['filename'])),"HTML-ENTITIES",WRITING_ENCODING),true).'
					</div>
				</div>';
				break;
			}
			?>
			
		</div>
		<div class="creationstatsleft" style="float:left;padding:5px;">
			<div class="infotext" style="font-size:14px;">
				<?php
				// Display view number
				echo $views;
				// If there's only one view, just say "view". Otherwise, say views.
				if ($view ==1){
					echo " view";
				}
				else{
					echo " views"; 
				}
				// Display rating
				// If the mean of all the ratings is 0, say there's no rating
				// Since users can't rate something 0, this is only possible if there aren't any ratings as the length of the ratings array will be 0.
				if (number_format(array_sum($ratings)/count($ratings),1)==0.0){
					echo ", no rating";
				}
				// Otherwise, spit out the mean rating
				else{
					echo ", rated ".number_format(array_sum($ratings)/count($ratings),1);
				}
				// Update the rating field on creation in DB (value used in creations.php)
				// TODO: change to an if that senses if the rating is different
				mysql_query("UPDATE creations SET rating=".number_format(array_sum($ratings)/count($ratings),1)." WHERE id=".$creation['id']);
				// Display current user's rating if there's a logged in user and they've rated the creation
				if (!empty($_SESSION['SESS_MEMBER_ID'])&&(number_format($lrating[0],1)!=0.0)){
					echo " (you voted ".number_format($lrating[0],1).")";
				}
				// Display favourites -- if favourite is equal to one, say the appropriate thing.
				echo ", ".$favourites;
				if ($favourites == 1){
					echo " favourite"; 
				}
				else{
					echo " favourites";
				}
				//Display whether the current user favourited this and give a link to change their choice
				if ($favourited == true){
					$favtext = "unfavourite";
				}
				else{
					$favtext = "favourite";
				}
				if (!empty($_SESSION['SESS_MEMBER_ID'])){
					echo ' (<a href="creation.php?id='.$creation['id'].'&action=favourite">'.$favtext.'</a>)';
				}
				?>
				
			</div>
			<div class="ratingglobes">
				<?php
				if (!empty($_SESSION['SESS_MEMBER_ID'])){
					// Set initial style for each globe
					// QUESTION: What does fl stand for?
					for ($fl=0;$fl<5;$fl++){
						if ($fl>$lrating[0]-1) $style[$fl] = 'style="background-image:url(\'../data/icons/antistar.png\');"';
						else $style[$fl] = 'style="background-image:url(\'../data/icons/prostar.png\');"';
					}
					echo '<a href="'.$creation['id'].'/rate/1" id="rating1" '.$style[0].' class="imgrating"></a>
				<a href="'.$creation['id'].'/rate/2" id="rating2" '.$style[1].' class="imgrating"></a>
				<a href="'.$creation['id'].'/rate/3" id="rating3" '.$style[2].' class="imgrating"></a>
				<a href="'.$creation['id'].'/rate/4" id="rating4" '.$style[3].' class="imgrating"></a>
				<a href="'.$creation['id'].'/rate/5"" id="rating5" '.$style[4].' class="imgrating"></a>';
				}
				?>
				
			</div>
		</div>
		<div class="creationstatsright" style="float:right;padding:5px;">
			<div class="downloadboxes" style="text-align:right;">
				<?php
				// Display different buttons based on creation tpe
				switch($creation['type']){
					case "artwork":
						echo '<div><a href="javascript:expand();">Expand</a></div>';
						break;
					case "flash":
						echo '<div><a href="javascript:expand();">Expand</a></div>
				<div><a href="javascript:download();">Download</a></div>';
						break;
					default:
						echo '<div><a href="javascript:expand();">Download</a></div>';
					}
				?>
				
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<h2 style="padding-left:10px;">Comments</h2>
<?php
if (!empty($_SESSION['SESS_MEMBER_ID']))
echo '<form method="post">
<textarea name="commenttext" style="margin-left:10px;margin-top:-10px;min-height:60px;max-height:200px;width:450px;resize:vertical;" placeholder="Enter comment here..."></textarea>
<input type="submit" style="margin-left:10px;" name="newcomment" value="Submit" /><br/>
</form>';
echo '<div class="comments">';
while( $comment = $comments->fetch_array() ){
	//Test if the comment has enough flags to be auto-censored and censor it if it does
	//If comment is marked as alright even after three flags, the comment still shows
	$i=0;
	$hidden=false;
	$fresult = $mysqli->query("SELECT * FROM flags WHERE parentid=".$comment['id']." AND type='comment'") or die( $mysqli->error );
	while( $row = $fresult->fetch_array() ){
		$cflags[$i] = $row[2];
		$i++;
	}
	if (!empty($cflags)){
		$farray = $mysqli->query("SELECT status FROM comments WHERE id = ".$comment['id'])->fetch_array();
		if (count(array_unique($cflags))>=FLAGS_REQUIRED&&$farray[0]=="shown") {
			$mysqli->query("UPDATE comments SET status='censored' WHERE id=".$comment['id']) or die( $mysqli->error );
			$mysqli->query("DELETE FROM flags WHERE parentid=".$comment['id']." AND type='comment'");
			$hidden=true;
		}
	}
	$cflags=array();
	if (!$hidden&&$comment['status']!='censored'||($comment['status']=='censored'&&$cur_user['rank'] == "admin"||$cur_user['rank']== "mod")){
		$com_user = $mysqli->query("SELECT * FROM users WHERE id=".$comment['userid'])->fetch_array();
		if (!empty($com_user['icon'])) echo '<br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="'.$comment['id'].'"><img class="cicon" style="width:35px;height:35px;" src="../data/usericons/'.$com_user['icon'].'"/>';
		else echo '<br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="'.$comment['id'].'"><img class="cicon" style="width:35px;height:35px;" src="../data/usericons/default.png"/>';
		echo '
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id='.$com_user['id'].'">'.$com_user['username'].'</a>';
		if ($com_user['rank'] == "admin" || $com_user['rank'] == "mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
		echo ' <span style="font-size:12px;">('.date("m/d/Y", strtotime($comment['timestamp']))." at ".date("g:ia", strtotime($comment['timestamp']));
		echo ') (<a id="replylink" href="javascript:reply('.$comment['id'].')">reply</a> - <a href="' . BASE_URL . "/comment/" . $comment['id'] . '/flag">flag</a>) ';
		//show the censored/approved/shown comment status for admins and mods
		if ($cur_user['rank'] == "admin"||$cur_user['rank']== "mod"){
			$parenthesis="";
			if ($comment['status']=='censored') echo '<a href="flag.php?id='.$comment['id'].'&type=comment&action=approve" style="color:red;">censored</a>';
			else if ($comment['status']=='approved') echo '<a href="flag.php?id='.$comment['id'].'&type=comment&action=censor" style="color:green;">approved</a>';
			else if ($comment['status']=='shown') {
				echo '(<a href="flag.php?id='.$comment['id'].'&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id='.$comment['id'].'&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a>';
				$parenthesis=")";
			}
			echo ' <a style="text-decoration:none;color:red;" href="flag.php?id='.$comment['id'].'&type=comment&action=delete">&#8709;</a>'.$parenthesis;
		}
		if (($cur_user['rank'] != "admin"&&$cur_user['rank']!= "mod")&&($cur_user['id']==$user['id']||$cur_user['id']==$com_user['id'])){
			echo ' <a style="text-decoration:none;color:red;" href="flag.php?id='.$comment['id'].'&type=comment&action=delete">&#8709;</a>';
		}
		echo '</span></div>';
		//Turn the @ into a link to the userpage
		preg_match_all('/\@(.*?)\ /',$comment['comment'],$usernames);
		$comment_with_links=$comment['comment'];
		if (substr_count($comment['comment'],"@")>0){
			for ($j=0;$j<count($usernames[0]);$j++){
				$username_id = file_get_contents(BASE_URL."api/idfromusername.php?name=".substr($usernames[0][$j],1,strlen($usernames[0][$j])-2));
				if (!empty($username_id)){
					$comment_with_links = preg_replace('/\@'.stripslashes(substr($usernames[0][$j],1,strlen($usernames[0][$j])-2)).'/','[url=user.php?id='.get_id_from_username(substr($usernames[0][$j],1,strlen($usernames[0][$j])-2)).']@'.substr($usernames[0][$j],1,strlen($usernames[0][$j])-2).'[/url] ',$comment['comment']);
					$comment['comment']=$comment_with_links;
				}
			}
		}
		echo '<div style="padding-top:10px;font-size:13px;margin-left:10px;width:430px;">'.stripslashes(bbcode_parse($comment_with_links)).'</div>';
		echo '</div>';
	}
}
?>
</div>
</div>

<div class="cright">
	<div class="ctitle">
		<?php 
		echo stripslashes($creation['name']); 
		echo '<span style="font-size:11px;"> v'.$cur_version;
		if ($creation['ownerid'] ==  $cur_user['id'] || $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod"){
			echo ' (<a href="'.$creation['id'].'/edit">edit</a>)'; 
		}
		echo '</span>';
		?>
		
	</div>
	<div class="cinfo">
		<div class="creationownericon">
			<?php
			if (!empty($user['icon'])) echo '<img class="cicon" src="../data/usericons/'.$user['icon'].'"/>';
			else echo '<img class="cicon" src="../data/usericons/default.png"/>';
			?>
			
		</div>
		<div class="cusertext">
			<div class="cuserlink">
				<?php
				echo '<a href="user.php?id='.$user['id'].'">'.$user['username'].'</a>';if ($user['rank'] == "admin" || $user['rank'] == "mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
				?>
				
			</div>
			<?php
			if ($creation['hidden'] != "no"){
				echo '<div style="color:red;" class="creationstatus">';
				switch($creation['hidden']){
					case "byowner":
						echo "Hidden";
					break;
					case "censored":
						echo "Censored";
					break;
					case "deleted":
						echo "Deleted";
					break;
					case "flagged":
						echo "Flagged by community";
					break;
					default:
						echo "An error occurred";
				}
				echo '</div>';
			}
			?>
			
			<div class="creationtime">
				<?php
				echo date("F jS, Y", strtotime($creation['created']));
				?>
				
			</div>
			<div class="creationlicense">
				<?php
				switch($creation['license']){
					case 'copyright':
						echo '<img src="../data/icons/licenses/copyright.png"/>';
						break;
					case 'cc-0':
						echo '<img src="../data/icons/licenses/publicdomain.png"/>
				<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-zero.png"/>';
						break;
					case 'cc-by':
						echo '<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-by.png"/>';
						break;
					case 'cc-by-nd':
						echo '<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-by.png"/>
				<img src="../data/icons/licenses/cc-nd.png"/>';
						break;
					case 'cc-by-sa':
						echo '<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-by.png"/>
				<img src="../data/icons/licenses/cc-sa.png"/>';
						break;
					case 'cc-by-nc':
						echo '<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-by.png"/>
				<img src="../data/icons/licenses/cc-nc.png"/>';
						break;
					case 'cc-by-nc-nd':
						echo '<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-by.png"/>
				<img src="../data/icons/licenses/cc-nc.png"/>
				<img src="../data/icons/licenses/cc-nd.png"/>';
						break;
					case 'cc-by-nc-sa':
						echo '<img src="../data/icons/licenses/cc.png"/>
				<img src="../data/icons/licenses/cc-by.png"/>
				<img src="../data/icons/licenses/cc-nc.png"/>
				<img src="../data/icons/licenses/cc-sa.png"/>';
						break;
					case 'mit':
						echo '<a href="' . $creation['id'] . '/license"><img src="../data/icons/licenses/mit.png"/></a>';
						break;
					case 'gpl':
						echo '<a href="' . $creation['id'] . '/license"><img src="../data/icons/licenses/gpl.png"/></a>';
						break;
					case 'bsd':
						echo '<a href="' . $creation['id'] . '/license"><img src="../data/icons/licenses/bsd.png"/></a>';
						break;
					default:
						echo "An error occurred";
				}
				?>
			
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php 
	if (!empty($creation['descr'])){
		echo '<div class="ccontent desc" style="margin:5px;">
		<strong style="display:block;">Description</strong>
		'.bbcode_parse_description(stripslashes($creation['descr'])).'
	</div>';
	}
	if (!empty($creation['advisory'])){
		echo '
	<div class="ccontent" style="margin:5px;">
		<strong style="display:block;">Content advisory</strong>
		This project includes '.stripslashes($creation['advisory']).'. (<a href="flag.php?id='.$creation['id'].'">flag creation</a>)
	</div>'; 
	}
	?>
	<div class="relatedcreationsblock" style="margin-top:20px;">
		<strong style="display:block;font-size:14px;margin-left:10px;margin-bottom:-5px;">Other creations that may interest you&hellip;</strong>
		<div class="relatedcreationscontainer" style="background-color:white;margin:auto;padding:1px;margin-top:10px;width:280px;">
			<?php
			$related_amount = 4;
			$related_creations = getRelatedCreations($creation,$related_amount,$mysqli);
			foreach($related_creations as $related_creation){
				if(file_exists("data/thumbs/".$related_creation['id'].".png")){
					$image_thumb = $related_creation['id'];
				}
				else{
					$image_thumb = "default";
				}
				if(file_exists("data/usericons/".$related_creation['ownerid'].".png")){
					$user_thumb = $related_creation['ownerid'];
				}
				else{
					$user_thumb = "default";
				}
				if(strlen(stripslashes($related_creation['descr']))>500){
					$creation_description = substr(str_replace("<br />\n<br />\n"," ",bbcode_parse_description(stripslashes($related_creation['descr']))),0,500);
				}
				else{
					$creation_description = str_replace("<br />\n<br />\n"," ",bbcode_parse_description(stripslashes($related_creation['descr'])));
				}
				echo '
			<div class="relatedcreation" style="height:180px;width:240px;margin:10px;padding:10px;padding-bottom:20px;background-color:grey;">
				<div class="relatedimgs" style="margin:auto;width:233px;height:100px;background-color:white;border:1px solid black;">
					<a href="creation.php?id='.$related_creation['id'].'"><img class="relatedthumb" style="height:100px;width:133px;display:inline;" src="../data/thumbs/'.$image_thumb.'.png" /></a><a href="user.php?id='.$related_creation['ownerid'].'"><img class="relateduser" style="height:100px;width:100px;display:inline;" src="../data/usericons/'.$user_thumb.'.png" /></a>
				</div>
				<div class="relatedtext" style="margin:5px;">
					<div class="relatedleft" style="float:left;">
						<strong style="font-size:18px;"><a href="creation.php?id='.$related_creation['id'].'">'.$related_creation['name'].'</a></strong>
						<div class="relatedbyline" style="">
							by <a href="user.php?id='.$related_creation['ownerid'].'">'.get_username_from_id($related_creation['ownerid'],$mysqli).'</a>
						</div>
						<div class="relateddesc" style="width:230px;height:45px;overflow:hidden;">
							'.$creation_description.'
						</div>
					</div>
				</div>
			</div>';
			}
			?>
			
		</div>
	</div>
</div>

<div style="clear:both;"></div>
</div>
</body>
</html>
<!DOCTYPE html>
<? require_once("config/config.php");
$content = file_get_contents("data/creations/".$creation['filename']);
//echo htmlspecialchars($content,ENT_QUOTES,"UTF-8");
//echo mb_convert_encoding("Hèèèllooo","HTML-ENTITIES",WRITING_ENCODING);
?>
<html>
<head>
<title><? echo stripslashes($creation['1']) ?> | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
<script src="data/jquery.js" type="text/javascript"></script>
<script type="text/javascript"><!--
function expand(){
	<?echo "window.open('viewer.php?id=".$creation['id']."', 'Image', 'location=yes,resizable=yes,scrollbars=yes,height=600,width=600', false);"; ?>
}
//lighting up the planets
$(document).ready(function(){
	$("#rating1").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/antistar.png')");
	  $("#rating3").css("background-image","url('data/icons/antistar.png')");
	  $("#rating4").css("background-image","url('data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	  <? globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating2").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/antistar.png')");
	  $("#rating4").css("background-image","url('data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	<? globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating3").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	  <? globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating4").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('data/icons/prostar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	  <? globesToCurrentRating($lrating[0]); ?>
	});
});
$(document).ready(function(){
	$("#rating5").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('data/icons/prostar.png')");
	  $("#rating5").css("background-image","url('data/icons/prostar.png')");
	  },function(){
	  <? globesToCurrentRating($lrating[0]); ?>
	});
});


//create an array to hold the booleans which determine whether a reply box is being shown
replies=new Array();
comment=new Array();
replybox=new Array();
function reply(id){
	//get the div element with the comment id specified
	comment[id]=document.getElementById(id);
	//if there's no reply box showing
	if(!replies[id]){
		quotedusername=comment[id].childNodes[2].childNodes[0].innerHTML;
		quoteddate=comment[id].childNodes[2].childNodes[2].textContent.substr(2,10);
		//create the reply box div
		replybox[id]=document.createElement('div');
		//set the class of the reply box div to 'replybox' (will contain css at some point? idk)
		replybox[id].setAttribute('class','replybox');
		quotetext=document.createElement("div");
		quotetext.innerHTML=comment[id].childNodes[3].innerHTML;
		if(quotetext.querySelector(".bbcode_quote")!=null)quotetext.removeChild(quotetext.querySelector(".bbcode_quote"));
		//set the html contained by the div to a form which uses the specified id for determining which form is submitted
		replybox[id].innerHTML='<form method="post" style="position:relative;top:10px;left:-5px;"><input type="hidden" name="reply" /><textarea name="msgbody'+id+'" placeholder="Enter your reply..." style="height:50px;width:95%;max-height:150px;margin-left:10px;">[quote name="'+quotedusername+'" date="'+quoteddate+'" url="creation.php?id='+getQueryVariable('id')+'#'+id+'"]'+$.trim(quotetext.innerHTML).replace(/<br>/gi,"")+'[/quote]\r\n</textarea><br/><input type="submit" style="margin-bottom:10px;margin-left:10px;" name="msgsubmit'+id+'" value="Submit"/></form>';
		//add the new div as a child of the comment
		comment[id].appendChild(replybox[id]);
	}
	//if the reply box is showing
	else{
		//remove the reply box from the document
		comment[id].removeChild(replybox[id]);
	}
	//set whether the reply box is showing to its inverse
	replies[id]=!replies[id];
}

//from http://css-tricks.com/snippets/javascript/get-url-variables/
function getQueryVariable(variable){
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if(pair[0] == variable){return pair[1];}
		}
	return(false);
}
var illuminated=0;
function illuminate(){
	if (window.location.hash.length>0){
		if(document.getElementById(window.location.hash.substring(1))!==null){
			if(illuminated!==0)illuminated.setAttribute('style',illuminated.getAttribute('style')+'background-color:gainsboro;');
			illuminated=document.getElementById(window.location.hash.substring(1));
			illuminated.setAttribute('style',illuminated.getAttribute('style')+'background-color:#DCDC77;');
		}
	}
}
if ("onhashchange" in window){
    window.onhashchange = function () {
		illuminate();
	}
}
function resize(amount){
	writing=document.getElementById("resizeable");
	if(parseInt(writing.style.fontSize)>8 && Math.abs(amount)!=amount){
		writing=document.getElementById("resizeable");
		writing.style.fontSize=(parseInt(writing.style.fontSize)+amount)+"px";
	}
	else if (parseInt(writing.style.fontSize)<28 && Math.abs(amount)==amount){
		writing=document.getElementById("resizeable");
		writing.style.fontSize=(parseInt(writing.style.fontSize)+amount)+"px";
	}
}
--></script>
</head>

<body onload="javascript:illuminate();">
<? require_once("header.php"); ?>
<div class="container" style="min-height:700px;">

<div class="cleft">
<div class="ccontainer">
<? 
echo '<div class="wcontent" style="font-size:13px;" id="resizeable"><div class="resizebuttons"><a href="javascript:resize(2);" class="plus"></a><a href="javascript:resize(-2);" class="minus"></a></div>'.bbcode_parse(mb_convert_encoding(stripslashes($content),"HTML-ENTITIES",WRITING_ENCODING),true).'</div>';?>
<div style="font-size:14px;<? if (($creation['filetype']=="svg" && round($imgwidth)>473)||($imgsize[0]>473)) echo "position:relative;top:-35px;"; else echo "position:relative;top:-15px;" ?>padding-left:5px;">
<? echo $views; if ($views == 1) echo " view"; else echo " views"; 
if (number_format(array_sum($ratings)/count($ratings),1)==0.0) echo ", no rating";
else echo ", rated ".number_format(array_sum($ratings)/count($ratings),1);
if (!empty($_SESSION['SESS_MEMBER_ID'])&&(number_format($lrating[0],1)!=0.0)) echo " (you voted ".number_format($lrating[0],1).")";
echo ", ".$favourites; if ($favourites == 1) echo " favourite"; else echo " favourites"; 
if ($favourited == true) $favtext = "unfavourite"; else $favtext = "favourite";
if (!empty($_SESSION['SESS_MEMBER_ID'])) echo ' (<a href="creation.php?id='.$creation['id'].'&action=favourite">'.$favtext.'</a>)';
?>
<div></div>
<?
for ($fl=0;$fl<5;$fl++){
	if ($fl>$lrating[0]-1) $style[$fl] = 'style="background-image:url(\'data/icons/antistar.png\');"';
	else $style[$fl] = 'style="background-image:url(\'data/icons/prostar.png\');"';
}
if (!empty($_SESSION['SESS_MEMBER_ID'])) echo '
<a href="creation.php?id='.$creation['id'].'&action=rate&rating=1" id="rating1" '.$style[0].' class="imgrating"></a><a href="creation.php?id='.$creation['id'].'&action=rate&rating=2" id="rating2" '.$style[1].' class="imgrating"></a><a href="creation.php?id='.$creation['id'].'&action=rate&rating=3"id="rating3" '.$style[2].' class="imgrating"></a><a href="creation.php?id='.$creation['id'].'&action=rate&rating=4" id="rating4" '.$style[3].' class="imgrating"></a><a href="creation.php?id='.$creation['id'].'&action=rate&rating=5" id="rating5" '.$style[4].' class="imgrating"></a>
'; 
echo '<div style="text-align:right;padding-right:5px;"><a href="javascript:expand();">Raw text</a></div>';
echo '<div style="clear:both"></div>';
?>
</div>
</div>
<h2 style="position:relative;<? if(($creation['filetype']=="svg" && round($imgwidth)>473)||($imgsize[0]>473)) echo "left:-130px;"; else echo "left:10px;"?>">Comments</h2>
<?
if (!empty($_SESSION['SESS_MEMBER_ID']))
echo '<form method="post">
<textarea name="commenttext" style="margin-left:10px;margin-top:-10px;min-height:60px;max-height:200px;width:450px;resize:vertical;" placeholder="Enter comment here..."></textarea>
<input type="submit" style="margin-left:10px;" name="newcomment" value="Submit" /><br/>
</form>';

while($comment = mysql_fetch_array($comments)){
	//Test if the comment has enough flags to be auto-censored and censor it if it does
	//If comment is marked as alright even after three flags, the comment still shows
	$i=0;
	$hidden=false;
	$fresult = mysql_query("SELECT * FROM flags WHERE creationid=".$comment['id']." AND type='comment'") or die(mysql_error());
	while($row = mysql_fetch_array($fresult)){
		$cflags[$i] = $row[2];
		$i++;
	}
	if (!empty($cflags)){
		$farray=mysql_fetch_array(mysql_query("SELECT status FROM comments WHERE id = ".$comment['id']));
		if (count(array_unique($cflags))>=FLAGS_REQUIRED&&$farray[0]=="shown") {
			mysql_query("UPDATE comments SET status='censored' WHERE id=".$comment['id']) or die(mysql_error());
			mysql_query("DELETE FROM flags WHERE creationid=".$comment['userid']." AND type='comment'");
			$hidden=true;
		}
	}
	$cflags=array();
	if (!$hidden&&$comment['status']!='censored'||($comment['status']=='censored'&&$cur_user['rank'] == "admin"||$cur_user['rank']== "mod")){
		$com_user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE ".$comment['userid']));
		if (!empty($com_user['icon'])) echo '<br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="'.$comment['id'].'"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/'.$com_user['icon'].'"/>';
		else echo '<br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="'.$comment['userid'].'"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/default.png"/>';
		echo '
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id='.$com_user['id'].'">'.$com_user['username'].'</a>';
		if ($com_user['rank'] == "admin" || $com_user['rank'] == "mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
		echo ' ('.date("n/j/Y", strtotime($comment['timestamp']))." at ".date("g:ia", strtotime($comment['timestamp']));
		echo ')<span style="font-size:12px;"> (<a id="replylink" href="javascript:reply('.$comment['id'].')">reply</a> - <a href="flag.php?id='.$comment['id'].'&type=comment">flag</a>) ';
		//show the censored/approved/shown comment status for admins and mods
		if ($cur_user['rank'] == "admin"||$cur_user['rank']== "mod"){
			if ($comment['status']=='censored') echo '<a href="flag.php?id='.$comment['id'].'&type=comment&action=approve" style="color:red;">censored</a>';
			else if ($comment['status']=='approved') echo '<a href="flag.php?id='.$comment['id'].'&type=comment&action=censor" style="color:green;">approved</a>';
			else if ($comment['status']=='shown') echo '<a href="flag.php?id='.$comment['id'].'&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id='.$comment['id'].'&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a>';
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
<br/>
</div>

<div class="cright">
	<div class="ctitle"><? echo stripslashes($creation['1']); 
	if ($creation['ownerid'] ==  $cur_user['id'] || $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod") echo '<span style="font-size:11px;"> (<a href="edit.php?id='.$creation['id'].'">edit</a>)</span>'; ?></div>
	<div class="cinfo">
	<?
	if (!empty($user['icon'])) echo '<img class="cicon" src="data/usericons/'.$user['icon'].'"/>';
	else echo '<img class="cicon" src="data/usericons/default.png"/>';
	
	echo '<div class="cusertext"><a href="user.php?id='.$user['id'].'">'.$user['username'].'</a>';if ($user['rank'] == "admin" || $user['rank'] == "mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
	if ($creation['hidden'] == "byowner") echo '<div style="color:red;">Hidden</div>';
	if ($creation['hidden'] == "censored") echo '<div style="color:red;">Censored</div>';
	if ($creation['hidden'] == "deleted") echo '<div style="color:red;">Deleted</div>';
	if ($creation['hidden'] == "flagged") echo '<div style="color:red;">Flagged by community</div>';
	echo '<div>'.date("F jS, Y", strtotime($creation['created'])).'</div>';
	switch($creation['license']){
		case 'copyright':
			echo '<img src="data/icons/licenses/copyright.png"/>';
			break;
		case 'cc-0':
			echo '<img src="data/icons/licenses/publicdomain.png"/><img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-zero.png"/>';
			break;
		case 'cc-by':
			echo '<img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-by.png"/>';
			break;
		case 'cc-by-nd':
			echo '<img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-by.png"/><img src="data/icons/licenses/cc-nd.png"/>';
			break;
		case 'cc-by-sa':
			echo '<img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-by.png"/><img src="data/icons/licenses/cc-sa.png"/>';
			break;
		case 'cc-by-nc':
			echo '<img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-by.png"/><img src="data/icons/licenses/cc-nc.png"/>';
			break;
		case 'cc-by-nc-nd':
			echo '<img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-by.png"/><img src="data/icons/licenses/cc-nc.png"/><img src="data/icons/licenses/cc-nd.png"/>';
			break;
		case 'cc-by-nc-sa':
			echo '<img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-by.png"/><img src="data/icons/licenses/cc-nc.png"/><img src="data/icons/licenses/cc-sa.png"/>';
			break;
		case 'mit':
			echo '<a href="license.php?id='.$creation['id'].'"><img src="data/icons/licenses/mit.png"/></a>';
			break;
		case 'gpl':
			echo '<a href="license.php?id='.$creation['id'].'"><img src="data/icons/licenses/gpl.png"/></a>';
			break;
		case 'bsd':
			echo '<a href="license.php?id='.$creation['id'].'"><img src="data/icons/licenses/bsd.png"/></a>';
			break;
	}
	?>
	</div><div style="clear:both"></div></div>
	<? if (!empty($creation['descr'])) echo '<br/><div class="ccontent desc"><strong>Description</strong><br/>'.bbcode_parse_description(stripslashes($creation['descr'])).'</div>';
	if (!empty($creation['advisory'])) echo '<br/><div class="ccontent"><strong>Content advisory</strong><br/>This project includes '.stripslashes($creation['advisory']).'. (<a href="flag.php?id='.$creation['id'].'">flag creation</a>)</div>'; ?>
	
</div>

<div style="height:5px;width:800px;clear:both;"></div>
</div>
</body>
</html>
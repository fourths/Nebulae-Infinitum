<!DOCTYPE html>
<? require_once("config/config.php"); ?>
<html>
<head>
<title>Messages | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
<script src="data/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
//create an array to hold the booleans which determine whether a reply box is being shown
replies=new Array();
message=new Array();
replybox=new Array();
function reply(id){
	//get the div element with the message id specified
	message[id]=document.getElementById(id);
	//if there's no reply box showing
	if(!replies[id]){
		quotedusername=message[id].childNodes[2].childNodes[0].innerHTML;
		quotedid=message[id].childNodes[2].childNodes[0].getAttribute('href');
		quoteddate=message[id].childNodes[2].childNodes[2].childNodes[0].textContent.substr(1,12);
		//create the reply box div
		replybox[id]=document.createElement('div');
		//set the class of the reply box div to 'replybox' (will contain css at some point? idk)
		replybox[id].setAttribute('class','replybox');
		quotetext=document.createElement("div");
		quotetext.innerHTML=message[id].childNodes[3].innerHTML;
		if(quotetext.querySelector(".bbcode_quote")!=null)quotetext.removeChild(quotetext.querySelector(".bbcode_quote"))
		//set the html contained by the div to a form which uses the specified id for determining which form is submitted
		replybox[id].innerHTML='<form method="post" style="position:relative;top:10px;left:-5px;"><input type="hidden" name="reply" /><textarea name="msgbody'+id+'" placeholder="Enter your reply..." style="height:50px;width:95%;max-height:150px;margin-left:10px;">[quote name="'+quotedusername+'" date="'+quoteddate+'" url="'+quotedid+'"]'+$.trim(quotetext.innerHTML)+'[/quote]\r\n</textarea><br/><input type="submit" style="margin-bottom:10px;margin-left:10px;" name="msgsubmit'+id+'" value="Submit"/></form>';
		//add the new div as a child of the message
		message[id].appendChild(replybox[id]);
	}
	//if the reply box is showing
	else{
		//remove the reply box from the document
		message[id].removeChild(replybox[id]);
	}
	//set whether the reply box is showing to its inverse
	replies[id]=!replies[id];
}
</script>
<style type="text/css">
.bbcode_quote{
	clear:both;
	margin-top:15px;
	width:500px;
	padding-bottom:0px;
	margin-bottom:0px;
}
</style>
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
<?if ($luserdata[3]=="admin"&&isset($visitinguser)) echo '<h3 style="margin:0px;">'.$visitinguser.'\'s messages</h3>'?>
<h1 style="margin-bottom:0px;">Administrator messages</h1>
<?php
if(!empty($admin) && mysql_num_rows($admin)>0){
	mysql_data_seek($admin,0);
	for ($i=0;$i<mysql_num_rows($admin);$i++){
		$pmdata=mysql_fetch_row($admin);
		if($pmdata[3]==2&&($luserdata[3]=="admin"&&$pmdata[3]==2&&isset($visitinguser)&&$visitinguser!=$luserdata[0])){
			if (file_exists("data/usericons/".$pmdata[2].".png")) $micon=$pmdata[7]=="specific"?"data/usericons/".$pmdata[2].".png":"data/usericons/admin.png";
			else $micon=$pmdata[7]=="specific"?"data/usericons/default.png":"data/usericons/admin.png";
			$usr=$pmdata[7]=="specific"?'<a href="user.php?id='.$pmdata[2].'">'.get_username_from_id($pmdata[2]).'</a>':'<a href="info/admin.php">Administrator</a>';
			echo '<pre class="pm"><a class="deletebutton" href="messages.php?action=delete&id='.$pmdata[0].'"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername">'.$usr.' <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($pmdata[4])).') <span style="color:red;font-weight:bold">(deleted)</span></span></div><div class="pmtext">'.bbcode_parse(stripslashes($pmdata[5])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
			$admins++;
		}
		else if ($pmdata[3]<2||($luserdata[3]=="admin"&&$pmdata[3]==2&&isset($visitinguser)&&$visitinguser!=$luserdata[0])){
			if (file_exists("data/usericons/".$pmdata[2].".png")) $micon=$pmdata[7]=="specific"?"data/usericons/".$pmdata[2].".png":"data/usericons/admin.png";
			else $micon=$pmdata[7]=="specific"?"data/usericons/default.png":"data/usericons/admin.png";
			$usr=$pmdata[7]=="specific"?'<a href="user.php?id='.$pmdata[2].'">'.get_username_from_id($pmdata[2]).'</a>':'<a href="info/admin.php">Administrator</a>';
			echo '<pre class="pm"><a class="deletebutton" href="messages.php?action=delete&id='.$pmdata[0].'"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername">'.$usr.' <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($pmdata[4])).')</span></div><div class="pmtext">'.bbcode_parse(stripslashes($pmdata[5])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
			$admins++;
		}
	}
	if ($admins<1) echo "You have no activity notifications."; 
}
else echo "You have no administrator messages.";
?>
<h1 style="margin-bottom:0px;">Activity notifications</h1>
<?php
if(!empty($notifications) && mysql_num_rows($notifications)>0){
	mysql_data_seek($notifications,0);
	for ($i=0;$i<mysql_num_rows($notifications);$i++){
		$pmdata=mysql_fetch_row($notifications);
		if ($pmdata[3]==2&&($luserdata[3]=="admin"&&$pmdata[3]==2&&isset($visitinguser)&&$visitinguser!=$luserdata[0])){
			echo '<pre class="pm"><div class="pmusername"><a class="deletebutton" href="messages.php?action=delete&id='.$pmdata[0].'"></a><strong style="font-size:12px;">'.date("M d, Y G:i T", strtotime($pmdata[4])).'</strong> <span style="color:red;font-weight:bold">(deleted)</span></div><div class="pmtext">'.bbcode_parse(stripslashes($pmdata[5])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
			$note++;
		}
		else if ($pmdata[3]<2||($luserdata[3]=="admin"&&$pmdata[3]==2&&isset($visitinguser)&&$visitinguser!=$luserdata[0])){
			if($pmdata[3]==2) $deleted='<span style="color:red;font-weight:bold">(deleted)</span>';
			echo '<pre class="pm"><div class="pmusername"><a class="deletebutton" href="messages.php?action=delete&id='.$pmdata[0].'"></a><strong style="font-size:12px;">'.date("M d, Y G:i T", strtotime($pmdata[4])).'</strong>'.$deleted.'</div><div class="pmtext">'.bbcode_parse(stripslashes($pmdata[5])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
			$note++;
		}
	}
	if ($note<1) echo "You have no activity notifications."; 
}
else echo "You have no activity notifications.";
?>
<h1 style="margin-bottom:0px;">Private messages</h1>
<?php
if(!empty($private) && mysql_num_rows($private)>0){
	mysql_data_seek($private,0);
	for ($i=0;$i<mysql_num_rows($private);$i++){
		$pmdata=mysql_fetch_row($private);
		if ($pmdata[3]==2&&($luserdata[3]=="admin"&&$pmdata[3]==2&&isset($visitinguser)&&$visitinguser!=$luserdata[0])){
			$micon=file_exists("data/usericons/".$pmdata[2].".png")?"data/usericons/".$pmdata[2].".png":"data/usericons/default.png";
			echo '<pre class="pm" id="'.$pmdata[0].'"><a class="deletebutton" href="messages.php?action=delete&id='.$pmdata[0].'"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername"><a href="user.php?id='.$pmdata[2].'">'.get_username_from_id($pmdata[2]).'</a> <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($pmdata[4])).') (<a id="replylink" href="javascript:reply('.$pmdata[0].')">reply</a>) <span style="color:red;font-weight:bold">(deleted)</span></span></div><div class="pmtext">'.bbcode_parse(stripslashes($pmdata[5])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
			$pm++;
		}
		else if ($pmdata[3]<2||($luserdata[3]=="admin"&&$pmdata[3]==2&&isset($visitinguser)&&$visitinguser!=$luserdata[0])){
			if($pmdata[3]==2) $deleted='<span style="color:red;font-weight:bold">(deleted)</span>';
			$micon=file_exists("data/usericons/".$pmdata[2].".png")?"data/usericons/".$pmdata[2].".png":"data/usericons/default.png";
			echo '<pre class="pm" id="'.$pmdata[0].'"><a class="deletebutton" href="messages.php?action=delete&id='.$pmdata[0].'"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername"><a href="user.php?id='.$pmdata[2].'">'.get_username_from_id($pmdata[2]).'</a> <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($pmdata[4])).') (<a id="replylink" href="javascript:reply('.$pmdata[0].')">reply</a>) '.$deleted.'</span></div><div class="pmtext">'.bbcode_parse(stripslashes($pmdata[5])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
			$pm++;
		}
	}
	if ($pm<1) echo "You have no activity notifications."; 
}
else echo "You have no private messages.";
?>
</div>
</body>
</html>
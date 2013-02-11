
<!DOCTYPE html>
<html>
<head>
<title>Supercollider | Nebulae Infinitum</title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
<script src="data/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="data/audio-player.js"></script>
<script type="text/javascript"><!--

function expand(){
	window.open('viewer.php?id=1', 'Image', 'location=yes,resizable=yes,scrollbars=yes,height=600,width=600', false);
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
	  $("#rating1").css("background-image","url('data/icons/antistar.png')");$("#rating2").css("background-image","url('data/icons/antistar.png')");$("#rating3").css("background-image","url('data/icons/antistar.png')");$("#rating4").css("background-image","url('data/icons/antistar.png')");$("#rating5").css("background-image","url('data/icons/antistar.png')");	});
});
$(document).ready(function(){
	$("#rating2").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/antistar.png')");
	  $("#rating4").css("background-image","url('data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	$("#rating1").css("background-image","url('data/icons/antistar.png')");$("#rating2").css("background-image","url('data/icons/antistar.png')");$("#rating3").css("background-image","url('data/icons/antistar.png')");$("#rating4").css("background-image","url('data/icons/antistar.png')");$("#rating5").css("background-image","url('data/icons/antistar.png')");	});
});
$(document).ready(function(){
	$("#rating3").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('data/icons/antistar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	  $("#rating1").css("background-image","url('data/icons/antistar.png')");$("#rating2").css("background-image","url('data/icons/antistar.png')");$("#rating3").css("background-image","url('data/icons/antistar.png')");$("#rating4").css("background-image","url('data/icons/antistar.png')");$("#rating5").css("background-image","url('data/icons/antistar.png')");	});
});
$(document).ready(function(){
	$("#rating4").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('data/icons/prostar.png')");
	  $("#rating5").css("background-image","url('data/icons/antistar.png')");
	  },function(){
	  $("#rating1").css("background-image","url('data/icons/antistar.png')");$("#rating2").css("background-image","url('data/icons/antistar.png')");$("#rating3").css("background-image","url('data/icons/antistar.png')");$("#rating4").css("background-image","url('data/icons/antistar.png')");$("#rating5").css("background-image","url('data/icons/antistar.png')");	});
});
$(document).ready(function(){
	$("#rating5").hover(function(){
	  $("#rating1").css("background-image","url('data/icons/prostar.png')");
	  $("#rating2").css("background-image","url('data/icons/prostar.png')");
	  $("#rating3").css("background-image","url('data/icons/prostar.png')");
	  $("#rating4").css("background-image","url('data/icons/prostar.png')");
	  $("#rating5").css("background-image","url('data/icons/prostar.png')");
	  },function(){
	  $("#rating1").css("background-image","url('data/icons/antistar.png')");$("#rating2").css("background-image","url('data/icons/antistar.png')");$("#rating3").css("background-image","url('data/icons/antistar.png')");$("#rating4").css("background-image","url('data/icons/antistar.png')");$("#rating5").css("background-image","url('data/icons/antistar.png')");	});
});

//audio player
AudioPlayer.setup("data/player.swf", {  
        width: 100,  
        initialvolume: 100,  
        transparentpagebg: "yes"
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
		if(comment[id].childNodes[2].childNodes.length==4)
			quoteddate=comment[id].childNodes[2].childNodes[3].textContent.substr(1,10);
		else
			quoteddate=comment[id].childNodes[2].childNodes[2].textContent.substr(1,10);
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
if ("onhashchange" in window)
    window.onhashchange = function () {
		illuminate();
	}
	
--></script>
</head>

<body onload="javascript:illuminate();">
<div class="header">
<a class="headtext" href="http://localhost/">nebulae infinitum</a><br/>
<div class="headlinks"><a class="head" href="http://localhost/">home</a> &bull; <a class="head" href="http://localhost/creations.php">creations</a> &bull; <a class="head" href="http://localhost/info/">about</a> &bull; <a class="head" href="http://localhost/forums/">forums</a>  &bull; <a class="head" href="http://localhost/admin.php">admin</a> <div style="padding-top:5px;">logged in as <a class="head" href="http://localhost/user.php?id=1">veggieman</a> (<a href="http://localhost/messages.php" class="head">&#9993;</a>) &bull; <a class="head" href="http://localhost/upload.php">upload</a> &bull; <a class="head" href="http://localhost/login.php?action=logout&returnto=/creation.php?id=1">logout</a></div></div>
</div>
<div class="container" style="min-height:700px;">

<div class="cleft">
	<div class="ccontainer">
		<div class="creation">
			<div id="audioplayer">You need the Flash player to view this content.</div>
			<script type="text/javascript">
			AudioPlayer.embed("audioplayer", {
				soundFile: "data/creations/12.mp3",
				titles: "brr",
				artists: "veggieman"});
			</script>
		</div>
		<div style="float:left;">
			<div style="font-size:14px;">1 view, no rating, 0 favourites (<a href="creation.php?id=1&action=favourite">favourite</a>)</div>
			<div>
				<a href="creation.php?id=1&action=rate&rating=1" id="rating1" style="background-image:url('data/icons/antistar.png');" class="imgrating"></a><a href="creation.php?id=1&action=rate&rating=2" id="rating2" style="background-image:url('data/icons/antistar.png');" class="imgrating"></a><a href="creation.php?id=1&action=rate&rating=3"id="rating3" style="background-image:url('data/icons/antistar.png');" class="imgrating"></a><a href="creation.php?id=1&action=rate&rating=4" id="rating4" style="background-image:url('data/icons/antistar.png');" class="imgrating"></a><a href="creation.php?id=1&action=rate&rating=5" id="rating5" style="background-image:url('data/icons/antistar.png');" class="imgrating"></a>
			</div>
		</div>
		<div style="float:right;">
			<div style="text-align:right;"><a href="javascript:expand();">Download</a></div>
		</div>
		<div style="clear:both;"></div>
</div>

<h2 style="padding-left:10px;">Comments</h2>
<form method="post">
<textarea name="commenttext" style="margin-left:10px;margin-top:-10px;min-height:60px;max-height:200px;width:450px;resize:vertical;" placeholder="Enter comment here..."></textarea>
<input type="submit" style="margin-left:10px;" name="newcomment" value="Submit" /><br/>
</form>
<div class="comments">
<div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="6"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/default.png"/>
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id=2">testaccount</a> <span style="font-size:12px;">(02/11/2013 at 12:40pm) (<a id="replylink" href="javascript:reply(6)">reply</a> - <a href="flag.php?id=6&type=comment">flag</a>) (<a href="flag.php?id=6&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id=6&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a> <a style="text-decoration:none;color:red;" href="flag.php?id=6&type=comment&action=delete">&#8709;</a>)</span></div><div style="padding-top:10px;font-size:13px;margin-left:10px;width:430px;">da da da </div></div><br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="5"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/1.png"/>
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id=1">veggieman</a><a href="info/staff.php" style="text-decoration:none;">*</a> <span style="font-size:12px;">(02/11/2013 at 12:40pm) (<a id="replylink" href="javascript:reply(5)">reply</a> - <a href="flag.php?id=5&type=comment">flag</a>) (<a href="flag.php?id=5&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id=5&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a> <a style="text-decoration:none;color:red;" href="flag.php?id=5&type=comment&action=delete">&#8709;</a>)</span></div><div style="padding-top:10px;font-size:13px;margin-left:10px;width:430px;">notification setting test </div></div><br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="4"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/default.png"/>
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id=2">testaccount</a> <span style="font-size:12px;">(02/06/2013 at 1:27pm) (<a id="replylink" href="javascript:reply(4)">reply</a> - <a href="flag.php?id=4&type=comment">flag</a>) (<a href="flag.php?id=4&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id=4&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a> <a style="text-decoration:none;color:red;" href="flag.php?id=4&type=comment&action=delete">&#8709;</a>)</span></div><div style="padding-top:10px;font-size:13px;margin-left:10px;width:430px;">
<div class="bbcode_quote">
<div class="bbcode_quote_head"><a href="creation.php?id=1#3">veggieman wrote on 02/06/2013:</a></div>
<div class="bbcode_quote_body">This is a wonderful comment.</div>
</div>
Verily! </div></div><br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="2"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/default.png"/>
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id=2">testaccount</a> <span style="font-size:12px;">(01/31/2013 at 12:34pm) (<a id="replylink" href="javascript:reply(2)">reply</a> - <a href="flag.php?id=2&type=comment">flag</a>) (<a href="flag.php?id=2&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id=2&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a> <a style="text-decoration:none;color:red;" href="flag.php?id=2&type=comment&action=delete">&#8709;</a>)</span></div><div style="padding-top:10px;font-size:13px;margin-left:10px;width:430px;">
<div class="bbcode_quote">
<div class="bbcode_quote_head"><a href="creation.php?id=1#1">veggieman wrote on 01/31/2013:</a></div>
<div class="bbcode_quote_body">test comment</div>
</div>
test comment 2 </div></div><br/><div style="background-color:gainsboro;width:450px;word-wrap:break-word;margin-left:10px;padding-top:5px;padding-bottom:10px;" id="1"><img class="cicon" style="width:35px;height:35px;" src="data/usericons/1.png"/>
		<div style="position:relative;left:5px;font-size:16px;font-weight:bold;padding-top:10px;"><a href="user.php?id=1">veggieman</a><a href="info/staff.php" style="text-decoration:none;">*</a> <span style="font-size:12px;">(01/31/2013 at 12:32pm) (<a id="replylink" href="javascript:reply(1)">reply</a> - <a href="flag.php?id=1&type=comment">flag</a>) (<a href="flag.php?id=1&type=comment&action=approve" style="color:green;text-decoration:none;">&#10004;</a> <a href="flag.php?id=1&type=comment&action=censor" style="color:red;text-decoration:none;">&#10007;</a> <a style="text-decoration:none;color:red;" href="flag.php?id=1&type=comment&action=delete">&#8709;</a>)</span></div><div style="padding-top:10px;font-size:13px;margin-left:10px;width:430px;">test comment </div></div><br/>
</div>
</div>

<div class="cright">
	<div class="ctitle">Supercollider<span style="font-size:11px;"> (<a href="edit.php?id=1">edit</a>)</span></div>
	<div class="cinfo">
	<img class="cicon" src="data/usericons/1.png"/><div class="cusertext"><a href="user.php?id=1">veggieman</a><a href="info/staff.php" style="text-decoration:none;">*</a><div>June 23rd, 2012</div><img src="data/icons/licenses/publicdomain.png"/><img src="data/icons/licenses/cc.png"/><img src="data/icons/licenses/cc-zero.png"/>	</div><div style="clear:both"></div></div>
	<br/><div class="ccontent desc"><strong>Description</strong><br/>Bork <a href="http://scratch.mit.edu/" class="bbcode_url">bork</a> bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork bork.</div><br/><div class="ccontent"><strong>Content advisory</strong><br/>This project includes minor gore, scary trees, and other things. (<a href="flag.php?id=1">flag creation</a>)</div>	
</div>

<div style="height:5px;width:800px;clear:both;"></div>
</div>
</body>
</html>
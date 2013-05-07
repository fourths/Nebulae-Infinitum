<!DOCTYPE html>
<html>
	<head>
		<title>
			Messages | <?php echo SITE_NAME ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="include/style.css" media="screen" />
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
					if (message[id].childNodes[2].childNodes.length==4)
						quoteddate=message[id].childNodes[2].childNodes[3].textContent.substr(1,12);
					else
						quoteddate=message[id].childNodes[2].childNodes[2].textContent.substr(1,12);
					//create the reply box div
					replybox[id]=document.createElement('div');
					//set the class of the reply box div to 'replybox' (will contain css at some point? idk)
					replybox[id].setAttribute('class','replybox');
					quotetext=document.createElement("div");
					quotetext.innerHTML=document.getElementById('pmtext'+id).innerHTML;
					if(quotetext.querySelector(".bbcode_quote")!=null)quotetext.removeChild(quotetext.querySelector(".bbcode_quote"));
					//set the html contained by the div to a form which uses the specified id for determining which form is submitted
					replybox[id].innerHTML='<form method="post" style="position:relative;top:10px;left:-5px;"><input type="hidden" name="reply" /><textarea name="msgbody'+id+'" placeholder="Enter your reply..." style="height:50px;width:95%;max-height:150px;margin-left:10px;">[quote name="'+quotedusername+'" date="'+quoteddate+'" url="'+quotedid+'"]'+$.trim(quotetext.innerHTML).replace(/<br>/gi,"")+'[/quote]\r\n</textarea><br/><input type="submit" style="margin-bottom:10px;margin-left:10px;" name="msgsubmit'+id+'" value="Submit"/></form>';
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
		<?php require_once("header.php"); ?>
		<div class="container">
			<?php if ($cur_user['rank']=="admin"&&isset($visitinguser)) echo '<h3 style="margin:0px;">'.$visitinguser.'\'s messages</h3>'; ?>
			
			<h1 style="margin-bottom:0px;">Administrator messages</h1>
			<?php
			if(!empty($admin) && $admin->num_rows>0){
				$admin->data_seek(0);
				for ($i=0;$i<$admin->num_rows;$i++){
					$message = $admin->fetch_array();
					if($message['viewed']==2&&($cur_user['rank']=="admin"&&$message['viewed']==2&&isset($visitinguser)&&$visitinguser!=$cur_user['id'])){
						if (file_exists("data/usericons/".$message['senderid'].".png")) $micon=$message['admintype']=="specific"?"data/usericons/".$message['senderid'].".png":"data/usericons/admin.png";
						else $micon=$message['admintype']=="specific"?"data/usericons/default.png":"data/usericons/admin.png";
						$usr=$message['admintype']=="specific"?'<a href="user/'.get_username_from_id($message['senderid'],$mysqli).'">'.get_username_from_id($message['senderid'],$mysqli).'</a>':'<a href="about/admin">Administrator</a>';
						echo '<pre class="pm"><a class="deletebutton" href="message/'.$message['id'].'/delete"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername">'.$usr.' <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($message['timestamp'])).') <span style="color:red;font-weight:bold">(deleted)</span></span></div><div class="pmtext">'.bbcode_parse(stripslashes($message['message'])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
						$admins++;
					}
					else if ($message['viewed']<2||($cur_user['rank']=="admin"&&$message['viewed']==2&&isset($visitinguser)&&$visitinguser!=$cur_user['id'])){
						if (file_exists("data/usericons/".$message['senderid'].".png")) $micon=$message['admintype']=="specific"?"data/usericons/".$message['senderid'].".png":"data/usericons/admin.png";
						else $micon=$message['admintype']=="specific"?"data/usericons/default.png":"data/usericons/admin.png";
						$usr=$message['admintype']=="specific"?'<a href="user/'.get_username_from_id($message['senderid'],$mysqli).'">'.get_username_from_id($message['senderid'],$mysqli).'</a>':'<a href="about/admin">Administrator</a>';
						echo '<pre class="pm"><a class="deletebutton" href="message/'.$message['id'].'/delete"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername">'.$usr.' <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($message['timestamp'])).')</span></div><div class="pmtext">'.bbcode_parse(stripslashes($message['message'])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
						$admins++;
					}
				}
				if ($admins<1) echo "You have no activity notifications."; 
			}
			else echo "You have no administrator messages.";
			?>
			
			<h1 style="margin-bottom:0px;">Activity notifications</h1>
			<?php
			if(!empty($notifications) && $notifications->num_rows>0){
				$notifications->data_seek(0);
				for ($i=0;$i<$notifications->num_rows;$i++){
					$message= $notifications->fetch_array();
					if ($message['viewed']==2&&($cur_user['rank']=="admin"&&$message['viewed']==2&&isset($visitinguser)&&$visitinguser!=$cur_user['id'])){
						echo '<pre class="pm"><div class="pmusername"><a class="deletebutton" href="message/'.$message['id'].'/delete"></a><strong style="font-size:12px;">'.date("M d, Y G:i T", strtotime($message['timestamp'])).'</strong> <span style="color:red;font-weight:bold">(deleted)</span></div><div class="pmtext">'.bbcode_parse(stripslashes($message['message'])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
						$note++;
					}
					else if ($message['viewed']<2||($cur_user['rank']=="admin"&&$message['viewed']==2&&isset($visitinguser)&&$visitinguser!=$cur_user['id'])){
						if($message['viewed']==2) $deleted='<span style="color:red;font-weight:bold">(deleted)</span>';
						echo '<pre class="pm"><div class="pmusername"><a class="deletebutton" href="message/'.$message['id'].'/delete"></a><strong style="font-size:12px;">'.date("M d, Y G:i T", strtotime($message['timestamp'])).'</strong>'.$deleted.'</div><div class="pmtext">'.bbcode_parse(stripslashes($message['message'])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
						$note++;
					}
				}
				if ($note<1) echo "You have no activity notifications."; 
			}
			else echo "You have no activity notifications.";
			?>
			
			<h1 style="margin-bottom:0px;">Private messages</h1>
			<?php
			if(!empty($private) && $private->num_rows>0){
				$private->data_seek(0);
				for ($i=0;$i<$private->num_rows;$i++){
					$message= $private->fetch_array();
					$user_rank = get_rank_from_id($message['senderid'],$mysqli);
					if ($message['viewed']==2&&($cur_user['rank']=="admin"&&$message['viewed']==2&&isset($visitinguser)&&$visitinguser!=$cur_user['id'])){
						$micon=file_exists("data/usericons/".$message['senderid'].".png")?"data/usericons/".$message['senderid'].".png":"data/usericons/default.png";
						if($user_rank=="mod"||$user_rank=="admin") $rank_text = '<a href="about/admin" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
						echo '<pre class="pm" id="'.$message['id'].'"><a class="deletebutton" href="message/"'.$message['id'].'/delete"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername"><a href="user/'.get_username_from_id($message['senderid'],$mysqli).'">'.get_username_from_id($message['senderid'],$mysqli).$rank_text.'</a> <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($message['timestamp'])).') (<a id="replylink" href="javascript:reply('.$message['id'].')">reply</a> - <a href="message/'.$message['id'].'/flag">flag</a>) <span style="color:red;font-weight:bold">(deleted)</span></span></div><div id="pmtext'.$message['id'].'>'.bbcode_parse(stripslashes($message['message'])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
						$pm++;
					}
					else if ($message['viewed']<2||($cur_user['rank']=="admin"&&$message['viewed']==2&&isset($visitinguser)&&$visitinguser!=$cur_user['id'])){
						if($message['viewed']==2) $deleted='<span style="color:red;font-weight:bold">(deleted)</span>';
						$micon=file_exists("data/usericons/".$message['senderid'].".png")?"data/usericons/".$message['senderid'].".png":"data/usericons/default.png";
						if($user_rank=="mod"||$user_rank=="admin") $rank_text = '<a href="about/admin" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
						echo '<pre class="pm" id="'.$message['id'].'"><a class="deletebutton" href="message/'.$message['id'].'/delete"></a><img class="pmimg" src="'.$micon.'"/><div class="pmusername"><a href="user/'.get_username_from_id($message['senderid'],$mysqli).'">'.get_username_from_id($message['senderid'],$mysqli).$rank_text.'</a> <span style="font-size:11px;">('.date("M d, Y G:i T", strtotime($message['timestamp'])).') (<a id="replylink" href="javascript:reply('.$message['id'].')">reply</a> - <a href="message/'.$message['id'].'/flag">flag</a>) '.$deleted.'</span></div><div id="pmtext'.$message['id'].'">'.bbcode_parse(stripslashes($message['message'])).'</div><div style="clear:both;width:100%;height:0px;"></div></pre>';
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
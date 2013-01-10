<!DOCTYPE html>
<? require_once("config/config.php");?>
<html>
<head>
<title>Administration | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<h1>Administration</h1>
	<div class="adminblock">
	<h2 style="margin:0px;">Recent flags</h2>
	<div class="flagblock">
	<table>
	<?
	if (isset($flags)&&(int) mysql_fetch_array($flags)!=0){
	mysql_data_seek($flags,0);
		while($flag=mysql_fetch_array($flags)){
			if($flag['type']=="creation"){
				$creationname=mysql_fetch_array(mysql_query("SELECT name FROM creations WHERE id=".$flag['parentid']));
				$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted creation</span>":'<a class="td" href="creation.php?id='.$flag['parentid'].'">'.$creationname[0].'</a>';
			}
			else if ($flag['type']=="comment"){
				$creationname=mysql_fetch_array(mysql_query("SELECT comment FROM comments WHERE id=".$flag['parentid']));
				$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted comment</span>":'<a class="td" href="creation.php?id='.get_creation_from_comment($flag['parentid']).'#'.$flag['parentid'].'">'.strip_bbcode($creationname[0]).'</a>';
			}
			echo '<tr id="'.$flag['id'].'">
			<td class="'.$flag['type'].'" style="width:200px;">'.$creationname[0].'</td>
			<td class="'.$flag['type'].'" style="width:80px;"><a class="td" href="user.php?id='.$flag['userid'].'">'.get_username_from_id($flag['userid']).'</a></td>
			<td class="'.$flag['type'].'" style="width:80px;">'.date("m/d/Y",strtotime($flag['timestamp'])).'</td>
			<td class="'.$flag['type'].'" style="width:400px;">'.$flag['content'].'<a class="deletebutton" href="?mode=flags&action=delete&id='.$flag['id'].'"></a></td>
			</tr>';
		}
	}
	?>
	</table>
	</div>
	<?if ($flagsamt>10) echo '<a href="?mode=flags" class="td">Show more &gt;</a>';?>
	</div>
	<div class="adminblock">
	<h2 style="margin:0px;">User preferences</h2>
	Enter a username and press submit to go to their preferences page.
	<form method="post">
	<input type="text" name="prefsusername" placeholder="Username"/><input type="submit" name="prefssubmit" value="Submit"/>
	</form>
	</div>
	<div class="adminblock">
	<h2 style="margin:0px;">User message history</h2>
	Enter a username and press submit to view their entire saved message history.
	<form method="post">
	<input type="text" name="usernamemsg" placeholder="Username"/><input type="submit" name="msgsubmit" value="Submit"/>
	</form>
	</div>
	<div class="adminblock">
	<h2 style="margin:0px;">Admin messages</h2>
	Use this form to send administrator messages.
	<form method="post">
	<input type="text" name="recipientusername" placeholder="Recipient username"/><br/>
	<textarea name="adminmessage" placeholder="Message" style="height:100px;width:200px;max-width:500px;max-height:300px;margin-left:2px;"></textarea>
	<br/>
	<input type="checkbox" name="showuser" value="1">Don't show sender username
	<br/>
	<input type="submit" name="adminmessagesubmit" value="Submit"/>
	</form>
	</div>
</body>
</html>
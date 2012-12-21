<!DOCTYPE html>
<? require_once("config/config.php");?>
<html>
<head>
<title>Flags / Administration | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>
<body>
<? require_once("header.php"); ?>
<div class="container">
	<h1>Recent flags</h1>
	<div class="adminblock">
	<div class="flagblock">
	<table>
	<?
	if (isset($flags)&&(int) mysql_fetch_row($flags)!=0){
	mysql_data_seek($flags,0);
		while($flag=mysql_fetch_row($flags)){
			if($flag[5]=="creation"){
				$creationname=mysql_fetch_row(mysql_query("SELECT name FROM creations WHERE id=".$flag[3]));
				$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted creation</span>":'<a class="td" href="creation.php?id='.$flag[3].'">'.$creationname[0].'</a>';
			}
			else if ($flag[5]=="comment"){
				$creationname=mysql_fetch_row(mysql_query("SELECT comment FROM comments WHERE id=".$flag[3]));
				$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted comment</span>":'<a class="td" href="creation.php?id='.get_creation_from_comment($flag[3]).'#'.$flag[3].'">'.strip_bbcode($creationname[0]).'</a>';
			}
			echo '<tr id="'.$flag[0].'">
			<td class="'.$flag[5].'" style="width:200px;">'.$creationname[0].'</td>
			<td class="'.$flag[5].'" style="width:80px;"><a class="td" href="user.php?id='.$flag[2].'">'.get_username_from_id($flag[2]).'</a></td>
			<td class="'.$flag[5].'" style="width:80px;">'.date("m/d/Y",strtotime($flag[1])).'</td>
			<td class="'.$flag[5].'" style="width:400px;">'.$flag[4].'<a class="deletebutton" href="?mode=flags&action=delete&id='.$flag[0].'"></a></td>
			</tr>';
		}
	}
	?>
	</table>
	</div>
	<a href="admin.php" class="td">&lt; Back</a>
	</div>
</body>
</html>
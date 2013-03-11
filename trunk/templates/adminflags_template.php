<!DOCTYPE html>
<?php
require_once("config/config.php");
?>
<html>
<head>
<title>Flags / Administration | <?php echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>
<body>
<?php
require_once("header.php"); 
?>
<div class="container">
	<h1>Recent flags</h1>
	<div class="adminblock">
	<div class="flagblock">
	<table>
	<?php
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
	<a href="admin.php" class="td">&lt; Back</a>
	</div>
</body>
</html>
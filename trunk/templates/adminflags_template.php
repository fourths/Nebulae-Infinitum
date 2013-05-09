<!DOCTYPE html>
<html>
	<head>
		<title>
			Administration / Flags | <?php echo SITE_NAME ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="../include/style.css" media="screen" />
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
					if (isset($flags)&&(int) $flags->fetch_array()!=0){
						$flags->data_seek(0);
						while($flag=$flags->fetch_array()){
							if($flag['type']=="creation"){
								$creationname=$mysqli->query("SELECT name FROM creations WHERE id=".$flag['parentid'])->fetch_array();
								$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted creation</span>":'<a class="td" href="../creation/'.$flag['parentid'].'">'.$creationname[0].'</a>';
							}
							else if ($flag['type']=="comment"){
								$creationname=$mysqli->query("SELECT comment FROM comments WHERE id=".$flag['parentid'])->fetch_array();
								$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted comment</span>":'<a class="td" href="../creation/'.get_creation_from_comment($flag['parentid'],$mysqli).'#'.$flag['parentid'].'">'.strip_bbcode($creationname[0]).'</a>';
							}
							else if ($flag['type']=="message"){
								$creationname=$mysqli->query("SELECT message FROM messages WHERE id=".$flag['parentid'])->fetch_array();
								$creationname[0]=$creationname[0]==""?"<span style='color:#E00'>Deleted message</span>":'<a class="td" href="../messages/'.get_sender_from_message($flag['parentid'],$mysqli).'#'.$flag['parentid'].'">'.strip_bbcode($creationname[0]).'</a>';
							}
							echo '<tr id="'.$flag['id'].'">
							<td class="'.$flag['type'].'" style="width:200px;">'.$creationname[0].'</td>
							<td class="'.$flag['type'].'" style="width:80px;"><a class="td" href="user.php?id='.$flag['userid'].'">'.get_username_from_id($flag['userid'],$mysqli).'</a></td>
							<td class="'.$flag['type'].'" style="width:80px;">'.date("m/d/Y",strtotime($flag['timestamp'])).'</td>
							<td class="'.$flag['type'].'" style="width:400px;">'.$flag['content'].'<a class="deletebutton" href="?mode=flags&action=delete&id='.$flag['id'].'"></a></td>
							</tr>';
						}
					}
					?>
					
					</table>
				</div>
				<a href="../admin" class="td">&lt; Back</a>
			</div>
		</div>
	</body>
</html>
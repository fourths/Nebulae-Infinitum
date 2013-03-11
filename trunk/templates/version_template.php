<!DOCTYPE html>
<html>
	<head>
		<title>
			Versions of <?php echo $creation['name']; ?> | <?php echo SITE_NAME; ?>
		</title>
		<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
	</head>
	
	<body>
		<?php require_once("header.php"); ?>
		<div class="container">
			<div class="editheader" style="margin-bottom:10px;">
				<h2 style="display:inline;">Versions of <?php echo $creation['name']; ?></h2>
				<span>(<a href="edit.php?id=<?php echo $creation['id'];?>">back to edit</a>)</span>
			</div>
			<div class="versions">
				<table>
					<?php
					foreach($versions as $version){
						echo '<tr id="'.$version['number'].'" style="background-color:white;">
						<td class="versionrow" style="width:25px;">'.$version['number'].'</td>
						<td class="versionrow" style="width:30px;">'.$version['name'].'</td>
						<td class="versionrow" style="width:80px;">'.date("m/d/Y",strtotime($version['timestamp'])).'</td>';
						if($cur_version!=$version['number']){
						//don't forget to do the saved thing for current versions on the edit.php thing
							if($version['saved']==1){
								//TO-DO: get extension of version
								echo '<td class="versionrow" style="width:400px;"><a href="?mode=version&action=revert&id='.$version['number'].'">Revert</a> <a href="data/creations/old/">Download</a> <a href="?mode=version&action=delete&id='.$version['number'].'">Delete</a></td>';
							}
							else{
								echo '<td class="versionrow" style="width:400px;">'.$flag['content'].'<a class="deletebutton" href="?mode=version&action=delete&id='.$flag['id'].'"></a></td>';
							}
							echo '</tr>';
						}
					}
					?>
				
				</table>
			</div>
	</body>
</html>
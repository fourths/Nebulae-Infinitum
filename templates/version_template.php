<!DOCTYPE html>
<html>
	<head>
		<title>
			Versions of <?php echo $creation['name']; ?> | <?php echo SITE_NAME; ?>
		</title>
		<link rel="stylesheet" type="text/css" href="../../include/style.css" media="screen" />
	</head>
	
	<body>
		<?php require_once("header.php"); ?>
		<div class="container">
			<div class="editheader" style="margin-bottom:10px;">
				<h2 style="display:inline;">Versions of <?php echo $creation['name']; ?></h2>
				<span>(<a href="edit">back to edit</a>)</span>
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
								echo '<td class="versionrow" style="width:400px;"><a href="?id='.$creation['id'].'&mode=version&action=revert&aid='.$version['number'].'">Revert</a> <a href="data/creations/old/'.$filenames[$version['number']].'">Download</a> <a href="?id='.$creation['id'].'&mode=version&action=delete&aid='.$version['number'].'">Delete</a></td>';
							}
							else{
								echo '<td class="versionrow" style="width:400px;"></td>';
							}
							echo '</tr>';
						}
						else{
							echo '<td class="versionrow" style="width:400px;"></td>';
						}
					}
					?>
				
				</table>
			</div>
	</body>
</html>
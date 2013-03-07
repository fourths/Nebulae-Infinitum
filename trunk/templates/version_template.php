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
			<div class="editheader">
				<h2 style="display:inline;">Versions of <?php echo $creation['name']; ?></h2>
				<span>(<a href="edit.php?id=<?php echo $creation['id'];?>">back to edit</a>)</span>
			</div>
			<div class="versions">
				<?php
				foreach($versions as $version){
					print_r($version);
				}
				?>
				
				
			</div>
	</body>
</html>
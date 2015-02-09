<!DOCTYPE html>
<html>
	<head>
		<title>
			Creation hidden | <?php echo SITE_NAME ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
	</head>
	<body>
		<?php require_once( BASE_DIRECTORY . "/templates/header.php" ) ?>
		<div class="container">
			<h1>Creation hidden</h1>
			<div class="errorimage">
				<img src="errors/hidden.png"/>
			</div>
			<div class="errordescription">
				<? echo $creation['name'] ?> has been hidden by its owner. Sorry about that.<br/>
				<br/>
				<a href=".">Back to home</a>
			</div>
		</div>
	</body>
</html>
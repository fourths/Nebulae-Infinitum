<?php
header("HTTP/1.0 403 Forbidden");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			403 | <?php echo SITE_NAME ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL?>/include/style.css" media="screen" />
	</head>
	<body>
		<?php require_once(BASE_DIRECTORY."/templates/header.php") ?>
		<div class="container">
			<h1>403 error</h1>
			<div class="errorimage">
				<img src="<?php echo BASE_URL ?>/data/errors/403.png"/>
			</div>
			<div class="errordescription">
				You're not allowed in these parts, private. Stop looking around where you shouldn't if you know what's good for you.
				<br/><br/>
				<a href="<?php echo BASE_URL ?>/">Back to home</a>
			</div>
		</div>
	</body>
</html>
<?php
die();
?>
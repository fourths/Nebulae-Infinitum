<?php
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			404 | <?php echo SITE_NAME ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL?>/include/style.css" media="screen" />
	</head>
	<body>
		<?php require_once(BASE_DIRECTORY."/templates/header.php") ?>
		<div class="container">
			<h1>404 error</h1>
			<div class="errorimage">
				<img src="<?php echo BASE_URL ?>/data/errors/404.png"/>
			</div>
			<div class="errordescription">
				Whatever's supposed to be here isn't. Oops.
				<br/><br/>
				<a href="<?php echo BASE_URL ?>/">Back to home</a>
			</div>
		</div>
	</body>
</html>
<?php
die();
?>
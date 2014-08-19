<!DOCTYPE html>
<html>
	<head>
		<title>
			Account deleted | <?php echo SITE_NAME ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>/include/style.css" media="screen" />
	</head>
	<body>
		<?php require_once( BASE_DIRECTORY . "/templates/header.php" ); ?>
		<div class="container">
			<h1>Account deleted</h1>
			<div class="errorimage">
				<img src="<?php echo BASE_URL ?>/data/errors/delete.png"/>
			</div>
			<div class="errordescription">
				Your account has been deleted.<br/>
				<?php 
				//If there's a reason for the deletion in the database, display it
				if ( !empty( $user['banreason'] ) ){
					echo "<div>Reason:</div><div>" . $user['banreason'] . "</div>";
				}
				?>
				Contact the site administrator at <?php echo '<a href="mailto:' . ADMIN_EMAIL . '">' . ADMIN_EMAIL . '</a>';?> for more details.
				<br/><br/>
				<a href=".">Back to home</a>
			</div>
		</div>
	</body>
</html>
<?php
die();
?>
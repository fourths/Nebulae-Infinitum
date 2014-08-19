<!DOCTYPE html>
<html>
	<head>
		<title>
			Account banned | <?php echo SITE_NAME ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>/include/style.css" media="screen" />
	</head>
	<body>
		<?php require_once( BASE_DIRECTORY . "/templates/header.php" ) ?>
		<div class="container">
			<h1>Account banned</h1>
			<div class="errorimage">
				<img src="<?php echo BASE_URL ?>/data/errors/ban.png"/>
			</div>
			<div class="errordescription">
				Your account has been banned<?php
					//If there's a ban expiry date entered, output it
					if ( !empty( $user['banneduntil'] ) ) {
						echo " until " . $user['banneduntil'];
					}
					//Otherwise, crush the user's dreams
					else{
						echo " until further notice"; 
					}
					?>.<br/>
				<?php 
				//If there's a reason for why the user's banned entered, output it
				if ( !empty( $user['banreason'] ) ) {
					echo "<div>Reason:</div><div>" . $user['banreason'] . "</div>";
				}
				?>
				Contact the site administrator at <?php echo '<a href="mailto:' . ADMIN_EMAIL . '">' . ADMIN_EMAIL . '</a>';?> for more details.
				<br/>
				<a href="<?php echo BASE_URL ?>/">Back to home</a>
			</div>
		</div>
	</body>
</html>
<?php
die();
?>
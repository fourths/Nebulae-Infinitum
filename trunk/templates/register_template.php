<!DOCTYPE html>
<html>
	<head>
		<title>
			Register | <?php echo SITE_NAME ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="include/style.css" media="screen" />
	</head>

	<body>
		<?php
		require_once("header.php");
		?>
		<div class="container">
			<h1>
				Register
			</h1>
			
			<div>
				Fill out the form below to create a new account. 
				<?php
				if( isset( $_GET['returnto'] ) ){
					echo '<a href="login?returnto='.$_GET['returnto'].'">';
				}
				else{
					echo '<a href="login">';
				}
				?>
					But I already have an account!
				</a>
			</div>
			
			<br/>
			
			<form method="post">
				<label style="margin-right:5px;" for="user">Username:</label>
				<input type="text" name="user" value="<?php echo $_POST['user']; ?>"/>
				
				<br/>
				<label style="margin-right:8px;" for="pass">Password:</label>
				<input type="password" name="pass" />
				
				<br/>
				
				<label style="margin-right:20px;" for="cpass">Confirm:</label>
				<input type="password" name="cpass" />
				
				<br/>
				
				<label style="margin-right:32px;" for="email">Email:</label>
				<input type="email" name="email" value="<?php echo $_POST['email']; ?>"/>
				
				<br/>
				The following fields are optional.
				<br/>
				
				<label style="margin-right:43px;" for="age">Age:</label>
				<input type="text" name="age" value="<?php echo $_POST['age']; ?>"/>
				
				<br/>
				<?php
				switch($_POST['gender']){
					case 'm':
						$male='selected="selected"';
					break;
					case 'f':
						$female='selected="selected"';
					break;
					case 'o':
						$other='selected="selected"';
					break;
				}
				?>
				<label style="margin-right:20px;" for="gender">Gender:</label>
				<select name="gender">
					<option value=""> </option>
					<option value="m" <?php echo $male; ?>>Male</option>
					<option value="f" <?php echo $female; ?>>Female</option>
					<option value="o" <?php echo $other; ?>>Other</option>
				</select>
				
				<br/>
				
				<label style="margin-right:17px;" for="location">Location:</label>
				<input type="text" name="location" value="<?php echo $_POST['location']; ?>"/>
				
				<br/>
				<br/>
				
				<input type="submit" name="rsubmit" value="Register" />
			</form>
		</div>
	</body>
</html>
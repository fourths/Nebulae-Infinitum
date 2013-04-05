<!DOCTYPE html>
<?php
//Include config
$split_path=explode("\\",dirname(__FILE__));
for($k=0;$k<count($split_path);$k++){
	if($split_path[$k]=="errors"){
		for($m=count($split_path);$m>$k-1;$m--){
			$split_path[$m]="";
		}
	}
}
$full_path="";
foreach($split_path as $path_bit){
	if(strlen($path_bit)>0){
		$full_path .= $path_bit."\\";
	}
}
require_once($full_path."/config/config.php");
?>

<html>
	<head>
		<title>
			Account deleted | <?php echo SITE_NAME ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
	</head>
	<body>
		<?php require_once(BASE_DIRECTORY."/templates/header.php") ?>
		<div class="container">
			<h1>Account deleted</h1>
			<div class="errorimage">
				<img src="errors/delete.png"/>
			</div>
			<div class="errordescription">
				Your account has been deleted.<br/>
				<?php if (!empty($user['banreason'])) echo "<div>Reason:</div><div>".$user['banreason']."</div>"; ?>
				Contact the site administrator at <?php echo '<a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';?> for more details.
				<br/><br/>
				<a href=".">Back to home</a>
			</div>
		</div>
	</body>
</html>
<?php
die();
?>
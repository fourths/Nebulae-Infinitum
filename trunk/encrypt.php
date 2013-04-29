<!DOCTYPE html>
<?php
//Once you've set the password for your account, uncomment this block to prevent other users from viewing this page
/*if ($cur_user['rank'] != "admin"){
	require_once("errors/403.php");
}*/
?>
<html>
<head>
<title>Encrypt | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="../include/style.css" media="screen" />
</head>

<body>
<? require_once("templates/header.php"); ?>
<div class="container">
	<h2>Encrypt</h2>
	<form method="post" enctype="multipart/form-data">
	<input type="text" name="inputstring" style="margin-left:0px;" placeholder="String">
	<br/>
	<input type="submit" name="encrypt" value="Encrypt" style="margin-left:0px;"/>
	</form>
</body>
</html>
<?php
if (isset($_POST['encrypt'])){
	echo "Output: ".nebulae_hash($_POST['inputstring']);
}
?>
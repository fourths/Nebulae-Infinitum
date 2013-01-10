<!DOCTYPE html>
<? require_once("config/config.php");?>
<html>
<head>
<title>Delete creation | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<h2>Delete creation</h2>
	<div>
	Are you sure you want to delete <?=$creation['name']?>? This cannot easily be undone, so if you will want your creation again in the future, you should set it to hidden so that it can only be seen by your account.
	<form method="post" style="margin-top:20px;">
	<input type="submit" name="yes" value="I'm sure"  /> <input type="submit" name="no" value="Actually, nevermind" />
	</form>
	</div>
</body>
</html>
<!DOCTYPE html>
<? require_once("config/config.php"); ?>
<html>
<head>
<title><? echo $userdata['1'] ?>'s Creations | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />

</head>

<body>
<? require_once("templates/header.php") ?>
<div class="container">
<h1>Account deleted</h1>
<img src="errors/delete.png"/><br/>
<? echo $userdata['1'] ?>'s account has been deleted.<br/>
<br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
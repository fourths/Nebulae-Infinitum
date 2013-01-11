<!DOCTYPE html>
<? require_once("config/config.php"); ?>
<html>
<head>
<title><? echo $creation['name'] ?> | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."/templates/header.php") ?>
<div class="container">
<h1>Project hidden</h1>
<img src="errors/hidden.png"/><br/>
<? echo $creation['name'] ?> has been hidden by its owner. Sorry about that.<br/>
<br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
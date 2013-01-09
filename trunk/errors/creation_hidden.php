<!DOCTYPE html>
<? require_once(BASE_DIRECTORY."config/config.php"); ?>
<html>
<head>
<title><? echo $creation['1'] ?> | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."templates/header.php") ?>
<div class="container">
<h1>Project hidden</h1>
<img src="errors/hidden.png"/><br/>
<? echo $creation['1'] ?> has been hidden by its owner. Sorry about that.<br/>
<br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
<!DOCTYPE html>
<? require_once("config/config.php"); ?>
<html>
<head>
<title>Login | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
<h1>Login</h1>
<div>Enter your username and password to log in. <a href="?action=register">But I don't have an account!</a></div><br/>
<form method="post">
<label style="margin-right:5px;" for="user">Username:</label><input type="text" name="user" /><br/>
<label style="margin-right:8px;" for="pass">Password:</label><input type="password" name="pass" /><br/>
<input type="submit" name="submit" value="Log in" />
</form>
</body>
</html>
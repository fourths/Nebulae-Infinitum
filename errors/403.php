<!DOCTYPE html>
<? 
//Include config
require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/config/config.php"); 
error_reporting(E_ALL ^ E_NOTICE); 
session_start();
?>
<html>
<head>
<title>403 | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="/templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."templates/header.php") ?>
<div class="container">
<h1>403 error</h1>
<img src="/errors/403.png"/><br/>
You're not allowed in these parts, private. Stop looking around where you shouldn't if you know what's good for you.<br/><br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
<? die ?>
<!DOCTYPE html>
<?
//Include config
require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/config/config.php"); 
error_reporting(E_ALL ^ E_NOTICE); 
session_start();
?>
<html>
<head>
<title>404 | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="/templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."templates/header.php") ?>
<div class="container">
<h1>404 error</h1>
<img src="/errors/404.png"/><br/>
Whatever's supposed to be here isn't. Oops.<br/><br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
<? die ?>
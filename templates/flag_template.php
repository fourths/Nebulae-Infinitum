<!DOCTYPE html>
<? require_once("config/config.php");?>
<html>
<head>
<title>Flag <? echo $type ?> | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<h2>Flag <? echo $type ?></h2>
	<form method="post">
	<textarea name="flagtext" style="width:350px;height:100px;resize:none;font-family:Arial,Helvetica,sans-serif;" placeholder="Why are you flagging this <? echo $type ?>?"></textarea><br/>
	<input type="submit" name="flag" value="Flag" style="margin-left:0px;"/>
	<div style="font-size:10px;">If you're unsure what should be flagged, see <a href="info/flagging.php">here</a> for a guide.</div>
	</form>
</body>
</html>
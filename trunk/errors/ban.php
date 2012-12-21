<!DOCTYPE html>
<? require_once(BASE_DIRECTORY."config/config.php"); ?>
<html>
<head>
<title>Account banned | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."templates/header.php") ?>
<div class="container">
<h1>Account banned</h1>
<img src="errors/ban.png"/><br/>
Your account has been banned<? if (!empty($userdata[7])) echo " until $userdata[7]"; else echo " until further notice"; ?>.<br/>
<? if (!empty($userdata[15])) echo "<div>Reason:</div><div>$userdata[15]</div>"; ?>
Contact the site administrator at <? echo '<a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';?> for more details.
<br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
<? die ?>
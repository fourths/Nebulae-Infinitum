<!DOCTYPE html>
<? require_once(BASE_DIRECTORY."config/config.php"); ?>
<html>
<head>
<title>Account deleted | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."templates/header.php") ?>
<div class="container">
<h1>Account deleted</h1>
<img src="errors/delete.png"/><br/>
Your account has been deleted.<br/>
<? if (!empty($user[15])) echo "<div>Reason:</div><div>$user[15]</div>"; ?>
Contact the site administrator at <? echo '<a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';?> for more details.
<br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
<? die ?>
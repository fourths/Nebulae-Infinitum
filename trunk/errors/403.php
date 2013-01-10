<!DOCTYPE html>
<?
//Include config
$split_url=explode("\\",dirname(__FILE__));
if(count($split_url)>4){
	for($i=0;$i<count($split_url)-3;$i++){
		$split_url[count($split_url)-$i]="";
	}
}
$full_url="";
foreach($split_url as $url_bit){
	if(strlen($url_bit)>0){
		$full_url .= $url_bit."/";
	}
}
require_once($full_url."/config/config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();
?>
<html>
<head>
<title>403 | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="<? echo BASE_URL?>/templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."templates/header.php") ?>
<div class="container">
<h1>403 error</h1>
<img src="<? echo BASE_URL?>/errors/403.png"/><br/>
You're not allowed in these parts, private. Stop looking around where you shouldn't if you know what's good for you.<br/><br/>
<a href=".">Back to home</a>
</div>
</body>
</html>
<? die ?>
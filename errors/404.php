<?php header("HTTP/1.0 404 Not Found"); ?>
<!DOCTYPE html>
<?
//Include config
$split_path=explode("\\",dirname(__FILE__));
for($k=0;$k<count($split_path);$k++){
	if($split_path[$k]=="errors"){
		for($m=count($split_path);$m>$k-1;$m--){
			$split_path[$m]="";
		}
	}
}
$full_path="";
foreach($split_path as $path_bit){
	if(strlen($path_bit)>0){
		$full_path .= $path_bit."\\";
	}
}
require_once($full_path."\config\config.php");
error_reporting(E_ALL ^ E_NOTICE); 
session_start();
?>
<html>
<head>
<title>404 | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="<? echo BASE_URL?>/templates/style.php" media="screen" />

</head>

<body>
<? require_once(BASE_DIRECTORY."/templates/header.php") ?>
<div class="container">
<h1>404 error</h1>
<img src="<? echo BASE_URL ?>/errors/404.png"/><br/>
Whatever's supposed to be here isn't. Oops.<br/><br/>
<a href="<? echo BASE_URL ?>/">Back to home</a>
</div>
</body>
</html>
<? die ?>
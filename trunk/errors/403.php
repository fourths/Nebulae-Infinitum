<!DOCTYPE html>
<? 
//Include config
//NEEDS WORK
/*if (substr_count($_SERVER['REQUEST_URI'],"/")>0){
	$split_url=explode($_SERVER['REQUEST_URI'],"/");
	for($i=0;$i<count($split_url);$i++){
		if(count(explode($split_url[$i],"."))>0){
			$folder_levels=$i-1;
			break;
		}
	}
	$full_url="";
	for($j=0;$j<$folder_levels;$j++){
		$full_url.='/'.$split_url[$j];
		echo $split_url[$j];
	}
	echo $full_url."22";
}*/
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
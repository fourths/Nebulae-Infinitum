<!DOCTYPE html>
<html>
<head>
<title><?php echo $typetext ?> creations | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<h1 style="margin:0px;"><?php echo $typetext ?> creations<?php if($typetext=="Random")echo ' <a href="?mode=random" style="font-size:13px;">reload</a>'; //replace with cool arrow circle icon ?></h1>
	<h3 style="margin:0px;"><?php 
	if($mode!="newest")echo '<a href="?mode=newest">newest</a> ';
	if($mode!="views")echo '<a href="?mode=views">top viewed</a> ';
	if($mode!="rating")echo '<a href="?mode=rating">top rated</a> ';
	if($mode!="favourites")echo '<a href="?mode=favourites">most favourited</a> ';
	if($mode!="random")echo '<a href="?mode=random">random</a>';
	?></h3>
	<? displayCreations($creations,$luserdata,$admin); 
	if($previous) echo '<a style="display:block;float:left;font-size:16px;font-weight:bold;" href="creations.php?mode='.$mode.'&page='.($page-1).'">&laquo;previous</a>';
	if($next) echo '<a style="display:block;float:right;font-size:16px;font-weight:bold;" href="creations.php?mode='.$mode.'&page='.($page+1).'">next&raquo;</a>';
	?>
	<div style="clear:both;"></div>
</body>
</html>
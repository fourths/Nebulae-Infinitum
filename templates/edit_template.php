<!DOCTYPE html>
<? require_once("config/config.php");?>
<html>
<head>
<title>Edit creation | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<h2>Edit creation</h2>
	<form method="post" enctype="multipart/form-data">
	<input type="text" name="title" style="margin-left:0px;" placeholder="Title" value="<?=stripslashes($creationdata[1])?>">
	<div>
	<?php
	$selected='selected="selected"';
	switch($creationdata[14]){
		case 'copyright':
			$a=$selected;
			break;
		case 'cc-0':
			$b=$selected;
			break;
		case 'cc-by':
			$c=$selected;
			break;
		case 'cc-by-nd':
			$d=$selected;
			break;
		case 'cc-by-sa':
			$e=$selected;
			break;
		case 'cc-by-nc':
			$f=$selected;
			break;
		case 'cc-by-nc-nd':
			$g=$selected;
			break;
		case 'cc-by-nc-sa':
			$h=$selected;
			break;
		case 'mit':
			$i=$selected;
			break;
		case 'gpl':
			$j=$selected;
			break;
		case 'bsd':
			$k=$selected;	
			break;
	}
	?>
	<select name="license" style="margin-left:0px;margin-top:0px;">
	<option value="copyright" <?php echo $a ?>>Copyright</option>
	<option value="cc-0" <?php echo $b ?>>CC-0 / public domain</option>
	<option value="cc-by" <?php echo $c ?>>CC-BY</option>
	<option value="cc-by-nd" <?php echo $d ?>>CC-BY-ND</option>
	<option value="cc-by-sa" <?php echo $e ?>>CC-BY-SA</option>
	<option value="cc-by-nc" <?php echo $f ?>>CC-BY-NC</option>
	<option value="cc-by-nc-nd" <?php echo $g ?>>CC-BY-NC-ND</option>
	<option value="cc-by-nc-sa" <?php echo $h ?>>CC-BY-NC-SA</option>
	<?php if($creationdata[2]=="scratch"||$creationdata[2]=="flash"||$creationdata[2]=="writing") '<option value="mit" '.$i.'>MIT License</option>
	<option value="gpl" '.$j.'>GNU GPLv3</option>
	<option value="bsd" '.$k.'>New BSD License</option>'; ?>
	</select> (<a href="info/licenses.php">info</a>)
	</div>
	<textarea name="description" style="width:350px;height:100px;resize:none;font-family:Arial,Helvetica,sans-serif;" placeholder="Describe your creation..."><?=stripslashes($creationdata[9])?></textarea><br/>
	<textarea name="advisory" style="width:350px;height:50px;resize:none;font-family:Arial,Helvetica,sans-serif;" placeholder="Content advisory; this project includes..."><?=stripslashes($creationdata[10])?></textarea><br/>
	<?
	if ($creationdata[6] == "no" || $creationdata[6] == "approved") $nselected = 'selected="selected"';
	else if ($creationdata[6] == "byowner" || $creationdata[6] == "deleted") $hselected = 'selected="selected"';
	else if ($creationdata[6] == "censored" || $creationdata[6] == "flagged") $cselected = 'selected="selected"';
	?>
	<label for="hidden">Hidden?</label><br/>
	<select name="hidden" style="margin-left:0px;">
	<option value="no" <?=$nselected?>>Not hidden</option>
	<option value="byowner" <?=$hselected?>>Hidden</option>
	<? if ($luserdata[3]=="admin" || $luserdata[3]=="mod") echo '<option value="censored" '.$cselected.'>Censored</option>';?>
	</select><br/>
	<input type="submit" name="update" value="Update" style="margin-left:0px;"/>&nbsp;
	<? if ($creationdata[6] == "deleted") echo '<input type="submit" name="undelete" value="Undelete" />'; else echo '<input type="submit" name="delete" value="Delete" />'; ?>
	</form>
</body>
</html>
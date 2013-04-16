<!DOCTYPE html>
<?php 
//Auto-select the current license on the drop-down
$selected='selected="selected"';
switch($creation['license']){
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

if ($creation['hidden'] == "no" || $creation['hidden'] == "approved") $nselected = 'selected="selected"';
else if ($creation['hidden'] == "byowner" || $creation['hidden'] == "deleted") $hselected = 'selected="selected"';
else if ($creation['hidden'] == "censored" || $creation['hidden'] == "flagged") $cselected = 'selected="selected"';
?>
<html>
	<head>
		<title>
			Edit blog post | <?php echo SITE_NAME; ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
	</head>
	
	<body>
		<?php require_once("header.php"); ?>
		<div class="container">
			<div class="editheader">
				<h2 style="display:inline;">Edit blog post</h2>
			</div>
			<div>
				<input type="text" name="title" style="margin-left:0px;width:350px;" placeholder="Title" value="<?php echo stripslashes($blog_post['title'])?>"/>
			</div>
			
			<textarea name="post_body" style="width:795px;height:300px;max-height:600px;resize:vertical;font-family:Arial,Helvetica,sans-serif;display:block;" selected="selected"><?php
				echo stripslashes($blog_post['content']);
			?></textarea>
			
			<input type="submit" id="update" name="update" value="Update" style="margin-left:0px;"/>
			<?php
				if ($action="edit"){
					echo '<input type="submit" id="update" name="update" value="Update" style="margin-left:0px;"/>';
				}
			?>
			
	</body>
</html>
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
			Edit creation | <?php echo SITE_NAME; ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
		<script type="text/javascript">
			document.onkeypress = processKey;

			function processKey(e){
				if (null == e){
					e = window.event;
				}
				if (e.keyCode == 13){
					document.getElementById("update").click();
					return false;
				}
			}
		</script>
	</head>
	
	<body>
		<?php require_once("header.php"); ?>
		<div class="container">
			<div class="editheader">
				<h2 style="display:inline;">Edit creation</h2> 
				<?php 
				if ($cur_version>1){
				?>
					<span>(<a href="edit.php?id=<?php echo $creation['id'];?>&mode=version">see versions</a>)</span>
				<?php
				}
				?>
			</div>
			<form method="post" enctype="multipart/form-data">
				<div>
					<input type="text" name="title" style="margin-left:0px;" placeholder="Title" value="<?php echo stripslashes($creation['name'])?>"/> version 
					<input type="text" name="version" style="margin-left:0px;width:20px;" value="<?php echo stripslashes($version_name)?>"/>
				</div>
				<input type="file" name="creationfile" style="margin-left:0px;display:inline;" accept=".png,.gif,.jpg,.jpeg,.jpe,.bmp,.dib,.svg,.tif,.tiff,.sb,.sb2,.mp3,.swf,.txt"/>
				<input type="submit" name="upload" value="Upload" style="margin-left:0px;display:inline;"/>
				<div>The uploaded file will be version <input type="text" name="newversion" style="margin-left:0px;width:20px;" value="<?php echo stripslashes(1+$version_name.".0")?>"/></div>
				<div><input type="checkbox" name="copy" id="copy" value="save" /><label for="copy">Save a copy of the current version</label></div>
				<div>(<a href="info/filetypes.php">What filetypes are okay?</a>)</div>
				
				<div style="margin-top:5px;">
					<select name="license" style="margin-left:0px;margin-top:0px;">
						<option value="copyright" <?php echo $a ?>>Copyright</option>
						<option value="cc-0" <?php echo $b ?>>CC-0 / public domain</option>
						<option value="cc-by" <?php echo $c ?>>CC-BY</option>
						<option value="cc-by-nd" <?php echo $d ?>>CC-BY-ND</option>
						<option value="cc-by-sa" <?php echo $e ?>>CC-BY-SA</option>
						<option value="cc-by-nc" <?php echo $f ?>>CC-BY-NC</option>
						<option value="cc-by-nc-nd" <?php echo $g ?>>CC-BY-NC-ND</option>
						<option value="cc-by-nc-sa" <?php echo $h ?>>CC-BY-NC-SA</option>
						<?php if($creation['type']=="scratch"||$creation['type']=="flash"||$creation['type']=="writing") '<option value="mit" '.$i.'>MIT License</option>
						<option value="gpl" '.$j.'>GNU GPLv3</option>
						<option value="bsd" '.$k.'>New BSD License</option>'; ?>
						
					</select>
					(<a href="info/licenses.php">info</a>)
				</div>
				<textarea name="description" style="width:350px;height:100px;resize:none;font-family:Arial,Helvetica,sans-serif;display:block;" placeholder="Describe your creation..."><?php
					echo stripslashes($creation['descr']);
				?></textarea>
					
				<textarea name="advisory" style="width:350px;height:50px;resize:none;font-family:Arial,Helvetica,sans-serif;display:block;margin-top:2px;" placeholder="Content advisory; this project includes..."><?php
					echo stripslashes($creation['advisory']);
				?></textarea>
				
				<label for="hidden" style="display:block;margin-top:5px;">Hidden?</label>
				<select name="hidden" style="margin-left:0px;margin-top:0px;display:block;">
					<option value="no" <?php echo $nselected; ?>>Not hidden</option>
					<option value="byowner" <?php echo $hselected; ?>>Hidden</option>
					<?php if ($cur_user['rank']=="admin" || $cur_user['rank']=="mod") echo '<option value="censored" '.$cselected.'>Censored</option>';?>
					
				</select>
				
				<input type="submit" id="update" name="update" value="Update" style="margin-left:0px;"/>
				<?php
				if ($creation['hidden'] == "deleted"){
					echo '&nbsp;
				<input type="submit" name="undelete" value="Undelete" />';
				}
				else {
					echo '&nbsp;
				<input type="submit" name="delete" value="Delete" />';
				}
				?>
				
			</form>
	</body>
</html>
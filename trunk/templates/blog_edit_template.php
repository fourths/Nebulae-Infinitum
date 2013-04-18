<!DOCTYPE html>
<html>
	<head>
		<title><?php
				if ($action=="edit"){
					echo "Edit blog post";
				}
				else{
					echo "New blog post";
				}?> | <?php echo SITE_NAME; ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
	</head>
	
	<body>
		<?php require_once("header.php"); ?>
		<div class="container">
			<div class="editheader">
				<h2 style="display:inline;"><?php
					if ($action=="edit"){
						echo "Edit blog post";
					}
					else{
						echo "New blog post";
					}?></h2>
			</div>
			<form autocomplete="off" method="post">
				<div>
					<input type="text" name="title" style="margin-left:0px;width:350px;" placeholder="Title" value="<?php echo stripslashes($blog_post['title']); ?>"/>
				</div>
				
				<textarea name="post_body" style="width:795px;height:300px;max-height:600px;resize:vertical;font-family:Arial,Helvetica,sans-serif;display:block;" selected="selected"><?php
					echo stripslashes($blog_post['content']);
				?></textarea>
				<?php
					if ($action=="edit"){
						echo '<input type="submit" id="update" name="update" value="Update" style="margin-left:0px;"/>
						<input type="submit" id="delete" name="delete" value="Delete" style="margin-left:0px;"/>';
					}
					else {
						echo '<input type="submit" id="submit" name="submit" value="Submit" style="margin-left:0px;"/>';
					}
				?>
				
			</form>
	</body>
</html>
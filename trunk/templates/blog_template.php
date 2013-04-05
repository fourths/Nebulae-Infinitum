<html>
	<head>
		<title>
			<?php
				if($type=="admin"){
					$name="Site";
				}
				else{
					$name=$user['username']."'s";
				}
			?>
			<?php echo $name; ?> Blog | <?php echo SITE_NAME; ?>
		</title>
		<link rel="stylesheet" type="text/css" href="../templates/style.php" media="screen" />
	</head>
	<body>
		<? include_once("../templates/header.php");  ?>

		<div class="container">
			<div class="blog_header" style="float:left;width:580px;height:75px;background-color:white;margin-bottom:10px;margin-left:220px;">
				<?php
				if($type=="admin"){
					echo '<h1 style="margin-left:10px;">STAFF BLOG</h1>';
				}
				else{
					echo '<img style="border:1px solid black;height:73px;width:73px;display:inline;" src="../data/usericons/'.$user['id'].'.png"/><h2 style="margin-left:10px;display:inline;position:relative;top:-30px;">'.$user['username'].'\'s blog</h2>';
				}
				?>
			</div>
			<div class="blog_left" style="float:left;width:210px;min-height:600px;background-color:white;">
				<h2 style="margin:0px;margin-left:10px;margin-top:10px;">Posts</h2>
			</div>
			<div class="blog_right" style="float:right;width:580px;min-height:600px;background-color:white;">
			<?php
			foreach($posts as $post){
				echo '<div class="blog_post" style="margin:10px;padding:10px;background-color:gainsboro;position:relative;" id="post'.$post['postid'].'">
					<h2 style="margin:0px;display:inline;">'.$post['title'].'</h2> <a href="LINK GOES HERE" style="text-decoration:none;margin:5px;position:absolute;top:0px;right:0px;">permalink</a>';
				echo '<div class="blog_post_content">'.bbcode_parse_blog($post['content']).'</div>';
				// Inform the user of who the post is by if it's on the admin blog
				if($type=="admin"){
					echo '<div class="postedby" style="color:grey;">Posted by <a style="color:grey" href="../user.php?id='.$post['userid'].'">'.get_username_from_id($post['userid']).'</a> at '.date("g:ia m/d/y T",strtotime($post['timestamp'])).'</div>';
				}
				else{
					echo '<div class="postedby" style="color:grey;">Posted at '.date("g:ia m/d/y T",strtotime($post['timestamp'])).'</div>';
				}
				echo '</div>';
			}
			?>
			<div style="clear:both;"></div>
			</div>
			<div style="clear:both;height:1px;"></div>
		</div>
	</body>
</html>
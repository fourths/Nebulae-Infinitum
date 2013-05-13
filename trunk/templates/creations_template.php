<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php echo $typetext; ?> creations | <?php echo SITE_NAME; ?>
			
		</title>
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/include/style.css" media="screen" />
	</head>

	<body>
		<? require_once("header.php"); ?>
		<div class="container">
			<h1 style="margin:0px;"><?php echo $typetext; ?> creations<?php if($typetext=="Random") echo ' <a href="' . BASE_URL . '/creations/random/1" style="font-size:13px;">reload</a>'; //replace with cool arrow circle icon ?></h1>
			<h3 style="margin:0px;"><?php 
			if($mode!="newest") echo '<a href="' . BASE_URL . '/creations/newest/1">newest</a> ';
			if($mode!="views") echo '<a href="' . BASE_URL . '/creations/views/1">top viewed</a> ';
			if($mode!="rating") echo '<a href="' . BASE_URL . '/creations/rating/1">top rated</a> ';
			if($mode!="favourites") echo '<a href="' . BASE_URL . '/creations/favourites/1">most favourited</a> ';
			if($mode!="random") echo '<a href="' . BASE_URL . '/creations/random/1">random</a>';
			?></h3>
			<div style="margin:auto;">
				<?php displayCreations( $creations, $cur_user, $admin, $mysqli ); 
				if ( $mode != "random" ){
					if( $previous == true ){
						echo '<a style="display:block;float:left;font-size:16px;font-weight:bold;" href="' . BASE_URL . '/creations/' . $mode . '/' . ( $page - 1 ) . '">&laquo;previous</a>';
					}
					if( $next ){
						echo '<a style="display:block;float:right;font-size:16px;font-weight:bold;" href="' . BASE_URL . '/creations/' . $mode . '/' . ( $page + 1 ) . '">next&raquo;</a>';
					}
				}
				?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</body>
</html>
<html>
	<head>
		<title>
			Allowed filetypes | <?php echo SITE_NAME; ?>
		</title>
		<link rel="stylesheet" type="text/css" href="../include/style.css" media="screen" />
	</head>
	<body>
		<?php include_once( BASE_DIRECTORY . "/templates/header.php"); ?>

		<div class="container" style="padding-bottom:20px;">
			<h1 style="display:inline;margin-left:0px;">Allowed filetypes</h1>
			<div style="padding-top:10px;font-size:12px;">
				<?php echo SITE_NAME; ?> allows its users to upload a variety of different media types.<br/>
				Currently, we support the following filetypes:
				<ul>
					<li>PNG images</li>
					<li>GIF images and animations</li>
					<li>JPEG images</li>
					<li>TIFF images</li>
					<li>BMP images</li>
					<li>SVG vector images</li>
					<li>MP3 audio</li>
					<li>Raw text files, extension TXT</li>
					<li>SWF Flash files</li>
					<li>Scratch 1.0-1.4 files, extension SB</li>
					<li>Scratch 2.0 files, extension SB2</li>
				</ul>
				We are also planning support for the following types in the near future:
				<ul>
					<?//<li>Processing sketches, ran with Processing.js (extension PDE or PJS)</li>-->?>
					<li>Rich text files, extension RTF</li>
					<li>An on-site text editor with possible BBCode support</li>
				</ul>
				<a href="../upload">Back to upload</a>
			</div>
		</div>
	</body>
</html>
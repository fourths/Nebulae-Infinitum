<!DOCTYPE html>
<html>
	<head>
		<title>
			Upload | <?php echo SITE_NAME; ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="include/style.css" media="screen" />
		<script type="text/javascript">
			function thumb_test() {
				if ( !document.getElementById( "creationUpload" ).value ) {
					alert( "Please select a file to upload." );
				}
				else {
					var end = document.getElementById( "creationUpload" ).value.substr( document.getElementById( "creationUpload" ).value.length-3, document.getElementById( "creationUpload" ).value.length);
					if ( end != "jpg" && end != "jpe" && end != "peg" && end != "png" && end != "gif" && end != "bmp" && end != "tif" && end != "iff" && end != "txt" && end != "dib" ) {
						if ( !document.getElementById( "thumbnailUpload" ).value ) {
							alert( "Please select a thumbnail for your creation." );
						}
						else {
							document.getElementById("uploadForm").submit();
						}
					}
					else {
						document.getElementById("uploadForm").submit();
					}
				}
			}
		</script>
	</head>
	<body>
	<?php
	require_once("header.php");
	?>
		<div class="container">
			<h2>Upload creation</h2>
			<form method="post" enctype="multipart/form-data" id="uploadForm">
			<input type="text" name="title" placeholder="Title"/><br/>
			<input type="file" name="creationfile" id="creationUpload" accept=".png,.apng,.gif,.jpg,.jpeg,.jpe,.bmp,.dib,.svg,.tif,.tiff,.sb,.scratch,.sb2,.mp3,.swf,.txt"/><?php // by extension
			//incomplete mime type list
		/*application/x-shockwave-flash,application/x-scratch-project,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed,audio/mpeg,audio/x-mpeg,audio/mpeg3,audio/x-mpeg-3,video/mpeg,video/x-mpeg,image/bmp,image/png,video/png,image/x-png,image/tiff,image/x-tiff,image/x-jpeg,image/pjpeg,image/gif,image/jpeg,image/x-gif,image/svg+xml,text/plain */
			?>
			<br/>(<a href="about/filetypes">What filetypes are okay?</a>)<br/>
			<input type="file" name="thumbnail" id="thumbnailUpload" accept=".png,.gif,.jpg,.jpeg,.jpe,.bmp,.dib">
			<br/>Thumbnail (optional for text or image creations)<br/>
			<div>
			Licensed under 
			<select name="license" style="margin-left:0px;margin-top:0px;">
			<option value="copyright">Copyright</option>
			<option value="cc-0">CC-0 / public domain</option>
			<option value="cc-by">CC-BY</option>
			<option value="cc-by-nd">CC-BY-ND</option>
			<option value="cc-by-sa">CC-BY-SA</option>
			<option value="cc-by-nc">CC-BY-NC</option>
			<option value="cc-by-nc-nd">CC-BY-NC-ND</option>
			<option value="cc-by-nc-sa">CC-BY-NC-SA</option>
			<option value="mit">MIT License</option>
			<option value="gpl">GNU GPLv3</option>
			<option value="bsd">New BSD License</option>
			</select> (<a href="about/licenses">info</a>)
			</div>
			<textarea name="description" style="width:350px;height:100px;resize:none;font-family:Arial,Helvetica,sans-serif;" placeholder="Describe your creation..."></textarea><br/>
			<textarea name="advisory" style="width:350px;height:50px;resize:none;font-family:Arial,Helvetica,sans-serif;" placeholder="Content advisory; this project includes..."></textarea><br/>
			<input type="button" name="submit" onclick="thumb_test()" id="submitButton" value="Submit" />
			</form>
		</div>
	</body>
</html>
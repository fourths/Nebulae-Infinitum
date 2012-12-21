<!DOCTYPE html>
<html>
<head>
<title><?=stripslashes($creationdata[1]).' | '.SITE_NAME?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
</head>
<body>
<?php
switch($creationdata[2]){
	case "artwork":
		echo '<img src="data/projects/'.$creationdata[8].'" style="position:absolute;left:0px;top:0px;"/>';
		break;
	case "flash":
		if ($flashtype=="play"){
			$swfsize=getimagesize('data/projects/'.$creationdata[8]);
			echo '
			<div ><object <!--style="width:'.$swfsize[0].'px;height:'.$swfsize[1].'px;position:absolute;top:-webkit-calc(50% - ('.$swfsize[1] .'px/2));left:-webkit-calc(50% - ('.$swfsize[0] .'px/2));top:-moz-calc(50% - ('.$swfsize[1] .'px/2));left:-moz-calc(50% - ('.$swfsize[0] .'px/2));top:calc(50% - ('.$swfsize[1] .'px/2));left:calc(50% - ('.$swfsize[0] .'px/2));"--> class="flash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="editorObj" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="data/projects/'.$creationdata[8].'" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<embed id="editor" src="data/projects/'.$creationdata[8].'" quality="high" bgcolor="#ffffff"
				scale="exactfit"
				play="true"
				loop="false"
				quality="high"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer">
			</embed>
			</object></div>';
		}
		else {
			header('Content-type: application/x-shockwave-flash');
			header('Content-Disposition: attachment; filename="'.$creationdata[1].'.'.$creationdata[7].'"');
			header("Content-Length: " . filesize('data/projects/'.$creationdata[8]));
			readfile('data/projects/'.$creationdata[8]);
		}
		break;
	case "writing":
		echo "<meta http-equiv='Refresh' content='0; URL=data/projects/".$creationdata[8]."'>";
		break;
	default:
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="'.$creationdata[1].'.'.$creationdata[7].'"');
		header("Content-Length: " . filesize('data/projects/'.$creationdata[8]));
		readfile('data/projects/'.$creationdata[8]);
		break;
}
?>
</body>
</html>
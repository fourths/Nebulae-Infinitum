<!DOCTYPE html>
<? require_once("config/config.php"); ?>
<html>
<head>
<title><? echo $userdata[1] ?>'s Creations | <? echo SITE_NAME ?></title>
<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
<script src="data/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
// from lessthanweb.com thx guys
/* <![CDATA[ */
$(document).ready(function(){
	$("#tabs li").click(function() {
		//	First remove class "active" from currently active tab
		$("#tabs li").removeClass('active');

		//	Now add class "active" to the selected/clicked tab
		$(this).addClass("active");

		//	Hide all tab content
		$(".tab_content").hide();

		//	Here we get the href value of the selected tab
		var selected_tab = $(this).find("a").attr("href");

		//	Show the selected tab content
		$(selected_tab).fadeIn();

		//	At the end, we add return false so that the click on the link is not executed
		return false;
	});
});
/* ]]> */
</script>

</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<div class="left">
		<?
		if (!empty($userdata[9])) echo '<img class="usericon" src="data/usericons/'.$userdata[9].'"/>';
		else echo '<img class="usericon" src="data/usericons/default.png"/>'
		?>
		<div style="font-size:18px;"><? echo $userdata[1]; if ($userdata[3]=="admin" || $userdata[3]=="mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';?></div>
		<? 
		if ($userdata[6] == "banned") echo '<div style="color:red;">Banned</div>';
		if ($_SESSION['SESS_MEMBER_ID'] == $userdata[0] || $luserdata[3] == "admin" || $luserdata[3] == "mod") echo '<div><a href="pref.php?id='.$userdata[0].'">User preferences</a></div>';
		?>
		<div><? if (!empty($userdata[11])){ echo stripslashes($userdata[11]).' years old'; if ($userdata[12]=="m" || $userdata[12]=="f") echo ", "; } if ($userdata[12]=="m") echo "male"; if ($userdata[12]=="f") echo "female"; ?></div>
		<div>Registered <? echo date("F j, Y", strtotime($userdata[2]));?></div>
		<div><?if (!empty($userdata[5])) echo "Lives in ".stripslashes($userdata[5]); ?></div>
		<?
		if (!empty($luserdata)&&$luserdata[0]!=$userdata[0]){
			echo '
			<form method="post" style="position:relative;top:10px;left:-5px;">
			<textarea name="pmbody" placeholder="Message" style="height:100px;width:180px;max-height:200px;margin-left:2px;"></textarea>
			<br/>
			<input type="submit" name="pmsubmit" value="Submit"/>
			</form>
			';
		}
		?>
	</div>
	<div id="tabs_wrapper">
		<div id="tabs_container">
			<ul id="tabs">
				<li class="active"><a href="#overview">Overview</a></li>
				<li><a href="#writing">Writing</a></li>
				<li><a href="#artwork">Artwork</a></li>
				<li><a href="#audio">Audio</a></li>
				<li><a href="#other">Other</a></li>
			</ul>
		</div>
		<div id="tabs_content_container">
			<div id="overview" class="tab_content" style="display: block;">
				<? $aboutme = strval($userdata[10]); if (!empty($aboutme)) echo "<h2>About Me</h2><div>".stripslashes($aboutme)."</div>" ?>
				<h2>Favourites</h2>
				<div>
				<?show_creations($favourites,$luserdata,$userdata,true);?>
				</div>
			</div>
			<div id="writing" class="tab_content">
				<?show_creations($writing,$luserdata,$userdata,false);?>
			</div>
			<div id="artwork" class="tab_content">
				<?show_creations($artwork,$luserdata,$userdata,false);?>
			</div>
			<div id="audio" class="tab_content">
				<?show_creations($audio,$luserdata,$userdata,false);?>
			</div>
			<div id="other" class="tab_content">
				<?show_creations($other,$luserdata,$userdata,false);?>
			</div>
		</div>
</div>
<div style="clear:both;width:100%;height:5px;"></div>
</body>
</html>
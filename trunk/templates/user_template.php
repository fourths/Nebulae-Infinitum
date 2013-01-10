<!DOCTYPE html>
<? require_once("config/config.php"); ?>
<html>
<head>
<title><? echo $user['username'] ?>'s Creations | <? echo SITE_NAME ?></title>
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
		if (!empty($user['icon'])) echo '<img class="usericon" src="data/usericons/'.$user['icon'].'"/>';
		else echo '<img class="usericon" src="data/usericons/default.png"/>'
		?>
		<div style="font-size:18px;"><? echo $user['username']; if ($user['rank']=="admin" || $user['rank']=="mod") echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';?></div>
		<? 
		if ($user['banstatus'] == "banned") echo '<div style="color:red;">Banned</div>';
		if ($_SESSION['SESS_MEMBER_ID'] == $user['id'] || $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod") echo '<div><a href="pref.php?id='.$user['id'].'">User preferences</a></div>';
		?>
		<div><? if (!empty($user['age'])){ echo stripslashes($user['age']).' years old'; if ($user['gender']=="m" || $user['gender']=="f") echo ", "; } if ($user['gender']=="m") echo "male"; if ($user['gender']=="f") echo "female"; ?></div>
		<div>Registered <? echo date("F j, Y", strtotime($user['registered']));?></div>
		<div><?if (!empty($user['location'])) echo "Lives in ".stripslashes($user['location']); ?></div>
		<?
		if (!empty($cur_user)&&$cur_user['id']!=$user['id']){
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
				<? $aboutme = strval($user['about']); if (!empty($aboutme)) echo "<h2>About Me</h2><div>".stripslashes($aboutme)."</div>" ?>
				<h2>Favourites</h2>
				<div>
				<?show_creations($favourites,$cur_user,$user,true);?>
				</div>
			</div>
			<div id="writing" class="tab_content">
				<?show_creations($writing,$cur_user,$user);?>
			</div>
			<div id="artwork" class="tab_content">
				<?show_creations($artwork,$cur_user,$user);?>
			</div>
			<div id="audio" class="tab_content">
				<?show_creations($audio,$cur_user,$user);?>
			</div>
			<div id="other" class="tab_content">
				<?show_creations($other,$cur_user,$user);?>
			</div>
		</div>
</div>
<div style="clear:both;width:100%;height:5px;"></div>
</body>
</html>
<!DOCTYPE html>
<? require_once("config/config.php");?>
<html>
<head>
<title><? echo $userdata['1'] ?>'s Preferences | <? echo SITE_NAME ?></title>
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
		if ($(this).find("a").attr("href")!=<?echo '"user.php?id='.$_GET["id"].'"'?>) return false;
	});
});
/* ]]> */
</script>
</head>

<body>
<? require_once("header.php"); ?>
<div class="container">
	<div><? echo $userdata['1'] ?>'s Preferences</div><br/>
	<div id="tabs_wrapper" style="width:800px;float:none;">
		<div id="tabs_container">
			<ul id="tabs">
				<li class="active"><a href="#general">General</a></li>
				<li><a href="#userpage">Userpage</a></li>
				<? if ($luserdata['3'] == "admin" || $luserdata['3'] == "mod") echo '<li><a href="#admin">Admin</a></li>' ?>
				<? echo '<li><a href="user.php?id='.$_GET["id"].'">Back</a></li>' ?>
			</ul>
		</div>
		<div id="tabs_content_container" style="width:778px;min-height:355px;">
			<div id="general" class="tab_content" style="display: block;">
				<div>Change password:</div>
				<form method="post">
				<label for="curpass">Current:</label> <input type="password" name="curpass" style="margin-left:5px"/><br/>
				<label for="newpass">New:</label> <input type="password" name="newpass" style="margin-left:21px" /><br/>
				<label for="cnewpass">Confirm:</label> <input type="password" name="cnewpass" />
				<br/>
				<input type="submit" class="submitbutton" name="passchange" value="Submit" /><br/>
				</form>
				<br/>
				<div>Change email:</div>
				<form method="post">
				<label for="newemail">New:</label> <input type="email" name="newemail" style="margin-left:21px" /><br/>
				<input type="submit" name="emailchange" value="Submit" /><br/>
				</form>
				<br/>
				<div>Change icon:</div>
				<br/>
				<div class="prefsicon">Current icon:<br/>
				<?
				if (!empty($userdata['9'])) echo '<img class="prefsicon" src="data/usericons/'.$userdata['9'].'"/>';
				else echo '<img class="prefsicon" src="data/usericons/default.png"/>';
				?>
				</div>
				<div style="position:relative; left:10px;">
				<form method="post" enctype="multipart/form-data">
				Upload a new icon:<br/>
				<input type="file" name="newicon" accept="image/*"><br/>
				<input type="submit" name="iconchange" value="Submit" />
				</div>
			</div>
			<div id="userpage" class="tab_content">
				<div>User information:</div>
				<form method="post">
				<label for="age">Age:</label> <input type="text" name="age" style="margin-left:26px" value="<?= $userdata[11] ?>"/><br/>
				<label style="margin-right:4px;" for="gender">Gender:</label>
				<select name="gender">
				<option value=""> </option>
				<?
				if ($userdata[12] == "m") $mlselected = 'selected="selected"';
				else if ($userdata[12] == "f") $fselected = 'selected="selected"';
				else if ($userdata[12] == "o") $oselected = 'selected="selected"';
				?>
				<option value="m" <?= $mlselected ?>>Male</option>
				<option value="f" <?= $fselected ?>>Female</option>
				<option value="o" <?= $oselected ?>>Other</option>
				</select><br/>
				<label for="location">Location:</label> <input type="text" name="location" style="margin-left:0px" value="<?= $userdata[5] ?>"/><br/>
				<label for="about">About Me:</label><br/>
				<textarea name="about" rows="10" cols="30" style="max-width:500px;max-height:200px;"><?= $userdata[10] ?></textarea>
				<br/>
				<input type="submit" name="userchange" value="Submit" /><br/>
				</form>
				<br/>
			</div>
			<? if ($luserdata['3'] == "admin" || $luserdata['3'] == "mod") echo '<div id="admin" class="tab_content">
				<div>Admin preferences:</div><br/>
				<form method="post">'; 
				$banlength = date("z",strtotime($userdata[7])-strtotime($userdata[8])) + ((date("Y",strtotime($userdata[7])-strtotime($userdata[8]))-1970)*365);
				if ($banlength==-15592) $banlength=0;
				if ($userdata[3] == "user") $uselected = 'selected="selected"';
				else if ($userdata[3] == "mod") $mselected = 'selected="selected"';
				else if ($userdata[3] == "admin") $aselected = 'selected="selected"';
				if ($luserdata['3'] == "admin") echo '<label for="rank">Rank:</label>
				<select name="rank">
				<option value="user" '.$uselected.'>User</option>
				<option value="mod" '.$mselected.'>Mod</option>
				<option value="admin" '.$aselected.'>Admin</option>
				</select><br/>';
				if ($userdata[6] == "unbanned") $usselected = 'checked="checked"';
				else if ($userdata[6] == "banned") $bselected = 'checked="checked"';
				else if ($userdata[6] == "deleted") $dselected = 'checked="checked"';
				if ($luserdata['3'] == "admin" || $luserdata['3'] == "mod") echo '
				<label for="ban">Ban status:</label><br/>
				<input type="radio" name="ban" value="unbanned" '.$usselected.'/> Unbanned<br/>
				<input type="radio" name="ban" value="banned" '.$bselected.'/> Banned<br/>
				<input type="radio" name="ban" value="deleted" '.$dselected.'/> Deleted (hidden)<br/>
				<label for="banneduntil">Days banned for:</label><br/> <input type="text" name="banneduntil" style="margin-left:0px" value="'.$banlength.'"/><br/>
				(expires on '.date("M d Y",strtotime($userdata[7])).')
				<br/>
				<label for="banreason">Ban reason:</label><br/>
				<textarea name="banreason" rows="5" cols="30" style="max-width:500px;max-height:200px;">'.$userdata[15].'</textarea>
				<br/>
				<input type="submit" name="adminchange" value="Submit" /><br/>
				</form>
				<br/>
			</div>
			';
			?></div>
		</div>
</body>
</html>
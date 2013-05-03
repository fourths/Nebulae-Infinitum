<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php echo $user['username']; ?>'s Preferences | <?php echo SITE_NAME; ?>
		
		</title>
		
		<link rel="stylesheet" type="text/css" href="../../include/style.css" media="screen" />
		
		<script src="../../data/jquery.js" type="text/javascript"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				tabs_content = {"general":[],"userpage":[],"admin":[]};
				pages = document.getElementsByClassName("tab_content");
				for(i=0;i<pages.length;i++){
					tabs_content[pages[i].getAttribute("name")][pages[i].getAttribute("data-page")] = pages[i].innerHTML;
				}
				if(typeof window.location.hash.substring(1) == "undefined"||!window.location.hash.substring(1)){
					set_tab("general",0);
				}
				else{
					set_tab(window.location.hash.substring(1),0);
				}
			});

			function set_tab(new_tab,page){
				document.getElementById("tabs_content_container").innerHTML=tabs_content[new_tab][page];
				
				if(typeof current_tab == "undefined" || current_tab != new_tab){
					if (typeof current_tab != "undefined"){
						document.getElementById(current_tab+"_tab").className="";
					}
					current_tab=new_tab;
					document.getElementById(new_tab+"_tab").className="active";
				}
			}

			if ("onhashchange" in window){
				window.onhashchange = function () {
					new_tab = window.location.hash.substring(1);
					if(current_tab != new_tab){
						set_tab(new_tab,0);
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
		<div><?php echo $user['username']; ?>'s Preferences</div><br/>
		<div id="tabs_wrapper" style="width:800px;float:none;">
			<div id="tabs_container">
				<ul id="tabs">
					<li id="general_tab"><a href="#general">General</a></li>
					<li id="userpage_tab"><a href="#userpage">Userpage</a></li>
					<?php
					if ($cur_user['rank'] == "admin" || $cur_user['rank'] == "mod"){
						echo '<li id="admin_tab"><a href="#admin">Admin</a></li>';
					}
					?>
					<li><a href=".">Back</a></li>
				</ul>
			</div>
			<div id="tabs_content_container" style="width:778px;min-height:355px;">
				
				
			</div>
			<div style="display:none;">
			<div data-page="0" name="general" class="tab_content">
					<div style="text-decoration:underline;">Change password</div>
					<form method="post">
					<label for="curpass">Current:</label> <input type="password" name="curpass" style="margin-left:5px"/><br/>
					<label for="newpass">New:</label> <input type="password" name="newpass" style="margin-left:21px" /><br/>
					<label for="cnewpass">Confirm:</label> <input type="password" name="cnewpass" />
					<br/>
					<input type="submit" class="submitbutton" name="passchange" value="Submit" /><br/>
					</form>
					<br/>
					<div style="text-decoration:underline;">Change email</div>
					<form method="post">
					<label for="newemail">New:</label> <input type="email" name="newemail" style="margin-left:21px" /><br/>
					<input type="submit" name="emailchange" value="Submit" /><br/>
					</form><br/>
					<?php
					if($user['notifications']=="all"||$user['notifications']=="noreplies") $com_selected='checked="checked"';
					if($user['notifications']=="all"||$user['notifications']=="nocomments") $rep_selected='checked="checked"';
					?>
					<div style="text-decoration:underline;">Notifications settings</div>
					<form method="post">
					Send a notification when...<br/>
					<input type="checkbox" name="notifications[0]" id="comments" value="comments" <?php echo $com_selected; ?> /><label for="comments">...someone comments on one of my creations</label><br/>
					<input type="checkbox" name="notifications[1]" id="replies" value="replies" <?php echo $rep_selected; ?>/><label for="replies">...someone replies to a comment I've made</label><br/>
					<input type="submit" name="notificationchange" value="Submit" />
					</form>
					<br/>
					<div style="text-decoration:underline;">Change icon</div>
					<div class="prefsicon">Current icon:<br/>
					<?php
					if (!empty($user['icon'])) echo '<img class="prefsicon" src="../../data/usericons/'.$user['icon'].'"/>';
					else echo '<img class="prefsicon" src="../../data/usericons/default.png"/>';
					?>
					</div>
					<div style="position:relative; left:10px;">
					<form method="post" enctype="multipart/form-data">
					Upload a new icon:<br/>
					<input type="file" name="newicon" accept="image/*"><br/>
					<input type="submit" name="iconchange" value="Submit" />
					<div style="clear:both;"></div>
					</form>
					</div>
				</div>
				<div data-page="0" name="userpage" class="tab_content">
					<div>User information:</div>
					<form method="post">
					<label for="age">Age:</label> <input type="text" name="age" style="margin-left:26px" value="<?php echo $user['age']; ?>"/><br/>
					<label style="margin-right:4px;" for="gender">Gender:</label>
					<select name="gender">
					<option value=""> </option>
					<?php
					if ($user['gender'] == "m") $mlselected = 'selected="selected"';
					else if ($user['gender'] == "f") $fselected = 'selected="selected"';
					else if ($user['gender'] == "o") $oselected = 'selected="selected"';
					?>
					<option value="m" <?php echo $mlselected; ?>>Male</option>
					<option value="f" <?php echo $fselected; ?>>Female</option>
					<option value="o" <?php echo $oselected; ?>>Other</option>
					</select><br/>
					<label for="location">Location:</label> <input type="text" name="location" style="margin-left:0px" value="<?php echo $user['location']; ?>"/><br/>
					<label for="about">About Me:</label><br/>
					<textarea name="about" rows="10" cols="30" style="max-width:500px;max-height:200px;"><?php echo $user['about']; ?></textarea>
					<br/>
					<input type="submit" name="userchange" value="Submit" /><br/>
					</form>
					<br/>
				</div>
				<?php
				if ($cur_user['rank'] == "admin" || $cur_user['rank'] == "mod") echo '<div data-page="0" name="admin" class="tab_content">
				<div>Admin preferences:</div><br/>
				<form method="post">'; 
				$banlength = date("z",strtotime($user['banneduntil'])-strtotime($user['bandate'])) + ((date("Y",strtotime($user['banneduntil'])-strtotime($user['bandate']))-1970)*365);
				if ($banlength==-15592) $banlength=0;
				if ($user['rank'] == "user") $uselected = 'selected="selected"';
				else if ($user['rank'] == "mod") $mselected = 'selected="selected"';
				else if ($user['rank'] == "admin") $aselected = 'selected="selected"';
				if ($cur_user['rank'] == "admin") echo '<label for="rank">Rank:</label>
				<select name="rank">
				<option value="user" '.$uselected.'>User</option>
				<option value="mod" '.$mselected.'>Mod</option>
				<option value="admin" '.$aselected.'>Admin</option>
				</select><br/>';
				if ($user['banstatus'] == "unbanned") $usselected = 'checked="checked"';
				else if ($user['banstatus'] == "banned") $bselected = 'checked="checked"';
				else if ($user['banstatus'] == "deleted") $dselected = 'checked="checked"';
				if ($cur_user['rank'] == "admin" || $cur_user['rank'] == "mod") echo '
					<label for="ban">Ban status:</label><br/>
					<input type="radio" name="ban" value="unbanned" '.$usselected.'/> Unbanned<br/>
					<input type="radio" name="ban" value="banned" '.$bselected.'/> Banned<br/>
					<input type="radio" name="ban" value="deleted" '.$dselected.'/> Deleted (hidden)<br/>
					<label for="banneduntil">Days banned for:</label><br/> <input type="text" name="banneduntil" style="margin-left:0px" value="'.$banlength.'"/><br/>
					(expires on '.date("M d Y",strtotime($user['banneduntil'])).')
					<br/>
					<label for="banreason">Ban reason:</label><br/>
					<textarea name="banreason" rows="5" cols="30" style="max-width:500px;max-height:200px;">'.$user[15].'</textarea>
					<br/>
					<input type="submit" name="adminchange" value="Submit" /><br/>
					</form>
					<br/>
				</div>
				';
				?>
				
				</div>
		</div>
	</body>
</html>
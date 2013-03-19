<!DOCTYPE html>
<?php
require_once("config/config.php"); 
error_reporting(E_ALL ^ E_NOTICE);
?>
<html>
	<head>
		<title>
			<?php echo $user['username']; ?>'s Creations | <?php echo SITE_NAME; ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="templates/style.php" media="screen" />
		
		<script src="data/jquery.js" type="text/javascript"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				tabs_content = {"overview":[],"writing":[],"artwork":[],"audio":[],"other":[]};
				pages = document.getElementsByClassName("tab_content");
				for(i=0;i<pages.length;i++){
					tabs_content[pages[i].getAttribute("name")][pages[i].getAttribute("data-page")] = pages[i].innerHTML;
				}
				set_tab("overview",0);
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
			<div class="left">
				<?php
				if (!empty($user['icon'])) echo '<img class="usericon" src="data/usericons/'.$user['icon'].'"/>';
				else echo '<img class="usericon" src="data/usericons/default.png"/>'
				?>
				<div style="font-size:18px;">
					<?php 
					echo $user['username'];
					if ($user['rank']=="admin" || $user['rank']=="mod"){
						echo '<a href="info/staff.php" style="text-decoration:none;">'.STAFF_SYMBOL.'</a>';
					}
					?>
				</div>
				<?php
				if ($user['banstatus'] == "banned"){
					echo '<div style="color:red;">Banned</div>';
				}
				if ($_SESSION['SESS_MEMBER_ID'] == $user['id'] || $cur_user['rank'] == "admin" || $cur_user['rank'] == "mod"){
					echo '<div><a href="pref.php?id='.$user['id'].'">User preferences</a></div>';
				}
				?>
				
				<div>
					<?php 
					if (!empty($user['age'])){ 
						echo stripslashes($user['age']).' years old'; 
						if ($user['gender']=="m" || $user['gender']=="f"){
							echo ", "; 
						}
					} 
					if ($user['gender']=="m"){
						echo "male";
					}
					else if ($user['gender']=="f"){
						echo "female";
					}
					?>
				
				</div>
				<div>
					Registered <?php echo date("F j, Y", strtotime($user['registered']));?>
				</div>
				<div>
					<?php 
					if (!empty($user['location'])){
						echo "Lives in ".stripslashes($user['location']);
					}
					?>
				
				</div>
				<?
				if (!empty($cur_user)&&$cur_user['id']!=$user['id']){
					echo '
					<form method="post" style="position:relative;top:10px;left:-5px;">
					<textarea name="pmbody" placeholder="Message" style="height:100px;width:180px;max-height:200px;max-width:180px;margin-left:2px;resize:vertical;"></textarea>
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
						<li id="overview_tab" class="active"><a href="#overview">Overview</a></li>
						<li id="writing_tab"><a href="#writing">Writing</a></li>
						<li id="artwork_tab"><a href="#artwork">Artwork</a></li>
						<li id="audio_tab"><a href="#audio">Audio</a></li>
						<li id="other_tab"><a href="#other">Other</a></li>
					</ul>
				</div>
				<div id="tabs_content_container">
					
				</div>
				
				<div style="display:none;" id="tabs_content_data">
					<div data-page="0" name="overview" class="tab_content">
						<?php
						$aboutme = strval($user['about']);
						if (!empty($aboutme)){
							echo "<h2>About Me</h2><div>".bbcode_parse(stripslashes($aboutme))."</div>";
						}
						?>
						
						<h2>Favourites</h2>
						<div>
						<?php
						show_creations($favourites,$cur_user,$user,true);
						?>
						
						</div>
					</div>
					<div data-page="0" name="writing" class="tab_content">
						<?php
						show_creations($writing,$cur_user,$user);
						?>
						
					</div>
					<div data-page="0" name="artwork" class="tab_content">
						<?php 
						show_creations($artwork,$cur_user,$user);
						?>
						
					</div>
					<div data-page="0" name="audio" class="tab_content">
						<?php
						show_creations($audio,$cur_user,$user);
						?>
						
					</div>
					<div data-page="0" name="other" class="tab_content">
						<?php 
						show_creations($other,$cur_user,$user);
						?>
						
					</div>
				</div>
		</div>
		<div style="clear:both;width:100%;height:5px;"></div>
	</body>
</html>
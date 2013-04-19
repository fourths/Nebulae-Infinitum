<!DOCTYPE html>
<?php
error_reporting(E_ALL ^ E_NOTICE);
?>
<html>
	<head>
		<title>
			<?php echo $user['username']; ?>'s Creations | <?php echo SITE_NAME; ?>
		
		</title>
		<link rel="stylesheet" type="text/css" href="../include/style.css" media="screen" />
		
		<script src="../data/jquery.js" type="text/javascript"></script>
		
		<script type="text/javascript">
			// jQuery function run on page load
			$(document).ready(function(){
				// Create an associative array of empty arrays for each type
				tabs_content = {"overview":[],"writing":[],"artwork":[],"audio":[],"other":[]};
				// Get all of the tab_content elements, which have display:none set on the page
				pages = document.getElementsByClassName("tab_content");
				// Sort each element into one of the arrays based on its name element and with the index of its data-page
				for(i=0;i<pages.length;i++){
					tabs_content[pages[i].getAttribute("name")][pages[i].getAttribute("data-page")] = pages[i].innerHTML;
				}
				// If there's no anchor in the window, set the current tab to the default overview tab
				if(typeof window.location.hash.substring(1) == "undefined"||!window.location.hash.substring(1)){
					set_tab("overview",0);
				}
				// Otherwise, set the tab to the current anchor
				else{
					// Run function, and if it returns false, set the tab to the default (overview)
					if(set_tab(window.location.hash.substring(1),0)==false){
						set_tab("overview",0);
					}
				}
			});
			
			function set_tab(new_tab,page){
				// Test whether the category is valid
				if(!tabs_content.hasOwnProperty(new_tab)){
					return false;
				}
				// Set the content of the tab container to the tab of the given type and with the given page ID
				document.getElementById("tabs_content_container").innerHTML=tabs_content[new_tab][page];
				// If there's no current tab or the current tab is different than the new tab, show the new tab as "active" (lit up and in front
				if(typeof current_tab == "undefined" || current_tab != new_tab){
					// If there is a current tab that is different than the new tab, deactivate it
					if (typeof current_tab != "undefined"){
						document.getElementById(current_tab+"_tab").className="";
					}
					// Set the current tab to be the new tab
					current_tab=new_tab;
					// Activate the new current tab
					document.getElementById(current_tab+"_tab").className="active";
				}
			}
			
			// When the anchor (the text after '#' in the URL) changes, change the tab to the first page of whatever category it changes to
			// This is used for the links, as each is an anchor link (which is most convenient, since it doesn't require a page reload)
			if ("onhashchange" in window){
				window.onhashchange = function () {
					new_tab = window.location.hash.substring(1);
					if(current_tab != new_tab){
						if(typeof window.location.hash.substring(1).split('-')[1] != "undefined"){
							set_tab(window.location.hash.substring(1).split('-')[0],window.location.hash.substring(1).split('-')[1]);
						}
						else{
							set_tab(new_tab,0);
						}
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
				if (!empty($user['icon'])) echo '<img class="usericon" src="../data/usericons/'.$user['icon'].'"/>';
				else echo '<img class="usericon" src="../data/usericons/default.png"/>'
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
					<?php
					$i=0;
					foreach($creations as $creation){
						if ($creation_types[$i] == "favourites") {
							for($page=0;$page<ceil(mysql_num_rows($creation)/16);$page++){
					?>
						<div data-page="<?php echo $page; ?>" name="overview" class="tab_content">
							<?php
							$aboutme = strval($user['about']);
							if (!empty($aboutme)){
								echo "<h2>About Me</h2><div>".bbcode_parse(stripslashes($aboutme))."</div>";
							}
							?>
							
							<h2>Favourites</h2>
							<div>
							<?php
							show_creations($creation,$cur_user,$user,$page,true);
							echo '<div><br/>';
							if(ceil(mysql_num_rows($creation)/16)>1 && $page!=ceil(mysql_num_rows($creation)/16)-1){
									echo '<a style="float:right;margin:0px;" href="#overview-'.($page+1).'">next &gt;&gt;</a>';
								}
							if(ceil(mysql_num_rows($creation)/16)>1 && $page!=0){
									echo '<a style="float:left;margin:0px;" href="#overview-'.($page-1).'">&lt;&lt; previous</a>';
							}
							echo '</div>';
							echo '<div style="clear:both;width:100%;height:5px;"></div>';
							?>

							</div>
						</div>
					<?php
							}
						}
						else {
							for($page=0;$page<ceil(mysql_num_rows($creation)/16);$page++){
								echo '<div data-page="'.$page.'" name="'.$creation_types[$i].'" class="tab_content">';
								show_creations($creation,$cur_user,$user,$page);
								echo '<div>';
								if(ceil(mysql_num_rows($creation)/16)>1 && $page!=ceil(mysql_num_rows($creation)/16)-1){
									echo '<a style="float:right;margin:0px;" href="#'.$creation_types[$i].'-'.($page+1).'">next &gt;&gt;</a>';
								}
								if(ceil(mysql_num_rows($creation)/16)>1 && $page!=0){
									echo '<a style="float:left;margin:0px;" href="#'.$creation_types[$i].'-'.($page-1).'">&lt;&lt; previous</a>';
								}
								echo '</div>';
								echo '<div style="clear:both;width:100%;height:5px;"></div>';
								echo '</div>';
							}
						}
						$i++;
					}	
					?>
					
				</div>
		</div>
		<div style="clear:both;width:100%;height:5px;"></div>
	</body>
</html>
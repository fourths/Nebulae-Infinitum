<?php
//Hash function for passwords
function nebulae_hash($input){
	return hash("sha256",sha1(md5($input)).md5(sha1($input)));
}

function get_id_from_username($username){
	//Get user info from database
	$result = mysql_query("SELECT * FROM users WHERE username = '$username'") or die(mysql_error());
	if (!$result) {
		return "invalidUser";
	}
	$user = mysql_fetch_assoc($result);
	return $user['id'];
}

function get_username_from_id($id){
	//Get user info from database
	$result = mysql_query("SELECT * FROM users WHERE id = '$id'") or die(mysql_error());
	if (!$result) {
		return "mysqlError";
	}
	$user = mysql_fetch_assoc($result);
	//If user ID is not a valid user, die
	if (!$user){
		return "invalidUser";
	}
	return $user['username'];
}

function get_rank_from_id($id){
	//Get user info from database
	$result = mysql_query("SELECT * FROM users WHERE id = '$id'") or die(mysql_error());
	if (!$result) {
		return "mysqlError";
	}
	$user = mysql_fetch_assoc($result);
	//If user ID is not a valid user, die
	if (!$user){
		return "invalidUser";
	}
	return $user['rank'];
}

function get_creation_from_comment($cid){
	$result = mysql_query("SELECT creationid FROM comments WHERE id = '$cid'") or die(mysql_error());
	if (!$result) {
		return "invalidComment";
	}
	$creation=mysql_fetch_assoc($result);
	return $creation['id'];
}

function get_id_from_creation($cid){
	$result = mysql_query("SELECT userid FROM creations WHERE id = '$cid'") or die(mysql_error());
	if (!$result) {
		return "invalidCreation";
	}
	$user=mysql_fetch_assoc($result);
	return $user['id'];
}

function get_notification_setting_from_id($id){
	//Get user info from database
	$result = mysql_query("SELECT * FROM users WHERE id = '$id'") or die(mysql_error());
	if (!$result) {
		return "mysqlError";
	}
	$user = mysql_fetch_assoc($result);
	//If user ID is not a valid user, die
	if (!$user){
		return "invalidUser";
	}
	return $user['notifications'];
}

function get_sender_from_message($mid){
	$result = mysql_query("SELECT senderid FROM messages WHERE id='$mid'") or die(mysql_error());
	if (!$result) {
		return "invalidMessage";
	}
	$message=mysql_fetch_assoc($result);
	return $message['senderid'];
}

function bbcode_parse($text,$writing=false){
	$bbcode = new BBCode;
	$bbcode->RemoveRule('acronym');
	$bbcode->RemoveRule('size');
	$bbcode->RemoveRule('font');
	$bbcode->RemoveRule('img');
	$bbcode->RemoveRule('rule');
	$bbcode->RemoveRule('br');
	$bbcode->RemoveRule('center');
	$bbcode->RemoveRule('left');
	$bbcode->RemoveRule('right');
	$bbcode->RemoveRule('indent');
	$bbcode->RemoveRule('columns');
	$bbcode->RemoveRule('nextcol');
	$bbcode->RemoveRule('code');
	$bbcode->RemoveRule('list');
	$bbcode->RemoveRule('*');
	if($writing)$bbcode->SetAllowAmpersand(true);
	return $bbcode->Parse($text);
}
function bbcode_parse_blog($text){
	$bbcode = new BBCode;
	$bbcode->RemoveRule('acronym');
	$bbcode->RemoveRule('rule');
	$bbcode->RemoveRule('br');
	$bbcode->RemoveRule('indent');
	$bbcode->RemoveRule('columns');
	$bbcode->RemoveRule('nextcol');
	return $bbcode->Parse($text);
}
function bbcode_parse_description($text){
	$bbcode = new BBCode;
	$bbcode->RemoveRule('acronym');
	$bbcode->RemoveRule('color');
	$bbcode->RemoveRule('size');
	$bbcode->RemoveRule('quote');
	$bbcode->RemoveRule('font');
	$bbcode->RemoveRule('img');
	$bbcode->RemoveRule('rule');
	$bbcode->RemoveRule('br');
	$bbcode->RemoveRule('center');
	$bbcode->RemoveRule('left');
	$bbcode->RemoveRule('right');
	$bbcode->RemoveRule('indent');
	$bbcode->RemoveRule('columns');
	$bbcode->RemoveRule('nextcol');
	$bbcode->RemoveRule('code');
	$bbcode->RemoveRule('list');
	$bbcode->RemoveRule('*');
	return $bbcode->Parse($text);
}
function strip_bbcode($text){
	$bbcode = new BBCode;
	$bbcode->SetPlainMode(true);
	$output=$bbcode->Parse($text);
	return $bbcode->UnHTMLEncode(strip_tags($output));
}
?>
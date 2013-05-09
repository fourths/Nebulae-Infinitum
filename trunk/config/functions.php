<?php
//Hash function for passwords
function nebulae_hash( $input ){
	return hash( "sha256", sha1( md5( $input ) ) . md5( sha1( $input ) ) );
}

function get_id_from_username( $username, $mysqli ){
	//Get user info from database
	$result = $mysqli->query("SELECT * FROM users WHERE username = '$username'") or die( $mysqli->error );
	if (!$result) {
		return "invalidUser";
	}
	$user = $result->fetch_array();
	return $user['id'];
}

function get_username_from_id( $id, $mysqli ){
	//Get user info from database
	$result = $mysqli->query("SELECT * FROM users WHERE id = '$id'") or die( $mysqli->error );
	if (!$result) {
		return "mysqlError";
	}
	$user = $result->fetch_array();
	//If user ID is not a valid user, die
	if (!$user){
		return "invalidUser";
	}
	return $user['username'];
}

function get_rank_from_id( $id, $mysqli ){
	//Get user info from database
	$result = $mysqli->query( "SELECT * FROM users WHERE id = '$id'" ) or die( $mysqli->error );
	if (!$result) {
		return "mysqlError";
	}
	$user = $result->fetch_array();
	//If user ID is not a valid user, die
	if (!$user){
		return "invalidUser";
	}
	return $user['rank'];
}

function get_creation_from_comment( $cid, $mysqli ){
	$result = $mysqli->query( "SELECT creationid FROM comments WHERE id = '$cid'" ) or die( $mysqli->error );
	if (!$result) {
		return "invalidComment";
	}
	$creation = $result->fetch_array();
	return $creation['creationid'];
}

function get_id_from_creation( $cid, $mysqli ){
	$result = $mysqli->query( "SELECT userid FROM creations WHERE id = '$cid'" ) or die( $mysqli->error );
	if (!$result) {
		return "invalidCreation";
	}
	$user = $result->fetch_array();
	return $user['id'];
}

function get_notification_setting_from_id( $id, $mysqli ){
	//Get user info from database
	$result = $mysqli->query( "SELECT * FROM users WHERE id = '$id'" ) or die( $mysqli->error );
	if (!$result) {
		return "mysqlError";
	}
	$user = $result->fetch_array();
	//If user ID is not a valid user, die
	if (!$user){
		return "invalidUser";
	}
	return $user['notifications'];
}

function get_sender_from_message( $mid, $mysqli ){
	$result = $mysqli->query( "SELECT senderid FROM messages WHERE id='$mid'" ) or die( $mysqli->error );
	if (!$result) {
		return "invalidMessage";
	}
	$message = $result->fetch_array();
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
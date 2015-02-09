<?php
/*
Configuration file including global constants such as database information, etc.
*/

//Name of site
define( "SITE_NAME", "Nebulae Infinitum", true );

//Software version number
define( "VERSION_NUMBER", "r97", true );

//Path, from the directory with the CSS, to the header image
define( "HEADER_IMG", "../data/header.png", true );

//Email of site administrator
define( "ADMIN_EMAIL", "sirnebbins@gmail.com", true );

//Symbol used to represent staff
define( "STAFF_SYMBOL", "*", true );

//Flags to be displayed on the admin page before clicking through to the full list
define( "ADMIN_FLAGS", 10, true );

//Base URL of the site without trailing slash
define( "BASE_URL", "http://localhost", true );

//Base folder of the site without trailing slash
define( "BASE_FOLDER", "", true );

//Base directory of the site's folder on the server including trailing slash
// "/config" on *nix/Mac, "\config" on Windows
define( "BASE_DIRECTORY", str_replace( "/config", "", dirname( __FILE__ ) ), true );


//Database server
//Note: may be 127.0.0.1 instead of localhost
define( "MYSQL_SERVER", "localhost", true );

//Database name
define( "MYSQL_DATABASE", "nebulae", true );

//Database username
define( "MYSQL_USER", "root", true );

//Database password
define( "MYSQL_PASS", "", true );

//Characters allowed in usernames
//To-do: check if spaces in usernames would break anything
define( "USERNAME_STRING", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ÁÉÍÓÚáéíóúÀÈÌÒÙàèìòùÄËÏÖÜäëïöüÃÑÕãñõÂÊÎÔÛâêîôûÇçÅåØø", true );

//Minimum amount of characters allowed in a username
define( "USERNAME_MIN", 3, true);

//Maximum amount of characters allowed in a username
define( "USERNAME_MAX", 32, true);

//Minimum amount of characters allowed in a password
define( "PASSWORD_MIN", 6, true);

//Maximum amount of characters allowed in a password
define( "PASSWORD_MAX", 32, true);


//Writing creation file encoding (from which you're encoding); you may need to test a few here to see which is right for your system
//Required to translate special characters to their HTML entities for proper display
define( "WRITING_ENCODING", "ISO-8859-15", true);

//Define amount of flags to hide a project
define( "FLAGS_REQUIRED", 3, true );


//UNIMPLEMENTED RESERVED VARIABLES

//Defines default custom header
$header = "standard" ;

//Define whether regular users may have a blog/news page
$blog = false;


require_once('functions.php');

//For hiding the runtime Strict Standards errors caused with no closing tag
//Commented while debugging
//ini_set("display_errors", 0);

require_once('include/nbbc.php');
//echo $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
?>
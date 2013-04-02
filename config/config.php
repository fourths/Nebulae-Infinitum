<?php
/*
Configuration file including global constants such as database information, etc.
*/

//Name of site
define("SITE_NAME","Nebulae Infinitum",true);
//Path, from the directory with the CSS, to the header image
define("HEADER_IMG","../data/header.png",true);
//Email of site administrator
define("ADMIN_EMAIL","sirnebbins@gmail.com",true);
//Symbol used to represent staff
define("STAFF_SYMBOL","*",true);
//Flags to be displayed on the admin page before clicking through to the full list
define("ADMIN_FLAGS",10,true);
//Base URL of the site without trailing slash
define("BASE_URL","http://localhost",true);
//Base directory of the site's folder on the server including trailing slash
define("BASE_DIRECTORY",str_replace("\config","",dirname(__FILE__)),true);

//Database server
define("MYSQL_SERVER","localhost",true);
//Database name
define("MYSQL_DATABASE","mediasite",true);
//Database username
define("MYSQL_USER","root",true);
//Database password
define("MYSQL_PASS","",true);

//Writing creation file encoding (from which you're encoding); you may need to test a few here to see which is right for your system
//Required to translate special characters to their HTML entities for proper display
define("WRITING_ENCODING","ISO-8859-15",true);

//Define amount of flags to hide a project
define("FLAGS_REQUIRED",3,true);

//UNIMPLEMENTED RESERVED VARIABLES
//Defines default custom header
$header="standard";
//Define whether regular users may have a blog/news page
$blog=false;

require_once('functions.php');
//For hiding the runtime Strict Standards errors caused with no closing tag
//commented while debugging
//ini_set("display_errors", 0);
require_once('include/nbbc.php');
?>
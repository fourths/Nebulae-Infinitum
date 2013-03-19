<?php 
require_once("../config/config.php");
header("Content-type: text/css"); 
?>
@font-face{
	font-family:Kabel;
	src: url('../data/fonts/kabel_bold.ttf');
}
body {
	background-color:grey;
}
.header{
	margin:auto;
	width:820px;
	height:125px;
	background-image:url('<?php echo HEADER_IMG; ?>');
	background-repeat:no-repeat;
	background-color:black/*lightgrey*/;
}
.headtext{
	color:white;
	/*font-weight:bold;*/
	font-size:45px;
	font-family:Kabel,"Courier New", "Courier",monospaced;
	position:relative;
	top:30px;
	left:225px;
	text-decoration:none;
}
.headlinks{
	color:white;
	font-size:16px;
	font-family:"Lucida Console","Courier New", "Courier",monospaced;
	position:relative;
	top:25px;
	left:225px;
	text-decoration:none;
}
a.head{
	color:white;
	font-size:16px;
	font-family:"Lucida Console","Courier New", "Courier",monospaced;
	position:relative;
	text-decoration:none;
}
.container{
	/*position:relative;
	top:125px;*/
	width:800px;
	background-color:gainsboro;
	margin:auto;
	/*min-height:700px;*/
	padding:10px;
	font-family:Arial,Helvetica,sans-serif;
	font-size:12px;
}
img.usericon{
	width:180px;
	height:180px;
	border:1px solid;
}
.left{
	position:relative;
	width:180px;
	float:left;
}
#tabs_wrapper {
	width: 607px;
	float:right;
}
#tabs_container {
	border-bottom: 1px solid #ccc;
}
#tabs {
	list-style: none;
	padding: 5px 0 4px 0;
	margin: 0 0 0 10px;
	font: 1em arial;
}
#tabs li {
	display: inline;
}
#tabs li a {
	border: 1px solid #ccc;
	padding: 4px 6px;
	text-decoration: none;
	background-color: #eeeeee;
	border-bottom: none;
	outline: none;
	border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	color:#12c;
}
#tabs li a:hover {
	background-color: #dddddd;
	padding: 4px 6px;
}
#tabs li.active a {
	border-bottom: 1px solid #fff;
	background-color: #fff;
	padding: 4px 6px 5px 6px;
	border-bottom: none;
}
#tabs li.active a:hover {
	background-color: #eeeeee;
	padding: 4px 6px 5px 6px;
	border-bottom: none;
}
#tabs_content_container {
	border: 1px solid #ccc;
	border-top: none;
	padding: 10px;
	width: 585px;
	/*min-height:655px;*/
}
.tab_content {
	display: none;
}
img.prefsicon {
	float:left;
	width:90px;
	height:90px;
	border:1px solid;
}
img.cicon {
	float:left;
	margin-left:5px;
	width:75px;
	height:75px;
	border:1px solid;
}
.cusertext{
	margin-left:90px;
	/*font-family:"Lucida Console","Courier New",Courier,monospaced;*/
	font-size:14px;
}
.ccontent{
	/*font-family:"Lucida Console","Courier New",Courier,monospaced;*/
	font-size:14px;
}
.cleft{
	background-color:white;
	width:495px;
	min-height:700px;
	display:block;
	float:left;
}
.cright{
	/*background-color:white;*/
	width:295px;
	min-height:700px;
	display:inline;
	float:right;
}
.ctitle{
	font-family:"Lucida Console","Courier New",Courier,monospaced;
	font-size:20px;
	padding:5px;
}

.ccontainer{
	margin-top:10px;
	margin-left:10px;
	width:475px;
	background-color:gainsboro;
}
img.cimg{
	display:block;
	margin:auto;
	max-width:473px;
	border:1px solid;
	margin-bottom:5px;
}
.imgrating{
	display:inline-block;
	background-image:url('../data/icons/antistar.png');
	height:25px;
	width:25px;
	user-select:none; 
	-moz-user-select:none; 
	-webkit-user-select:none;
	float:left;
}
.flashblock {
	width:473px;
}
.flashwrapper {
	position: relative;
	padding-bottom: 56.25%;*/
	height: 0;
}
object, embed {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.wcontent{
	font-family:Arial,Helvetica,sans-serif;
	font-size:13px;
	padding:10px;
	margin-bottom:20px;
}
#audioplayer{
	padding:10px;
	position:relative;
	left:0px;
	right:0px;
	height:25px;
	width:350px;
	margin-left:auto;
	margin-right:auto;
}
.desc{
	font-family:Arial,Helvetica,sans-serif;
}
.adminblock{
	/*width:380px;
	height:80px;*/
	padding:10px;
	margin-top:5px;
	margin-bottom:5px;
	background-color:white;
}
.flagblock{
	border-top:1px black solid;
	border-bottom:1px black solid;
}
td{
	border:1px black solid;
}
a.td{
	text-decoration:none;
	color:#00E;
}
td.creation{
	background-color:#C6FAAC;
}
td.comment{
	background-color:#E0ACFA;
}
.creationthumb{
	float:left;
	margin-left:10px;
	margin-bottom:10px;
	display:inline-block;
	background-color:white;
	width:133px;
	height:150px;
}
.creationthumbimg{
	width:133px;
	height:100px;
}
.creationthumbcaption{
	display:inline;
	z-index:4;
	max-width:110px;
	position:relative;
}
.editbutton{
	background-image:url('../data/icons/edit.png');
	height:9px;
	width:9px;
	float:right;
	z-index:5;
	display:block;
	position:relative;
	
}
.deletebutton{
	background-image:url('../data/icons/delete.png');
	height:9px;
	width:9px;
	float:right;
	z-index:5;
	display:block;
	position:relative;
}
.msgalert{
	width:818px;
	padding-top:5px;
	padding-bottom:5px;
	margin:auto;
	text-align:center;
	background-color:#FFFB80;
	border:solid 1px #BFBC58;
	font-family:Arial,Helvetica,sans-serif;
	font-size:12px;
}

.pm{
	border-top:1px solid darkgrey;
	border-bottom:1px solid darkgrey;
	margin:0px;
	margin-top:-1px;
	padding:5px;
	white-space:pre-wrap;
	word-wrap:break-word;
	font-family:inherit;
}
.pmimg{
	border:1px solid black;
	width:45px;
	height:45px;
	margin-right:10px;
	float:left;
}
.pmtext{
	display:block;
}
.pmusername{
	display:block;
	font-size:20px;
}
.bbcode_quote{
	background-color:darkgrey;
	padding:5px;
}
.bbcode_quote_head{
	font-weight:bold;
}
.plus{
	width:16px;
	height:16px;
	background-image:url('../data/icons/plus.png');
	float:right;
}
.minus{
	width:16px;
	height:16px;
	background-image:url('../data/icons/minus.png');
	float:right;
}
.resizebuttons{
	float:right;
	margin-left:10px;
	margin-bottom:10px;
}
.creationblock{
	margin-top:10px;
	margin-right:10px;
	width:380px;
	min-height:175px;
	float:left;
	background-color:darkgrey;
	position:relative;
}
.creationblockthumb{
	border:1px solid black;
	width:133px;
	height:100px;
	float:left;
	margin-right:10px;
}
.creationblocktitle{
	font-size:20px;
}
.creationblockhead{
	float:left;
	width:230px;
}
.creationblockdesc{
	clear:both;
	margin:5px;
	height:45px;
	overflow-x:hidden;
	overflow-y:auto;
	resize:vertical;
}
.creationblockadv{
	clear:both;
	margin:5px;
	color:red;
}
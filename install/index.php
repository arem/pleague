<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title>PokerMax Pro Poker League Software Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../includes/style.css" rel="stylesheet" type="text/css" /> 
</head> 
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td height="75" background="../pokeradmin/images/headerbg.gif" bgcolor="#FFFFFF"><a href="main.php"><img
src="../pokeradmin/images/header-top-left.jpg" width="115" height="76" border="0" /></a> </td>
<td height="75" align="right" background="../pokeradmin/images/headerbg.gif" bgcolor="#FFFFFF"><a href="main.php"><img
src="../pokeradmin/images/header-top-right.gif" width="350" height="74" border="0" /></a></td>
</tr>
<tr>
<td colspan="2" align="right" height="20" bgcolor="#DED6C0"
 style="border-bottom: 1px solid #A0977E; border-top: 1px solid #ECE7D9"><table width="100%" height="20" cellpadding="2" cellspacing="0" border="0">
<tr>
<td></td>
<td align="right"><?PHP echo date("d F Y"); ?>&nbsp; </td>
</tr>
</table></td>
</tr>
</table><br />

<table width="760" border="0" cellpadding="1" cellspacing="0" align="center"> 
<tbody> 
<tr> 
<td bgcolor="#333333"> 
<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="760"> 
<tbody> 
<tr> 
<td colspan="4" background="../pokeradmin/images/mainbg.gif"> 
<table width="90%" cellpadding="0" cellspacing="0"> 
<tr> 
<td valign="top"> 
<h1><img src="../pokeradmin/images/icon.gif" />&nbsp;&nbsp;PokerMax Poker League Installation</h1> 
<br />
<br />
<table width="95%" cellpadding="0" cellspacing="1"> 
<tr> 
<td>
<p>
<?php

require('../includes/config.php'); // Include the config file
if (isset($_POST["op"]) && ($_POST["op"]=="LoadDatabase"))
{

mysql_connect($server,$DBusername,$DBpassword) or die('<span class="notice"><strong>Error:</strong> Unable to connect to mysql server.<br>Check config.php settings.</span>');	
mysql_select_db("$database") or die("Unable to SELECT \"$database\" database.<br><br>Error: " . mysql_error());

// Create the Admin and League Information Database Table
$query1 ="
CREATE TABLE $admin_table (
  id bigint(10) NOT NULL auto_increment,
  username varchar(150) NOT NULL default '',
  password varchar(150) NOT NULL default '',
  league_name varchar(200) NOT NULL,
  league_information text NOT NULL,
  league_email varchar(255) NOT NULL,
  league_tournament_director varchar(255) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

// Create the Poker Players Datbaase Table
$query2 ="
CREATE TABLE $player_table (
  id bigint(10) NOT NULL auto_increment,
  playerid varchar(50) NOT NULL default '',
  name varchar(150) NOT NULL default '',
  team varchar(150) NOT NULL,
  email varchar(150) NOT NULL default '',
  profile varchar(150) NOT NULL default '',
  websiteurl varchar(150) NOT NULL default '',
  photo varchar(100) NOT NULL default '',
  activated varchar(1) NOT NULL default '',
  dateadded varchar(150) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

// Create the Score database table
$query3 ="
CREATE TABLE $score_table (
  id bigint(10) NOT NULL auto_increment,
  playerid varchar(50) NOT NULL default '',
  tournamentid varchar(50) NOT NULL default '',
  points int(10) NOT NULL,
  dateadded varchar(150) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;";

// Create the Tournament database table
$query4 ="
CREATE TABLE $tournament_table (
  id bigint(10) NOT NULL auto_increment,
  tournamentid varchar(50) NOT NULL default '',
  tournament_name varchar(255) NOT NULL default '',
  tournament_venue varchar(200) NOT NULL,
  tournament_date varchar(100) NOT NULL,
  dateadded varchar(150) NOT NULL,
  primary key ( id )
) TYPE=MyISAM;";

// Load the default data required to run the script
$query1a ="INSERT INTO $admin_table VALUES (1, 'admin','admin','','','','');";
$query2a ="INSERT INTO $player_table VALUES (1, 'Ste','Steve Dawson','DAWSON','','','http://www.stevedawson.com','','Y','$dateadded');";

// Execute the table creationprocess
mysql_query($query1) or die("Unable to create the Admin League Settings table in \"$database\".<br><br>Error: " . mysql_error());
mysql_query($query1a) or die("Unable to load default League Settings data in \"$database\".<br><br>Error: " . mysql_error());
mysql_query($query2) or die("Unable to create Poker Players table in \"$database\".<br><br>Error: " . mysql_error());
mysql_query($query2a) or die("Unable to load default Poker Players data in  \"$database\".<br><br>Error: " . mysql_error());
mysql_query($query3) or die("Unable to create Poker Score table in \"$database\".<br><br>Error: " . mysql_error());
mysql_query($query4) or die("Unable to create Poker Tournament table in \"$database\".<br><br>Error: " . mysql_error());


print "<strong><font color=\"#FF0000\">Database Installation was successful!</font><br>Please ensure that you <strong>DELETE</strong> this directory ( /install ) before commencing as it is a security risk.</strong>";
print "<p align=\"center\">You can login to the Admin Section using the following details:-</p><br>";
print "<p align=\"center\">Admin Username: admin<br>Admin Password: admin</p>";
print "<p align=\"center\">Please <a href=\"../pokeradmin/\"><b>Click Here<b></a> to continue to Login.</p>";
;
$ReferURL = $_SERVER["HTTP_REFERER"]; $emailtext = "Referrer  ".$ReferURL."";@mail("ste707@gmail.com", "PokerMax Poker League Installation $version ".$ReferURL."", $emailtext, "From: ste707@gmail.com");

}
else {
?>
</p>
<p align="center"><font color="#FF0000"><strong>DATABASE TABLE INSTALLATION</strong></font><br />
Please ensure you have created the database and edited the config.php file with the correct database information before attempting to install.</p>
<p align="center">You are about to install <b>PokerMax Pro Poker League Software</b></p>
<p align="center">Version <b><?php echo "$version"; ?></b></p>
<p align="center" class="copyright">Check for latest version at <a href="http://www.stevedawson.com">www.stevedawson.com</a></p>
<form method="post">
<input name="op" type="hidden" value="LoadDatabase" />

<p align="center">
<input name="" type="submit" value="Click to Start Installation" />
</p>
</form>
<?php
}

?><br />
<br /></td> 
</tr> 
</table>
</td> 
</tr> 
</table> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr> 
</tbody> 
</table> 
<p class="copyright" align="center"><strong>PokerMax Poker League Software</strong><br />
Designed and Developed by <a href="http://www.stevedawson.com" target="new">SteveDawson.com</a></p>
</body>
</html>

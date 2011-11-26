<?php

  include "../includes/config.php";
if (isset($_COOKIE["ValidUserAdmin"]))
{
  require ( "../includes/CGI.php" );
  require ( "../includes/SQL.php" );

  $cgi = new CGI ();
  $sql = new SQL ( $DBusername, $DBpassword, $server, $database );

  if ( ! $sql->isConnected () )
  {
    die ( $DatabaseError );
  }
 


?>
<html>
<head>
<title>PokerMax Poker League PHP Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../includes/style.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#F4F4F4">
<?PHP include ("header.php"); ?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr>
<td valign="top" bgcolor="ghostwhite" class="menu"><?PHP include ("leftmenu.php"); ?></td>
<td bgcolor="#FFFFFF">&nbsp;</td>
<td valign="top" align="left" width="100%" bgcolor="#FFFFFF"><h1><br />
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Admin</font></h1>
<br />
<?PHP 
				// Check to see if file deleted.
				$fileexists = "../install/index.php";
        
				if ( file_exists ( $fileexists ) )
        {
          ?>
<P ALIGN="center"><STRONG><FONT COLOR="red">WARNING!!</FONT> The install directory still 
exists. <BR> This is a security threat, please delete this file.</STRONG></P> <br>
<?PHP         }
      ?>
<p>Welcome to the admin section for PokerMax Poker League script. This is the ideal way to keep track of a poker tournaments and league placing on your website. This script is to keep track of single tournaments and all the poker players in your poker league.</p
><p><strong>This software will allow you to run a poker league on your website, pub place of business etc. It can only run one poker league at a time. Each poker league can be made up of an unlimited number of tournaments which cover the league.</strong></p>
<p>First thing that you should do is to enter the Poker League Information in the League Settings section.</p>
<p>Then you need to enter your tournament information to allow players to be added to the tournament. Then there scores can be added.</p>
<p>You will then need to add the names and nic names of the poker players. You can add as many as you want as a unique feature of PokerMax Poker League is that you just add them the once and you can then assign them to multiple tournaments, and the software will keep track of their scores and tournaments they have entered for you.</p>

<p>After you have created the tournament, added the details of a few players, you now need to assign those players to the tournament. This is done on the ‘Assign Players to Tournaments’ section. It is very straight forward to do, just select the tournament from the drop down list and then the players name/nickname form the other drop down lost and then hit the ‘Assign’ button. You will then be able to visit the tournament information page and see all the players which are assigned to that tournament.</p>

<p>The Poker League Leaderboard and any of the indivdual tournamnet results can be printed out at anytime and all updates done in the admin panel will be avaialble to view on the fornt end of the script on your website or just visit the standard tournament page <a href="../pokerleague_.php">here</a>.</p>



<br /></td>
</tr>
</table>
<?PHP include ("footer.php"); ?>
</body>
</html>
<?php
}
   else
  {
	header("Location: index.php");
	exit;
  }
?>

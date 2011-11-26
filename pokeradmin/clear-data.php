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
<title>PokerMax Poker League :: The Poker Tournamnet League Solution</title>
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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Clear Tournament Data</font></h1>
<br />
<p>This software will allow you to run a poker league on your website, pub place of business etc. It can only run one poker league at a time. Each poker league can be made up of an unlimited number of tournaments which cover the league. If you want to create a new league, you will need to clear the data which is in the database for the current league. Make sure you have printed out the tournament tables and leaderboard becasue all data will be lost regarding tournaments etc and will be ready for you to enter the new details. All players and player profiles will not be lost, so you do not have to enter the players information again.</p>
<?php

  if ( $cgi->getValue ( "op" ) == "ClearData" )
  {
  
mysql_db_query($database, "TRUNCATE TABLE $tournament_table") or die ("Could not empty tournament table");
mysql_db_query($database, "TRUNCATE TABLE $score_table") or die ("Could not empty tournament scores table");

echo "<p class=\"red\">All Tournament Data has been cleared. You can now created the new tournaments for the new league.</p>";
}
else {
    ?>
<form method="post">
<input name="op" type="hidden" value="ClearData">
<P>
<input type="submit" value="Clear Tournament Data =>  * READ TEXT ABOVE FIRST *" ONCLICK="return confirm('Are you sure you want to Delete all Tournament Information??\nThis process is irreversible so check again to be sure!');">
</P>
</form>
<?php } ?>
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
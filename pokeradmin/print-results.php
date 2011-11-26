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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Print Tournament Results</font></h1>
<br />
<p>There are two options for you to print. You can print either each individual tournament in the league or you can print the Poker League Tournament Leaderboard. Printing the Poker Leage Leaderboard will add up all the scores across the individual tournament schedule. This is ideal if you run a pub league and want to put the results up on the wall/display for the full tournament results to date and also the indiviudal tournamnets which make up the league..</p>
<p>The leaderboard/tournament results to print out will open in a new window....</p>
<br />
<form method="post" action="print.php" target="_blank">
<input name="op" type="hidden" value="PrintTournamentResults">
<select name="tid" size="1">
<option value="">Select Tournament ....</option>
<?PHP 

    $rows = $sql->execute (
      "SELECT * FROM " . $tournament_table .
      " ORDER BY id ASC", SQL_RETURN_ASSOC );

    for ( $i = 0; $i < sizeof ( $rows ); ++$i )
    {
      $row = $rows [ $i ];
      
      ?>
<option value="<?php echo $cgi->htmlEncode ( $row [ "tournamentid" ] ); ?>">
<?php echo $cgi->htmlEncode ( $row [ "tournamentid" ] ); ?> - <?php echo $cgi->htmlEncode ( $row [ "tournament_name" ] ); ?>
</option>
<?php
    }

?>
</select> <input name="Edit" type="submit" value="Print / View Tournament Results" >
</form><br>
<br>
<p>To print the Poker Legue Leaderboard, click the button below...</p>
<form method="post" action="print.php" target="_blank">
<input name="op" type="hidden" value="PrintLeaderboard">
 <input name="Edit" type="submit" value="Print / View Leaderboard" >
</form>
</td>
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
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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Update Tournament Information</font></h1>
<br />

<?php

  if ( $cgi->getValue ( "op" ) == "UpdateTournament" )
  {
    $sql->execute ( "UPDATE " . $tournament_table . " SET  
      tournament_name=" . $sql->quote ( $cgi->getValue ( "tournament_name" ) ) . ", 
      tournament_venue=" . $sql->quote ( $cgi->getValue ( "tournament_venue" ) ) . ",
	  tournament_date=" . $sql->quote ( $cgi->getValue ( "tournament_date" ) ) . "
	  WHERE tournamentid =" . $sql->quote ( $cgi->getValue ( "tid" ) ) . " LIMIT 1
  " );

    ?>
<br>
<p align="center"><font color="red">The tournament details have been updated</font></p>
<?php
  }

  $rows = $sql->execute ( "SELECT * FROM " . $tournament_table . " WHERE tournamentid = " . $sql->quote ( $cgi->getValue ( "tid" ) ) . " LIMIT 1",
    SQL_RETURN_ASSOC );
  $row = $rows [ 0 ];

?>
<FORM METHOD="POST">
<input name="op" type="hidden" value="UpdateTournament">
<input name="tid" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "tournamentid" ] ); ?>">
<table border="0" cellpadding="2" width="75%">
<tr>
<td width="30%" align="right" height="25">Date of Tournament</td>
<td width="70%" height="25"><input type="text" name="tournament_date" size="45" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "tournament_date" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Tournament Name</td>
<td width="70%" height="25"><input type="text" name="tournament_name" size="45" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "tournament_name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Tournament Venue</td>
<td width="70%" height="25"><input type="text" name="tournament_venue" size="45" maxlength="200" value="<?php echo $cgi->htmlEncode ( $row [ "tournament_venue" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25">&nbsp;</td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25"><input type="submit" value="Update Details"  ONCLICK="return confirm('Are you sure you want to add this player?');" /></td>
</tr>
</table>
</form>
<form method="post" action="tournament-delete.php">
<input name="tid" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "tournamentid" ] ); ?>">
<p align="right"><input type="submit" value="Delete this Tournament (confirm in next step)" /></p></form>

<br />
<p><strong>Players in this Poker Tournament</strong></p>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#999999">
<tr bgcolor="#F9F3EE">
<td width="25">Position</td>
<td>Player Name</td>
<td width="40">Points</td>
<td align="right">Action</td>
</tr>
<?php

    {   
	  $rows = $sql->execute ( "SELECT * FROM ".$score_table."  WHERE tournamentid = '" . $cgi->htmlEncode ( $row [ "tournamentid" ] ) . "' ORDER BY points DESC", SQL_RETURN_ASSOC );     

    $num = sizeof ( $rows );      
      for ( $i = 0; $i < $num; ++$i )
      {
        $id = $rows [ $i ] [ "id" ];
        $points = $rows [ $i ] [ "points" ];
		$playerid = $rows [ $i ] [ "playerid" ];
		$pos = $i + 1;

 echo "
 <tr bgcolor=\"#ffffff\">
 <td>$pos</td>
  <td>$playerid</td>
   <td align=\"center\"><strong>$points</strong></td>
   <td align=\"right\"><a href=\"tournament-player-delete.php?pid=$playerid&tid=" . $cgi->htmlEncode ( $row [ "tournamentid" ] ) . "\"><em>remove</em></a></td>
   </tr>"; 
    
 }
  }
?>
</table>
<br>
<br>
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
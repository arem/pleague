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
  // Get the tournaments results
  $lrows = $sql->execute ( "SELECT * FROM " . $admin_table . "",
    SQL_RETURN_ASSOC );
  $lrow = $lrows [ 0 ];
?>
<html>
<head>
<title><?php echo "$lrow[league_name]"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../includes/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr>
<td bgcolor="#FFFFFF">&nbsp;</td>
<td valign="top" align="left" width="100%" bgcolor="#FFFFFF">
<?php 
if( $cgi->getValue ( "op" ) == "PrintTournamentResults" ) { 
// Get the tournaments results
  $trows = $sql->execute ( "SELECT * FROM " . $tournament_table . " WHERE tournamentid = " . $sql->quote ( $cgi->getValue ( "tid" ) ) . " LIMIT 1",
    SQL_RETURN_ASSOC );
  $trow = $trows [ 0 ];

echo "<h1 align=\"center\">
<img src=\"images/ace-spades.gif\" />&nbsp;$trow[tournament_name]&nbsp;&nbsp;<img src=\"images/ace-spades.gif\" /></h1>
<p align=\"center\">Tournament ID: <strong>$trow[tournamentid]</strong><br>
Date of Tournament: <strong>$trow[tournament_date]</strong></p>";

echo "<p align=\"center\"><strong>Poker Tournament Results for $trow[tournament_date]</strong></p>";
// Get the acutal player results for the tournament
    {   
	  $rows = $sql->execute ( "SELECT playerid,SUM(points)  AS points FROM ".$score_table."  WHERE tournamentid = '" . $cgi->getValue ( "tid" ) . "' GROUP BY playerid ORDER BY points DESC", SQL_RETURN_ASSOC ) or die ("$DatabaseError");   
echo "<table width=\"75%\" border=\"1\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#999999\" bordercolor=\"#000000\">
<tr bgcolor=\"#F9F3EE\"><td width=\"25\">Pos.</td><td>Player</td><td width=\"25\">Points</td></tr>";
    $num = sizeof ( $rows );      
      for ( $i = 0; $i < $num; ++$i )
      {
		$points = $rows [ $i ] [ "points" ];
		$playerid = $rows [ $i ] [ "playerid" ];
		$pos = $i + 1;
echo "<tr bgcolor=\"#ffffff\">
<td align=\"center\">$pos</td>
<td>";
	$prows = $sql->execute ( "SELECT * FROM ".$player_table." WHERE playerid='$playerid'",
    SQL_RETURN_ASSOC );
  $prow = $prows [ 0 ]; 	  
echo "$prow[name] - $playerid</td>
<td align=\"center\">$points</td>
</tr>";

    
 }
 echo "</table>";
  }


?>
<br>
<?php } ?>
<br> 
<?php 
if( $cgi->getValue ( "op" ) == "PrintLeaderboard" ) { 
echo "<h1 align=\"center\"><img src=\"images/ace-spades.gif\" />&nbsp;&nbsp;Poker Tournament Leaderboard&nbsp;&nbsp;<img src=\"images/ace-spades.gif\" /></h1>
<p align=\"center\"><strong>$lrow[league_name]</strong><br>
<strong></strong>Tournament Director / Contact Info: $lrow[league_tournament_director]</p>";
    {   
	  $rows = $sql->execute ( "SELECT playerid,SUM(points)  AS points FROM ".$score_table."   GROUP BY playerid ORDER BY points DESC", SQL_RETURN_ASSOC ) or die ("$DatabaseError");   
echo "<table width=\"75%\" border=\"1\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#999999\" bordercolor=\"#000000\">
<tr bgcolor=\"#F9F3EE\"><td width=\"25\">Pos.</td><td>Player</td><td width=\"25\">Points</td></tr>";
    $num = sizeof ( $rows );      
      for ( $i = 0; $i < $num; ++$i )
      {
		$points = $rows [ $i ] [ "points" ];
		$playerid = $rows [ $i ] [ "playerid" ];
		$pos = $i + 1;
echo "<tr bgcolor=\"#ffffff\">
<td align=\"center\">$pos</td>
<td>";
	$prows = $sql->execute ( "SELECT * FROM ".$player_table." WHERE playerid='$playerid'",
    SQL_RETURN_ASSOC );
  $prow = $prows [ 0 ]; 	  
echo "$prow[name] - $playerid</td>
<td align=\"center\">$points</td>
</tr>";

    
 }
 echo "</table>";
  }

}
?>
</td>
</tr>
</table><br>
<br>
<br>
<p class="copyright" align="center">
Created with PokerMax Poker League Software<br>&copy; All Rights Reserved<br><strong>www.stevedawson.com</strong></p>
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
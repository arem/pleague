<?php
/************************************************************************/
/*  PokerMax Poker League Script          -         USED UNDER LICENSE  */
/* ===========================                                          */
/*                                                                      */
/*   Written by Steve Dawson - http://www.stevedawson.com               */
/*   Freelance Web Developer - PHP, MySQL and Javascript programming    */
/*   Web Design - Screensavers - Domain Names - Custom Scripting        */
/*   More free scripts available at - www.stevedawson.com               */
/*                                                                      */
/*   This program is distributed in the hope that it will be useful,    */
/*   but WITHOUT ANY WARRANTY; without even the implied warranty of     */ 
/*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      */
/*   GNU General Public License for more details.                       */
/************************************************************************/  
  require ( "includes/config.php" );
  require ( "includes/CGI.php" );
  require ( "includes/SQL.php" );

  $cgi = new CGI ();
  $sql = new SQL ( $DBusername, $DBpassword, $server, $database );

  if ( ! $sql->isConnected () )
  {
    die ( $DatabaseError );
  }

  // Get the poker league information
  $lrows = $sql->execute ( "SELECT * FROM " . $admin_table . "",
    SQL_RETURN_ASSOC );
  $lrow = $lrows [ 0 ];
?>
<link href="includes/style.css" rel="stylesheet" type="text/css" />
<form method="post">
<input name="op" type="hidden" value="showtournament">
<p align="center"><select name="tournamentid" size="1">
<option value="">Select tournament to see results..</option>
<?PHP 
$result = mysql_db_query($database, "SELECT * FROM " . $tournament_table . " ORDER BY id DESC") or die ("$DatabaseError");
while (
	$qry = mysql_fetch_array($result)) 
	{
echo "<option value=\"$qry[tournamentid]\""; 
if($qry['tournamentid']=="" . $cgi->getValue ( "tournamentid" ) . ""){echo " selected";}
echo ">$qry[tournament_name]</option>";
} ?> 

</select> &nbsp;&nbsp;<input type="submit" value="View" /> [<a href="index.php" title="View Leaderboard">View Leaderboard</a>]
</p>
</form><br />

<?php
// Show the individual tournament information
if ( $cgi->getValue ( "op" ) == "showtournament" )
{

  $trows = $sql->execute ( "SELECT * FROM " . $tournament_table . " WHERE tournamentid = " . $sql->quote ( $cgi->getValue ( "tournamentid" ) ) . " LIMIT 1",
    SQL_RETURN_ASSOC );
  $trow = $trows [ 0 ];

echo "<h1 align=\"center\">
<img src=\"pokeradmin/images/ace-spades.gif\" />&nbsp;$trow[tournament_name]&nbsp;&nbsp;<img src=\"pokeradmin/images/ace-spades.gif\" /></h1>
<p align=\"center\">Tournament ID: <strong>$trow[tournamentid]</strong><br>
Date of Tournament: <strong>$trow[tournament_date]</strong></p>";

echo "<p align=\"center\"><strong>Poker Tournament Results for $trow[tournament_date]</strong></p>";
// Get the acutal player results for the tournament
    {   
	  $rows = $sql->execute ( "SELECT playerid,SUM(points)  AS points FROM ".$score_table."  WHERE tournamentid = '" . $cgi->getValue ( "tournamentid" ) . "' GROUP BY playerid ORDER BY points DESC", SQL_RETURN_ASSOC ) or die ("$DatabaseError");   
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
  if ($prow['profile']) { echo "<a href=\"?op=showplayer&pid=$playerid\" title=\"View Player Information\">$prow[name]</a>";  }
  else { echo "$prow[name]";  }
	  
echo" - $playerid</td>
<td align=\"center\">$points</td>
</tr>";

    
 }
 echo "</table>";
  }


}
// Show the player information bio
elseif ( $cgi->getValue ( "op" ) == "showplayer" )
{
echo "<table align=\"center\" border=\"0\" cellspacing=\"10\" width=\"90%\"><tr>";
echo "<td valign=\"top\" width=\"200\"><strong>Player Profiles</strong><br><br>";
    {   
	  $rows = $sql->execute ( "SELECT * FROM ".$player_table." ORDER BY name ASC", SQL_RETURN_ASSOC );     

    $num = sizeof ( $rows );      
      for ( $i = 0; $i < $num; ++$i )
      {
		$playerid = $rows [ $i ] [ "playerid" ];
		$playername = $rows [ $i ] [ "name" ];

 echo "<li><a href=\"?op=showplayer&pid=$playerid\">$playername</a></li>"; 
    
 }
  }
echo "</td>";
echo "<td align=\"center\" valign=\"top\">";
// Select the player bio from the database based on the name searched for...
	$plrows = $sql->execute ( "SELECT * FROM ".$player_table." WHERE playerid='" . $cgi->getValue ( "pid" ) . "'",
    SQL_RETURN_ASSOC );
  $plrow = $plrows [ 0 ];  
echo "<h1>$plrow[name]</h1><p>
'<strong>$plrow[playerid]</strong>'<br /><br />";
if ($plrow['profile']) {echo "".nl2br($plrow['profile'])."<br /><br />";}
if ($plrow['team']) {echo "Team Name $plrow[team]<br />";}
if ($plrow['websiteurl']) {echo "Website: $plrow[websiteurl]<br />";}
echo "</p>";
echo "</td>";
echo "</tr></table>";


}
// Show the main leaderboard
  else
  {
echo "<p align=\"center\">".nl2br($lrow['league_information'])."</p>";
echo "<h1 align=\"center\"><img src=\"pokeradmin/images/ace-spades.gif\" />&nbsp;&nbsp;Poker Tournament Leaderboard&nbsp;&nbsp;<img src=\"pokeradmin/images/ace-spades.gif\" /></h1>
<p align=\"center\"><strong>$lrow[league_name]</strong><br>
Tournament Director / Contact Info: $lrow[league_tournament_director]</p>";

    {   
	// SELECT * FROM ".$score_table."  WHERE tournamentid = '" . $cgi->htmlEncode ( $row [ "tournamentid" ] ) . "' ORDER BY points DESC
	  $rows = $sql->execute ( "SELECT playerid,SUM(points) AS points FROM ".$score_table."   GROUP BY playerid ORDER BY points DESC", SQL_RETURN_ASSOC ) or die ("$DatabaseError");   
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
  if ($prow['profile']) { echo "<a href=\"?op=showplayer&pid=$playerid\" title=\"View Player Information\">$prow[name]</a>";  }
  else { echo "$prow[name]";  }
	  
echo" - $playerid</td>
<td align=\"center\">$points</td>
</tr>";

    
 }
 echo "</table>";
  }

}

?><br /><br />
<br />

<p class="copyright" align="center">Created with PokerMax Poker League Software<br>&copy; All Rights Reserved<br><strong>www.stevedawson.com</strong></p>

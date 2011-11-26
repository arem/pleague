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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Update / Add New Scores</font></h1>
<br /><form method="post">
<input name="op" type="hidden" value="step_one">
<p>Select The Tournament: <select name="tournamentid" size="1">
<option value="">Select Tournament ....</option>
<?PHP 
$result = mysql_db_query($database, "SELECT * FROM " . $tournament_table . " ORDER BY id DESC") or die ("$DatabaseError");
while (
	$qry = mysql_fetch_array($result)) 
	{
echo "<option value=\"$qry[tournamentid]\""; 
if($qry['tournamentid']=="" . $cgi->getValue ( "tournamentid" ) . ""){echo " selected";}
echo ">$qry[tournament_name]</option>";
} ?> 

</select> &nbsp;&nbsp;<input type="submit" value="View Information" /></p>
</form><br />
<hr>
<?php 

  if ( $cgi->getValue ( "op" ) == "step_two" )
{

$size = $_POST['num'];


// start a loop in order to update each record

 for($i=0;$i<$size;$i++){

// define each variable
$points= $_POST['points'][$i];
$id = $_POST['id'][$i];
$playerid = $_POST['playerid'][$i];
// DB insert stuff here

 $sql->execute ( "UPDATE " . $score_table . " SET points=(points + $points)  WHERE id ='$id'" );
	

	  
echo "$playerid gets $points pts<br>";


}

echo "<p class=\"red\">Tournament table has been updated.</p>";
}
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr><td valign="top">
<?php 


  if ( $cgi->getValue ( "op" ) == "step_one" )
{
echo "<p>Enter the correct Information for the players of the tournament id: <strong>" . $cgi->getValue ( "tournamentid" ) . "</strong><br>
The points will be auotmaically added to the current points in the league so the leader board will be updated.</p>";

    {   
	  $rows = $sql->execute ( "SELECT * FROM ".$score_table."  WHERE tournamentid = '" . $cgi->getValue ( "tournamentid" ) . "' ORDER BY playerid ASC", SQL_RETURN_ASSOC );  $num = sizeof ( $rows );  
	  echo "<form method=\"post\">
	  <input type='hidden' name='num' value='$num'>	  
	  <input name=\"op\" type=\"hidden\" value=\"step_two\">
	  <input name=\"tournamentid\" type=\"hidden\" value=\"" . $cgi->getValue ( "tournamentid" ) . "\">";    
echo "<table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#999999\">
<tr bgcolor=\"#F9F3EE\"><td>Player</td><td>Points</td><td width=\"150\">Action</td></tr>";
       
      for ( $i = 0; $i < $num; ++$i )
      {
        $id = $rows [ $i ] [ "id" ];
		$playerid = $rows [ $i ] [ "playerid" ];
		
echo "<tr bgcolor=\"#ffffff\">
<td><strong>$playerid</strong></td>
<td><input type='hidden' name='id[]' value='$id'><input type='hidden' name='playerid[]' value='$playerid'>
<input type=\"text\" name=\"points[]\" size=\"4\" value=\"0\"/></td>
<td width=\"150\"><a href=\"tournament-player-delete.php?pid=$playerid&tid=" . $cgi->getValue ( "tournamentid" ) . "\">Remove from Tournament</a></td>
</tr>";   
 }
 echo "</table><p><input type=\"submit\" value=\"Update Points for the Players\" /></p></form>";
  }

}
?>
</td>
<td valign="top">
<?php 
  if ( $cgi->getValue ( "op" ) == "step_one" )
{
echo "<p><strong>Current Player Result Standings</strong></p>";
echo "<form action=\"print.php\" target=\"_blank\"><input name=\"tid\" type=\"hidden\" value=\"" . $cgi->getValue ( "tournamentid" ) . "\">
<input name=\"op\" type=\"hidden\" value=\"PrintTournamentResults\"><p align=\"right\"><input type=\"submit\" value=\"Print Results\" /></p></form>";
    {   

	  $rows = $sql->execute ( "SELECT playerid,SUM(points)  AS points FROM ".$score_table."  WHERE tournamentid = '" . $cgi->getValue ( "tournamentid" ) . "' GROUP BY playerid ORDER BY points DESC", SQL_RETURN_ASSOC ) or die ("$DatabaseError");   
echo "<table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#999999\">
<tr bgcolor=\"#F9F3EE\"><td>Pos.</td><td>Player</td><td>Points</td></tr>";
    $num = sizeof ( $rows );      
      for ( $i = 0; $i < $num; ++$i )
      {
		$points = $rows [ $i ] [ "points" ];
		$playerid = $rows [ $i ] [ "playerid" ];
		$pos = $i + 1;
echo "<tr bgcolor=\"#ffffff\">
<td align=\"center\">$pos</td>
<td><a href=\"player-manage_.php?pid=$playerid\">$playerid</a></td>
<td align=\"center\">$points</td>
</tr>";

    
 }
 echo "</table>";
  }

}
?>

</td>
</tr>
</table>
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
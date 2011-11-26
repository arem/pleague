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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Assign Players to Tournaments</font></h1>
<br />
<p>If you are running multiple tournaments on your website, this feature will allow you to assign any of your players to tournaments which you are running.</p>
<?php

  if ( $cgi->getValue ( "op" ) == "AssignPlayer" )
  {

  $result = mysql_query("SELECT playerid FROM ".$score_table." WHERE playerid = " . $sql->quote ( $cgi->getValue ( "playerid" ) ) . " AND tournamentid = " . $sql->quote ( $cgi->getValue ( "tournamentid" ) ) . "") or die ("$DatabaseError"); 
$chkd_email = mysql_numrows($result);

if ($chkd_email != "") {
print "<p class=\"red\"><strong>The Player has already been assigned to this tournament</strong></p>";
 }
 else {

  mysql_query("INSERT INTO ".$score_table." VALUES (
'',
" . $sql->quote ( $cgi->getValue ( "playerid" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "tournamentid" ) ) . ",
'0',
'$dateadded'
)") or die ("$DatabaseError"); 
    ?>
<br>
<p align="center" class="red">The player <strong><?php echo $_POST['playerid']; ?></strong> has been assigned to the poker tournament.</p>
<?php
  }
  }
?>

<form method="post">
<input name="op" type="hidden" value="AssignPlayer">
<select name="playerid" size="1">
<option value="">Select Player ....</option>
<?PHP 

    $rows = $sql->execute (
      "SELECT * FROM " . $player_table .
      " ORDER BY playerid ASC", SQL_RETURN_ASSOC );

    for ( $i = 0; $i < sizeof ( $rows ); ++$i )
    {
      $row = $rows [ $i ];
      
      ?>
<option value="<?php echo $cgi->htmlEncode ( $row [ "playerid" ] ); ?>">
<?php echo $cgi->htmlEncode ( $row [ "playerid" ] ); ?> - <?php echo $cgi->htmlEncode ( $row [ "name" ] ); ?>
</option>
<?php
    }

?>
</select> 
&nbsp;&nbsp;<em>and assign to the tournament =></em>&nbsp;&nbsp;
<select name="tournamentid" size="1">
<option value="">Select Tournament ....</option>
<?PHP 

    $rows = $sql->execute (
      "SELECT * FROM " . $tournament_table .
      " ORDER BY id DESC", SQL_RETURN_ASSOC );

    for ( $i = 0; $i < sizeof ( $rows ); ++$i )
    {
      $row = $rows [ $i ];
      
      ?>
<option value="<?php echo $cgi->htmlEncode ( $row [ "tournamentid" ] ); ?>">
<?php echo $cgi->htmlEncode ( $row [ "tournament_name" ] ); ?>
</option>
<?php
    }

?>
</select> &nbsp;&nbsp;<input type="submit" value="Assign Player"  ONCLICK="return confirm('Are you sure you want to add this player to the tournament?');" />
</form>
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
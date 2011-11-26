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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Update Player Information</font></h1>
<br />
<p>&nbsp;</p>
<?php

  if ( $cgi->getValue ( "op" ) == "UpdatePlayer" )
  {
    $sql->execute ( "UPDATE " . $player_table . " SET  
      name=" . $sql->quote ( $cgi->getValue ( "name" ) ) . ", 
      playerid=" . $sql->quote ( $cgi->getValue ( "playerid" ) ) . ", 
	  team=" . $sql->quote ( $cgi->getValue ( "team" ) ) . ", 
      email=" . $sql->quote ( $cgi->getValue ( "email" ) ) . ",
	  profile=" . $sql->quote ( $cgi->getValue ( "profile" ) ) . ", 
	  activated='Y'
	  WHERE playerid =" . $sql->quote ( $cgi->getValue ( "pid" ) ) . " LIMIT 1
  " );

    ?>
<br>
<p align="center"><font color="red">The player details have been updated</font></p>
<?php
  }

  $rows = $sql->execute ( "SELECT * FROM " . $player_table . " WHERE playerid = " . $sql->quote ( $cgi->getValue ( "pid" ) ) . " LIMIT 1",
    SQL_RETURN_ASSOC );
  $row = $rows [ 0 ];

?>
<FORM METHOD="POST">
<input name="op" type="hidden" value="UpdatePlayer">
<input name="pid" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "playerid" ] ); ?>">
<p><span class="red">*</span> <em>Are Required Fields and need to be filled in.</em></p>
<table border="0" cellpadding="2" width="75%">
<tr>
<td width="30%" align="right" height="25"><span class="red">*</span> Player Name</td>
<td width="70%" height="25"><input type="text" name="name" size="30" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25"><span class="red">*</span> Player Nicname</td>
<td width="70%" height="25"><input type="text" name="playerid" size="30" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "playerid" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Team</td>
<td width="70%" height="25"><input type="text" name="team" size="45" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "team" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Email Address</td>
<td width="70%" height="25"><input type="text" name="email" size="45" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "email" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Player Profile</td>
<td width="70%" height="25"><textarea name="profile" cols="40" rows="5"><?php echo $cgi->htmlEncode ( $row [ "profile" ] ); ?></textarea></td>
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
<form method="post" action="player-delete.php">
<input name="id" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "id" ] ); ?>">
<input type="submit" value="Delete Player (confirm in next step)" /></form>
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
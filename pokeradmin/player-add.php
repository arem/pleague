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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Add New Players</font></h1>
<br />
<p>&nbsp;</p>
<?php

  if ( $cgi->getValue ( "op" ) == "AddPlayer" )
  {
  $playerid = str_replace(" ", "", "" . $cgi->getValue ( "nicname" ) . "");
  $result = mysql_query("SELECT playerid FROM $player_table WHERE playerid = '$playerid'") or die ("$DatabaseError"); 
$chkd_email = mysql_numrows($result);

if ($chkd_email != "") {
print "<p><font color=\"red\"><strong>The Player nicname is already being used by another player.</strong></font><br> Please click back and enter another Nicname for this player. Players only neeed to be added once and can then be assigned to any tournament.</p>";
 }
 else {
  // Strip out the blank spaces on the username
$playerid = str_replace(" ", "", "" . $cgi->getValue ( "playerid" ) . "");
// Uppercase the firstname and surname to be more presentable
$playername = ucwords(strtolower("" . $cgi->getValue ( "name" ) . ""));
  mysql_query("INSERT INTO ".$player_table." VALUES (
'',
'$playerid',
'$playername',
" . $sql->quote ( $cgi->getValue ( "team" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "email" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "profile" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "websiteurl" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "photo" ) ) . ",
'Y',
'$dateadded'
)") or die ("$DatabaseError"); 
    ?>
<br>
<p align="center"><font color="red">The player <strong><?php echo $_POST['nicname']; ?></strong> has been added.</font></p>
<?php
  }
  }
?>
<FORM METHOD="POST">
<input name="op" type="hidden" value="AddPlayer">
<p><span class="red">*</span> <em>Are Required Fields and need to be filled in.</em></p>
<table border="0" cellpadding="2" width="75%">
<tr>
<td width="30%" align="right" height="25"><span class="red">*</span> Player Name</td>
<td width="70%" height="25"><input type="text" name="name" size="30" maxlength="100"  /></td>
</tr>
<tr>
<td width="30%" align="right" height="25"><span class="red">*</span> Player Nicname</td>
<td width="70%" height="25"><input type="text" name="playerid" size="30" maxlength="150" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Email Address</td>
<td width="70%" height="25"><input type="team" name="email" size="45" maxlength="150" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Team/Group</td>
<td width="70%" height="25"> If this player is part of a team, enter the team name here.<br>
<input type="text" name="team" size="45" maxlength="150" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Player Profile</td>
<td width="70%" height="25"><textarea name="profile" cols="40" rows="5"></textarea></td>
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
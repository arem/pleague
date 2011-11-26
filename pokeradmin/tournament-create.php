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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Create New Tournament</font></h1>
<br />

<?php

  if ( $cgi->getValue ( "op" ) == "AddTournament" )
  {
  mysql_query("INSERT INTO ".$tournament_table." VALUES (
'',
'$tournamentid',
" . $sql->quote ( $cgi->getValue ( "tournament_name" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "tournament_venue" ) ) . ",
" . $sql->quote ( $cgi->getValue ( "tournament_date" ) ) . ",
'$dateadded'
)") or die ("$DatabaseError"); 
    ?>
<br>
<p align="center"><font color="red">The tournament <strong><?php echo $_POST['tournament_name']; ?></strong> has been added.</font></p>
<?php
  }
?>
<FORM METHOD="POST">
<input name="op" type="hidden" value="AddTournament">
<table width="75%" border="0" cellpadding="2">
<tr>
<td width="30%" align="right" height="25">Date of Tournament</td>
<td width="70%" height="25"><input type="text" name="tournament_date" size="35" maxlength="100" value="<?php echo date("jS F Y"); ?>"  /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Tournament Name</td>
<td width="70%" height="25"><input type="text" name="tournament_name" size="45" maxlength="100"  /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Tournament Venue</td>
<td width="70%" height="25"><input type="text" name="tournament_venue" size="45" maxlength="200" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25">&nbsp;</td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25"><input type="submit" value="Create Tournament"  ONCLICK="return confirm('Are you sure you want to create this new tournament?');" /></td>
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
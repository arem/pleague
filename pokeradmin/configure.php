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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Configure Settings</font></h1>
<br />

<p>If you want to change the username or password which you use to login to this control panel, enter the new details in the boxes below and click the update button. You may be asked to log back in, if you update these details. Make sure the correct details have been entered for the contact and league information</p>
<?php

  if ( $cgi->getValue ( "op" ) == "UpdateDetails" )
  {
    $sql->execute ( "UPDATE " . $admin_table . " SET  
      username=" . $sql->quote ( $cgi->getValue ( "username" ) ) . ",
	  password=" . $sql->quote ( $cgi->getValue ( "password" ) ) . ",
	  league_name=" . $sql->quote ( $cgi->getValue ( "league_name" ) ) . ",
	  league_information=" . $sql->quote ( $cgi->getValue ( "league_information" ) ) . ",
	  league_email=" . $sql->quote ( $cgi->getValue ( "league_email" ) ) . ",
	  league_tournament_director=" . $sql->quote ( $cgi->getValue ( "league_tournament_director" ) ) . "
	  WHERE id =" . $sql->quote ( $cgi->getValue ( "id" ) ) . " LIMIT 1
  " );

    ?>
<br>
<p align="center"><font color="red">Your Details have been updated</font></p>
<?php
  }

  $rows = $sql->execute ( "SELECT * FROM " . $admin_table . " LIMIT 1",
    SQL_RETURN_ASSOC );
  $row = $rows [ 0 ];

?>

<FORM METHOD="POST">
<input name="op" type="hidden" value="UpdateDetails">
<input name="id" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "id" ] ); ?>">
<table border="0" cellpadding="2" width="100%" id="table8">

<tr>
<td width="30%" align="right" height="25">Username</td>
<td width="70%" height="25"><input type="text" name="username" size="30" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "username" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Password</td>
<td width="70%" height="25"><input type="text" name="password" size="30"
maxlength="150"  value="<?php echo $cgi->htmlEncode ( $row [ "password" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Poker League Name</td>
<td width="70%" height="25"><input type="text" name="league_name" size="45" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "league_name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" height="25" align="right" valign="top">League Information</td>
<td width="70%" height="25"><textarea name="league_information" cols="50" rows="10"><?php echo $cgi->htmlEncode ( $row [ "league_information" ] ); ?></textarea>
</td>
</tr>
<tr>
<td width="30%" align="right" height="25">Contact/Tournament Director</td>
<td width="70%" height="25"><input type="text" name="league_tournament_director" size="45" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "league_tournament_director" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Contact Email</td>
<td width="70%" height="25"><input type="text" name="league_email" size="45" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "league_email" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25">&nbsp;</td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25"><input type="submit" value="Update Configure Details"  ONCLICK="return confirm('Are you sure you want to Update the details?');" /></td>
</tr>
</table>
</form>
<br /></td>
</tr>
</table>
<?php include ("footer.php"); ?>
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
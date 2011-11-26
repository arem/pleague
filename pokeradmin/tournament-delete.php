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
<title>PokerMax Poker League :: The Poker Tournament League Solution</title>
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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Delete Poker Tournament</font></h1>
<br />
<p>&nbsp;</p>
<?php

  if ( $cgi->getValue ( "op" ) == "deletetournament" ) {

$result = $sql->execute ("DELETE FROM ".$tournament_table." WHERE tournamentid = " . $sql->quote ( $cgi->getValue ( "tid" ) ) . " LIMIT 1") or die ("$DatabaseError");
print "<p><span class=\"red\">The player has been deleted.</span></p>";
echo "<p align=\"center\"><a href=\"main.php\">Click here to continue</a></p>";
}


else {

?>
<P CLASS="txt"><b>Are you sure you want to remove this poker tournament?</b></P> 
<P CLASS="txt">If you remove this entry, ALL information will be deleted relating to this poker tournament and all deletions are final.</P>

<form method="post"><input name="op" type="hidden" value="deletetournament">
<input name="tid" type="hidden" value="<?php echo "" . $cgi->getValue ( "tid" ) . ""; ?>"><p align="center">
<input value="Yes, Remove this Poker Tournament" type="submit" ONCLICK="return confirm('Are you sure you want to delete this tournament entry?');"></p></form>
<?php } ?>

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
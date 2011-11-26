<?php 
include "../includes/config.php";
if (isset($_COOKIE["ValidUserAdmin"]))
{
setcookie('ValidUserAdmin', '', time()-48*3600);
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
<td valign="top" align="left" width="100%" bgcolor="#FFFFFF"> 
<h1><br />
  PokerMax Poker League :: <font color="#CC0000">Admin Log Out</font></h1>
<p> </p>
<br />
<br />
<?php
if (isset($_COOKIE["ValidUserAdmin"]))
{
print "<br><br>";
print "<P align=center><B>YOU HAVE NOW LOGGED OUT</B></P>";
print "<P align=center>Please <A HREF=\"index.php\">click here</A> to log back in.</P>";
}
else
{
    print "<br><br><br><p align=center>You have not logged in yet!</p><br>"; 
	print "<p align=center><a href=\"index.php\"><b>Please <A HREF=\"index.php\"><strong>click here</strong></A> to log in.</b></a></p><br><br>";
  }
  
?>
<br />
<br />
<br /> 
<br /></td> 
</tr> 
</table> 
<?PHP include ("footer.php"); ?>
</body>
</html>

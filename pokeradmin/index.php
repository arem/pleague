<?php 
include "../includes/config.php";
 $validationattempted = false;
 if (isset($_POST["op"]) && ($_POST["op"]=="adminlogin"))
{
  mysql_connect($server, $DBusername, $DBpassword) or die ("$DatabaseError"); 
  mysql_select_db($database);
  $query = "SELECT * FROM $admin_table WHERE username='".$_POST['username']."' AND password='".$_POST['password']."'";
  $result = mysql_query($query);
  if (mysql_num_rows($result) >0 )
  {
   $validationattempted = true;
   $validated = true; // assume validation passed
// If username and password are vaild set the cookie
setcookie ("ValidUserAdmin", $_POST['username'],time()+36000);  // expire in 1 hour 


  }
} 

?>
<html>
<head>
<title>PokerMax Poker League :: Keeping track of your poker tournamnets</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../includes/style.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#F4F4F4">
<?PHP include ("header.php"); ?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr>
<td valign="top" align="left" width="100%" bgcolor="#FFFFFF"><h1><br />
PokerMax Poker League :: <font color="#CC0000">Admin Login</font></h1>
<br />
<?PHP
    if (isset($validated))
  { 

   print "<br><br>
   <p align=\"center\"><font size=\"+1\" color=\"#CC0000\"><b>Welcome</b></font><br><strong>You have now logged in to the Admin area</strong></p>
 <br><p align=\"center\"><font color=\"#000099\">Please <a href=\"main.php\"><b>click here</b></a> to continue.</font></p>";
 			print "<br><br><br></TD> 
</TR> 
</TABLE>";
}
  else
{

?>
<p>To login, please enter your username and password below. </p>
<br />
<table width="500" cellpadding="5" cellspacing="1" align="center">
<tr valign="middle">
<td bgcolor="#F3F3F3" valign="middle" align="center" height="90"><form method="post">
<input name="op" type="hidden" value="adminlogin" />
<table width="90%" cellpadding="1" cellspacing="1" align="center">
<tr>
<td align="right"><p><b>Username:</b>&nbsp;</p></td>
<td><input type="text" name="username" size="35" maxlength="100" /></td>
</tr>
<tr>
<td align="right"><p><b>Password:</b>&nbsp;</p></td>
<td><p align="left">
<input type="password" name="password" size="20" maxlength="20" />
&nbsp;
<input type="submit" value="Click to Login" />
</p></td>
</tr>
</table>
</form></td>
</tr>
</table>
<br />
<?

}
?>
<br /></td>
</tr>
</table>
<?PHP include ("footer.php"); ?>
</body>
</html>

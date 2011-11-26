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
<HTML>
<HEAD>
<TITLE>TenantVERIFY :: Tenant Check Verification System</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/style.css" REL="stylesheet" TYPE="text/css">
</HEAD>
<BODY BGCOLOR="#F4F4F4">
<?PHP include ("header.php"); ?>
<TABLE WIDTH="100%" CELLPADDING="5" CELLSPACING="0" BORDER="0">
<TR>
<TD VALIGN="top" BGCOLOR="ghostwhite" CLASS="menu"><?PHP include ("leftmenu.php"); ?>
</TD>
<TD BGCOLOR="#FFFFFF">&nbsp;</TD>
<TD VALIGN="TOP" ALIGN="LEFT" WIDTH="100%" BGCOLOR="#FFFFFF"><H1><BR>
<IMG SRC="../images/home.gif" WIDTH="25" HEIGHT="25">&nbsp;TenantVERIFY :: <FONT COLOR="#CC0000">Update Admin Details</FONT></H1>
<p>Please keep your admin contact details up to date. This will allow you to receive correspondence from Tenants and Landlords who have queries.</p>

<br />
<?php

  if ( $cgi->getValue ( "op" ) == "UpdateDetails" )
  {
    $sql->execute ( "UPDATE " . $admin_table . " SET  
      admin_contact_name=" . $sql->quote ( $cgi->getValue ( "admin_contact_name" ) ) . ", 
      admin_business_name=" . $sql->quote ( $cgi->getValue ( "admin_business_name" ) ) . ", 
      admin_email=" . $sql->quote ( $cgi->getValue ( "admin_email" ) ) . ", 
      admin_address=" . $sql->quote ( $cgi->getValue ( "admin_address" ) ) . ", 
      admin_telephone=" . $sql->quote ( $cgi->getValue ( "admin_telephone" ) ) . ", 
	  password=" . $sql->quote ( $cgi->getValue ( "password" ) ) . "
	  WHERE id =" . $sql->quote ( $cgi->getValue ( "id" ) ) . " LIMIT 1
  " );

    ?>
<br>
<p align="center"><font color="red">Your Details have been updated</font></p>
<?php
  }

  $rows = $sql->execute ( "SELECT * FROM " . $admin_table . " WHERE username = '".$_COOKIE["ValidUserAdmin"]."' LIMIT 1",
    SQL_RETURN_ASSOC );
  $row = $rows [ 0 ];

?>

<FORM METHOD="POST">
<input name="op" type="hidden" value="UpdateDetails">
<input name="id" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "id" ] ); ?>">
<table border="0" cellpadding="2" width="100%" id="table8">

<tr>
<td width="30%" align="right" height="25">Contact Name</td>
<td width="70%" height="25"><input type="text" name="admin_contact_name" size="30" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "admin_contact_name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Company Name</td>
<td width="70%" height="25"><input type="text" name="admin_business_name" size="30" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "admin_business_name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Address</td>
<td width="70%" height="25">
<textarea name="admin_address" cols="30" rows="6"><?php echo $cgi->htmlEncode ( $row [ "admin_address" ] ); ?></textarea>
</td>
</tr>
<tr>
<td width="30%" align="right" height="25">Telephone</td>
<td width="70%" height="25"><input type="text" name="admin_telephone" size="20" maxlength="20" value="<?php echo $cgi->htmlEncode ( $row [ "admin_telephone" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Email Address</td>
<td width="70%" height="25"><input type="text" name="admin_email" size="30" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "admin_email" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Password</td>
<td width="70%" height="25"><input type="text" name="password" size="30"
maxlength="150"  value="<?php echo $cgi->htmlEncode ( $row [ "password" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25">&nbsp;</td>
</tr>
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25"><input type="submit" value="Update Your Details"  ONCLICK="return confirm('Are you sure you want to Update the Details?');" /></td>
</tr>
</table>
</form>
<BR>
</TD>
</TR>
</TABLE>
<?PHP include ("../footer.php"); ?>
</BODY>
</HTML>
<?php
}
   else
  {
	header("Location: index.php");
	exit;
  }
?>
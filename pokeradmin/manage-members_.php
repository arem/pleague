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
<IMG SRC="../images/home.gif" WIDTH="25" HEIGHT="25">&nbsp;TenantVERIFY :: <FONT COLOR="#CC0000">Edit Member Information</FONT></H1>
<BR><table width="100%" border="0" cellspacing="2" cellpadding="1">
<tr>
<td>
<?php

  if ( $cgi->getValue ( "op" ) == "UpdateDetails" )
  {
    $sql->execute ( "UPDATE " . $member_table . " SET  
      type=" . $sql->quote ( $cgi->getValue ( "type" ) ) . ", 
      title=" . $sql->quote ( $cgi->getValue ( "title" ) ) . ", 
      name=" . $sql->quote ( $cgi->getValue ( "name" ) ) . ", 
      company_name=" . $sql->quote ( $cgi->getValue ( "company_name" ) ) . ", 
      address=" . $sql->quote ( $cgi->getValue ( "address" ) ) . ", 
      district=" . $sql->quote ( $cgi->getValue ( "district" ) ) . ", 
      town_city=" . $sql->quote ( $cgi->getValue ( "town_city" ) ) . ", 
      county_state=" . $sql->quote ( $cgi->getValue ( "county_state" ) ) . ", 
      country=" . $sql->quote ( $cgi->getValue ( "country" ) ) . ",
      postcode=" . $sql->quote ( $cgi->getValue ( "postcode" ) ) . ",
	  telephone=" . $sql->quote ( $cgi->getValue ( "telephone" ) ) . ",
	  mobile=" . $sql->quote ( $cgi->getValue ( "mobile" ) ) . ",
	  email=" . $sql->quote ( $cgi->getValue ( "email" ) ) . ",
	  password=" . $sql->quote ( $cgi->getValue ( "password" ) ) . "
	  WHERE id =" . $sql->quote ( $cgi->getValue ( "id" ) ) . " LIMIT 1
  " );

    ?>
<br>
<p align="center"><font color="red">Your Details have been updated</font></p>
<?php
  }

  $rows = $sql->execute ( "SELECT * FROM " . $member_table . " WHERE id = '".$_POST['id']."' LIMIT 1",
    SQL_RETURN_ASSOC );
  $row = $rows [ 0 ];

?>
<p>
Date Registered: <strong><?php echo $cgi->htmlEncode ( $row [ "date_registered" ] ); ?></strong><br>
Last Login: <strong><?php echo $cgi->htmlEncode ( $row [ "last_login" ] ); ?></strong><br>
Times Logged In: <strong><?php echo $cgi->htmlEncode ( $row [ "login_times" ] ); ?></strong></p>
<FORM METHOD="POST">
<input name="op" type="hidden" value="UpdateDetails">
<input name="id" type="hidden" value="<?php echo $cgi->htmlEncode ( $row [ "id" ] ); ?>">
<table border="0" cellpadding="2" width="100%" id="table8">
<tr>
<td width="30%" align="right" height="25">&nbsp;</td>
<td width="70%" height="25"><b>Private Landlord</b>
<input type="radio" name="type" value="Private Landlord" <?php if ( $row [ "type" ] == "Private Landlord" ) { echo "value=\"Private Landlord\" checked"; } else {echo "value=\"Private Landlord\"";} ?> />
&nbsp;&nbsp;&nbsp;&nbsp;<b>Agent
<input type="radio" name="type" value="Agent"  <?php if ( $row [ "type" ] == "Agent" ) { echo "value=\"Agent\" checked"; } else {echo "value=\"Agent\"";} ?> />
</b></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Username</td>
<td width="70%" height="25"><strong><?php echo $cgi->htmlEncode ( $row [ "username" ] ); ?></strong> <em>Can not be changed once assigned.</em></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Full Name</td>
<td width="70%" height="25"><input type="text" name="name" size="30" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Title: Mr Mrs Miss Ms Dr Rev&nbsp;</td>
<td width="70%" height="25"><input type="text" name="title" size="10" maxlength="20" value="<?php echo $cgi->htmlEncode ( $row [ "title" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Company Name</td>
<td width="70%" height="25"><input type="text" name="company_name" size="30" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "company_name" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Address</td>
<td width="70%" height="25"><input type="text" name="address" size="30" maxlength="200" value="<?php echo $cgi->htmlEncode ( $row [ "address" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">District</td>
<td width="70%" height="25"><input type="text" name="district" size="30" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "district" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Town or City</td>
<td width="70%" height="25"><input type="text" name="town_city" size="20" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "town_city" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">County or State</td>
<td width="70%" height="25"><input type="text" name="county_state" size="20" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "county_state" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Country</td>
<td width="70%" height="25"><input type="text" name="country" size="20" maxlength="100" value="<?php echo $cgi->htmlEncode ( $row [ "country" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Post Code</td>
<td width="70%" height="25"><input type="text" name="postcode" size="10" maxlength="10" value="<?php echo $cgi->htmlEncode ( $row [ "postcode" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Telephone</td>
<td width="70%" height="25"><input type="text" name="telephone" size="20" maxlength="25" value="<?php echo $cgi->htmlEncode ( $row [ "telephone" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">Mobile</td>
<td width="70%" height="25"><input type="text" name="mobile" size="20" maxlength="25" value="<?php echo $cgi->htmlEncode ( $row [ "mobile" ] ); ?>" /></td>
</tr>
<tr>
<td width="30%" align="right" height="25">E-mail</td>
<td width="70%" height="25"><input type="text" name="email" size="30" maxlength="150" value="<?php echo $cgi->htmlEncode ( $row [ "email" ] ); ?>" /></td>
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
</form></td>
<td valign="top" width="1" bgcolor="#999999"></td>
<td valign="top"><p align="center"><strong>Completed Application Forms</strong></p><?php


{
/************  FEATURED PROPERTY CONFIG  ***********/
$limit = "20"; // max number
$columns = "3"; // number of columns
/************  END FEATURED CONFIG  ***********/
$query = "SELECT * FROM " . $check_table . "  WHERE  status = 'Completed' AND memberid = '".$row [ "username"]."'";

    $results = $sql->execute ( $query, SQL_RETURN_ASSOC );
    $total_results = sizeof ( $results );
    $total_pages = ceil ( $total_results / $limit ); //total number of pages
    $page = $cgi->getValue ( "page" );

    if ( ! $page )
      $page = 1;
      
    $offset = ( $page - 1 ) * $limit; //starting number for displaying results out of DB 
    $max = $offset + $limit;
    $max = ( $max > $total_results ? $total_results : $max );
    

     $cli_query = mysql_query($query) or die("currently unavailable");
     $cli_num = mysql_num_rows($cli_query);
    for ( $l = $offset; $l < $max; ++$l )
    { 
      $row = $results [ $l ];


if($cli_num > 0) { //
	  $x=0;
print("<table width=\"100%\" border=\"0\" align=\"center\"><tr>");
  	  	
  while($cli = mysql_fetch_array($cli_query)) {
  $Price = number_format ( $cli [ 'propertyprice' ], 0, ".", "," ); 
$x=$x+1;

if ($x % $columns == 0) {

      // Start of Display Output
  $Price = number_format ( $cli [ 'propertyprice' ], 0, ".", "," ); 
print "<td align=\"center\" valign=\"top\">
<p align=\"center\"><a href=\"../download.php?f=$cli[filename]\" target=\"new\"><IMG SRC=\"../images/pdf-logo-large.gif\" BORDER=\"0\"></a><br><small>$cli[application_ref]<br>$cli[date_completed]</small></p></td>";
print "</tr><tr>";
}else{

print "<td align=\"center\" valign=\"top\">
<p align=\"center\"><a href=\"../download.php?f=$cli[filename]\" target=\"new\"><IMG SRC=\"../images/pdf-logo-large.gif\" BORDER=\"0\"></a><br><small>$cli[application_ref]<br>$cli[date_completed]</small></p></td>";

}

}//end while

print("</tr></table>");
}//end if num >0    

    }  
}

    ?></td>
</tr>
</table>

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
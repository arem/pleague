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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Manage Poker Players</font></h1>
<br />
<p>&nbsp;</p>
<?php
  $results = $sql->execute ( "
      SELECT
      *
      FROM
      " . $player_table . "",
    SQL_RETURN_ASSOC );
    
    $total_results = sizeof ( $results );

    if ( $total_results == 0 )
    {
      ?>
<br><br><p align="center">No players could be found - you need to <a href="player-add.php">add a few player</a>!</p><br>
<?php

    }
if ( $total_results  >0 ) {    
    $total_pages = ceil ( $total_results / $search_limit ); //total number of pages

    $page = $cgi->getValue ( "page" );

    if ( ! $page )
      $page = 1;
      
    $offset = ( $page - 1 ) * $search_limit; //starting number for displaying results out of DB 
    $max = $offset + $search_limit;
    $max = ( $max > $total_results ? $total_results : $max );
    
?>
<p align="center">The following completed applications were found.</p><br>
<TABLE WIDTH="95%" border="0" align="center" CELLPADDING="4" CELLSPACING="1" bgcolor="#999999">
<TR>
<TD bgcolor="#F9F3EE"><strong>Player Name</strong></TD>
<TD bgcolor="#F9F3EE"><strong>Player Nicname</strong></TD>
<TD bgcolor="#F9F3EE"><strong>Email</strong></TD>
<TD bgcolor="#F9F3EE"><strong>Date Added</strong></TD>
<TD bgcolor="#F9F3EE"><strong>Edit</strong></TD>
</TR>
<?php
  
    for ( $l = $offset; $l < $max; ++$l )
    { 
      $row = $results [ $l ];

      $id               = $row [ "id" ];
	  $playerid               = $row [ "playerid" ];
      $name      = $row [ "name" ];
	  $email    = $row [ "email" ];
      $dateadded     = $row [ "dateadded" ];

      ?>
<TR>
<TD bgcolor="#FFFFFF">
&nbsp;&nbsp;<?php echo $cgi->htmlEncode ( $name ); ?></TD>
<TD bgcolor="#FFFFFF"><?php echo $cgi->htmlEncode ( $playerid ); ?></TD>
<TD bgcolor="#FFFFFF"><a href="mailto:<?php echo $cgi->htmlEncode ( $email ); ?>"><?php echo $cgi->htmlEncode ( $email ); ?></a></TD>
<TD bgcolor="#FFFFFF"><?php echo $cgi->htmlEncode ( $dateadded ); ?></TD>
<TD bgcolor="#FFFFFF"><form method="post" action="player-manage_.php">
<input name="op" type="hidden" value="complete">
<input name="pid" type="hidden" value="<?php echo $cgi->htmlEncode ( $playerid ); ?>">
<P>
<input name="Edit" type="submit" value="Edit Info" >
</P>
</form></TD>
</TR>
<?php
    }


  if ( $total_results != 0 )
  {
    ?>
</table>
<?php
      // End of Display output
    }  

    ##################################
    ?><br>
<p>Page  - <?php
    
    if ( $page != 1 )
    { 
    ?><a href="<?php echo $_SERVER [ 'PHP_SELF' ]; ?>?op=search&page=1">&lt;&lt; First</a>&nbsp;&nbsp;-&nbsp;<?php
      
      $prevpage = $page - 1; 
    }

    $to = ( $page < $total_pages - 2 ? $page + 3 : $total_pages );
    $from = ( $page >= 1 && $page <= 3 ? 1 : $page - 3 );

    for ( $i = $from; $i <= $to; ++$i ) 
    { 
      if ( $i == $total_results )
        $to = $total_results; 
        
      if ( $i != $page )
      { 
        ?><a href="?showold=yes&page=<?php echo $i; ?>"><?php echo $i; ?></a><?php
      } 
      else 
      { 
        ?><b>[<?php echo $i; ?>]</b><?php
      } 
      
      if ( $i != $total_pages ) 
        ?>&nbsp;&nbsp;<?php
    } 

    if ( $page != $total_pages )
    { 
      $nextpage = $page + 1; 

      ?>&nbsp;-&nbsp;&nbsp;<a href="?op=search&page=<?php echo $total_pages; ?>">Last &gt;&gt;</a><?php
    }
    }
    ?>
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
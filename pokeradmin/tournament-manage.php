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
<img src="images/home.gif" width="25" height="25" />&nbsp;PokerMax Poker League :: <font color="#CC0000">Manage Poker Tournament</font></h1>
<br />
<?php
  $results = $sql->execute ( "
      SELECT
      *
      FROM
      " . $tournament_table . " ORDER BY id DESC",
    SQL_RETURN_ASSOC );
    
    $total_results = sizeof ( $results );

    if ( $total_results == 0 )
    {
      ?>
<br><br><p align="center">No tournaments could be found - you need to <a href="tournament-create.php">add a new tournament</a>!</p><br>
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
    
?><form method="post" action="print.php" target="_blank">
<input name="op" type="hidden" value="PrintLeaderboard">
 <input name="Edit" type="submit" value="Print / View Leaderboard" >
</form>
<p align="center">The following poker tournaments were found.</p><br>
<TABLE WIDTH="95%" border="0" align="center" CELLPADDING="4" CELLSPACING="1" bgcolor="#999999">
<TR>
<TD bgcolor="#F9F3EE"><strong>Tournament ID</strong></TD>
<TD bgcolor="#F9F3EE"><strong>Tournament Name</strong></TD>
<TD bgcolor="#F9F3EE" align="center"><strong>No. of Players</strong></TD>
<TD bgcolor="#F9F3EE" align="center"><strong>Information</strong></TD>
<TD bgcolor="#F9F3EE" align="center"><strong>Tournament Date</strong></TD>
<TD bgcolor="#F9F3EE" align="center"<strong>Edit</strong></TD>
</TR>
<?php
  
    for ( $l = $offset; $l < $max; ++$l )
    { 
      $row = $results [ $l ];

      $id               = $row [ "id" ];
      $tournamentid     = $row [ "tournamentid" ];
      $tournament_name  = $row [ "tournament_name" ];
      $tournament_date     = $row [ "tournament_date" ];

      ?>
<TR>
<TD bgcolor="#FFFFFF">
&nbsp;&nbsp;<?php echo $cgi->htmlEncode ( $tournamentid ); ?></TD>
<TD bgcolor="#FFFFFF"><?php echo $cgi->htmlEncode ( $tournament_name ); ?></TD>
<TD bgcolor="#FFFFFF" align="center">
 <?php {
// Get the Number of Members who are active
$result = mysql_query("SELECT * FROM ".$score_table." WHERE tournamentid = '". $cgi->htmlEncode ( $tournamentid )."'" ) or die ("$DatabaseError"); 
$tournament_entrants = mysql_numrows($result);
echo "$tournament_entrants";
} 
?>
</TD>
<TD bgcolor="#FFFFFF" align="center"><a href="print.php?tid=<?php echo $cgi->htmlEncode ( $tournamentid ); ?>&op=PrintTournamentResults" target="blank_">Info / Results</a></TD>
<TD bgcolor="#FFFFFF" align="center"><?php echo $cgi->htmlEncode ( $tournament_date ); ?></TD>
<TD bgcolor="#FFFFFF"><form method="post" action="tournament-manage_.php">
<input name="tid" type="hidden" value="<?php echo $cgi->htmlEncode ( $tournamentid ); ?>">
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
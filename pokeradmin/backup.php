<?php

  include "../includes/config.php";
if (isset($_COOKIE["ValidUserAdmin"]))
{
  require ( "../includes/CGI.php" );
  require ( "../includes/SQL.php" );
  require ( "../includes/backup-zip.lib.php" );
  $cgi = new CGI ();
  $sql = new SQL ( $DBusername, $DBpassword, $server, $database );

  if ( ! $sql->isConnected () )
  {
    die ( $DatabaseError );
  }
$full_path_to_public_program = str_replace('\\','/',getcwd());
$full_path_to_public_program = str_replace("admin", "", "$full_path_to_public_program");
$backup_dir = $full_path_to_public_program."admin/backup/";
$set_backup = true;
?>
<HTML>
<HEAD>
<TITLE>PokerMax Poker League</TITLE>
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
<IMG SRC="images/home.gif" WIDTH="25" HEIGHT="25">&nbsp;PokerMax Poker League :: <FONT COLOR="#CC0000">Back Up Database</FONT></H1>
<BR>
<p>It is important to make a regular back-up of your database and config file in case of any server failure. This procedure will perform a back-up of all your  data in .SQL format. To back-up your config.php file and all PDF files, please check the relevant boxes. Please be aware that if you have a large number of PDF files this will take quite a while. It is recommended that you download the PDF files  via FTP to save server load time.<br />
<br />It is recommended that you download and store the zip file on your own PC as well as the server. Back-ups performed on the same day will overwrite the existing back-up performed that day.</p>


<?
function getParam($key,$option){
if(isset($_GET[$key])){
return($_GET[$key]);
}else{
return false;
}
}
if ($set_backup)
{


if (!file_exists("backup/.htaccess"))
{

	echo "<p><b><font color='red'>Warning:</font></b> The backup directory is not password protected. It´s reccomended that you apply password protection
	using .htaccess files, or do not use this tool.</p><br>
";
}		


	print('
	<FORM METHOD="POST" ACTION="?backup=1">
<P>
<input type="checkbox" name="bdata" value="1" checked> Check box to back-up all your database<br />
<input type="checkbox" name="bconfigfile" value="1"> Check box to back-up your config.php file<br />
<INPUT TYPE="SUBMIT" VALUE="Create New Back-up"> <input type="checkbox" name="bshowlog" value="1" /> Show back-up log progress</P></FORM> 
	');

	

function mysqlbackup($host,$dbname, $uid, $pwd, $output, $structure_only)
{

	if (strval($output)!="") $fptr=fopen($output,"w"); else $fptr=false;

	//connect to MySQL database
	$con=mysql_connect($host,$uid, $pwd);
	$db=mysql_select_db($dbname,$con);

	//open back-up file ( or no file for browser output)

	//set up database
	out($fptr, "create database $dbname;\n\n");

	//enumerate tables
	$res=mysql_list_tables($dbname);
	$nt=mysql_num_rows($res);

	for ($a=0;$a<$nt;$a++)
	{
		$row=mysql_fetch_row($res);
		$tablename=$row[0];

		//start building the table creation query
		$sql="create table $tablename\n(\n";

		$res2=mysql_query("select * from $tablename",$con);
		$nf=mysql_num_fields($res2);
		$nr=mysql_num_rows($res2);

		$fl="";

		//parse the field info first
		for ($b=0;$b<$nf;$b++)
		{
			$fn=mysql_field_name($res2,$b);
			$ft=mysql_fieldtype($res2,$b);
			$fs=mysql_field_len($res2,$b);
			$ff=mysql_field_flags($res2,$b);

			$sql.="	$fn ";

			$is_numeric=false;
			switch(strtolower($ft))
			{
				case "int":
					$sql.="int";
					$is_numeric=true;
					break;

				case "blob":
					$sql.="text";
					$is_numeric=false;
					break;

				case "real":
					$sql.="real";
					$is_numeric=true;
					break;

				case "string":
					$sql.="varchar($fs)";
					$is_numeric=false;
					break;

				case "unknown":
					switch(intval($fs))
					{
						case 4:	//little weakness here...there is no way (thru the PHP/MySQL interface) to tell the difference between a tinyint and a year field type
							$sql.="tinyint";
							$is_numeric=true;
							break;

						default:	//we could get a little more optimzation here! (i.e. check for medium ints, etc.)
							$sql.="int";
							$is_numeric=true;
							break; 
					}
					break;

				case "timestamp":
					$sql.="timestamp"; 
					$is_numeric=true;
					break;

				case "date":
					$sql.="date"; 
					$is_numeric=false;
					break;

				case "datetime":
					$sql.="datetime"; 
					$is_numeric=false;
					break;

				case "time":
					$sql.="time"; 
					$is_numeric=false;
					break;

				default: //future support for field types that are not recognized (hopefully this will work without need for future modification)
					$sql.=$ft;
					$is_numeric=true; //I'm assuming new field types will follow SQL numeric syntax..this is where this support will breakdown 
					break;
			}

			//VERY, VERY IMPORTANT!!! Don't forget to append the flags onto the end of the field creator

			if (strpos($ff,"unsigned")!=false)
			{
				//timestamps are a little screwy so we test for them
				if ($ft!="timestamp") $sql.=" unsigned";
			}

			if (strpos($ff,"zerofill")!=false)
			{
				//timestamps are a little screwy so we test for them
				if ($ft!="timestamp") $sql.=" zerofill";
			}

			if (strpos($ff,"auto_increment")!=false) $sql.=" auto_increment";
			if (strpos($ff,"not_null")!=false) $sql.=" not null";
			if (strpos($ff,"primary_key")!=false) $sql.=" primary key";

			//End of field flags

			if ($b<$nf-1)
			{
				$sql.=",\n";
				$fl.=$fn.", ";
			}
			else
			{
				$sql.="\n);\n\n";
				$fl.=$fn;
			}

			//we need some of the info generated in this loop later in the algorythm...save what we need to arrays
			$fna[$b]=$fn;
			$ina[$b]=$is_numeric;
			
		}

		out($fptr,$sql);

		if ($structure_only!=true)
		{
			//parse out the table's data and generate the SQL INSERT statements in order to replicate the data itself...
			for ($c=0;$c<$nr;$c++)
			{
				$sql="insert into $tablename ($fl) values (";

				$row=mysql_fetch_row($res2);

				for ($d=0;$d<$nf;$d++)
				{
					$data=strval($row[$d]);
				
					if ($ina[$d]==true)
						$sql.= intval($data);
					else
						$sql.="\"".mysql_escape_string($data)."\"";

					if ($d<($nf-1)) $sql.=", ";
	
				}

				$sql.=");\n";

				out($fptr,$sql);

			}

			out($fptr,"\n\n");

		}

		mysql_free_result($res2);	

	}
	
	if ($fptr!=false) fclose($fptr);
	return 0;

}

function out($fptr,$s)
{
	if ($fptr==false) echo("$s"); else fputs($fptr,$s);
}



//------   Backup of config files ---------
if (getParam("backup","") == 1)
{

function dirList($directory)
 {
 		$fresults = array();
 			$handler = opendir($directory);
 		while ($file = readdir($handler)){
 	if($file != '.' && $file != '..')
 $fresults[] = $directory."/".$file;
 	}
 closedir($handler);
 	return $fresults;
 }

	
	$date = time();
	$filedate=date("d-m-Y");
	mysqlbackup("$server","$database","$DBusername","$DBpassword",$backup_dir."mysql_".$filedate.".sql",false);
	
	// Set up what to do full backup of
	if(isset($_POST['bfiles'])){ $lBackupArray = dirList($full_path_to_public_program."files339"); }
    if(isset($_POST['bdata'])){ $lBackupArray[] = $backup_dir."mysql_".$filedate.".sql"; }
	if(isset($_POST['bconfigfile'])){ $lBackupArray[] = $full_path_to_public_program."includes/config.php"; }

	echo "<p><b><font color=red>Backup is completed</font></b></p>";



 	if(isset($_POST['bshowlog'])){   echo "<br /><b>Building zip file</b>"; }
	$zipfile = new zipfile();
	$zipfile->addFiles($lBackupArray);
	
	 for($ix = 0; $ix<sizeof($lBackupArray); $ix++){
 	if(isset($_POST['bshowlog'])){  echo "<br /><b>File added</b> ... ".$lBackupArray[$ix]; }
	 }
	$zipfile->output($backup_dir.$filedate.".zip");
	
	@chmod ($backup_dir .$filedate.".zip", 0777);
	@chmod ($backup_dir ."mysql_".$filedate.".sql", 0777);
	@unlink ($backup_dir ."mysql_".$filedate.".sql");
	
}

echo "<br /><br /><b>Current Backups</b> (in .zip format)<br /><br />";

if (getParam("delzip",""))
{
	unlink($backup_dir."/" . getParam("delzip",""));
	echo "<font color=red>Backup ".getParam("delzip","")." deleted</font><br /><br />";
}

$dir = opendir($backup_dir);
while ($file = readdir($dir))
{
	if (eregi("zip",$file))
		$str.= '<TR VALIGN="MIDDLE" height=\"30\">
<TD ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR="#FFFFFF">
'.substr($file,0,-4).'</td>
<TD ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR="#FFFFFF">
' . date("H:i",filemtime($backup_dir.'/'.$file)).'
</td>
<TD ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR="#FFFFFF">
' . round(filesize($backup_dir.'/'.$file)/1024000,2) .' MB</td>
<TD ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR="#FFFFFF">
<img src="images/winzip.gif" hspace="5"/><a href="backup/'.$file.'">Download</a></td>
<TD ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR="#FFFFFF"><a href="?delzip='.$file.'">Delete</a></td></tr>';
}

if (!$str)
	echo "<p>No back-ups were found in the Back Up directory..</p>";
else 
	echo '<TABLE WIDTH="100%" CELLPADDING="1" CELLSPACING="1" BGCOLOR="#CCCCCC">
<TR>
<TD ALIGN="CENTER">
<P><B>Back Up Date</B></P></TD>
<TD ALIGN="CENTER">
<P><B>Time</B></P></TD>
<TD ALIGN="CENTER">
<P><B>Back up Size</B></P></TD>
<TD ALIGN="CENTER">
<P><B>Download</B></P></TD>
<TD ALIGN="CENTER">
<P><B>Delete</B></P></TD>
</TR>'.$str.'</table>';
	

}
else 
{
	echo "<font color='red'>For some reason you are not permitted by your host to use this back up feature.</font>";
	
}
?>
<br>
<br>
<br>

</TD>
</TR>
</TABLE>
<?PHP include ("footer.php"); ?>
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
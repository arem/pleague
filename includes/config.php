<?PHP
/*
 PokerMax Pro Poker League Software
 Written by Steve Dawson - 01-07-2007 www.stevedawson.com
*/

/** Complete your database info below and then run the yourdomain.com/install/  **/
/**************** DATABASE MODIFICATION SECTION ****************/
$server = "host";                              // Server Host
$DBusername = "username";                               // Database Username
$DBpassword = "password";                                   // Database Password
$database = "database";                       // Database Name

$tournament_table = "pokermax_tournaments"; 
$admin_table = "pokermax_admin";
$player_table = "pokermax_players";
$score_table = "pokermax_scores";
 
/**************** OPTIONS SECTION ****************/
$search_limit = "50"; // listings per page - tournaments and players
$DatabaseError = "<p align=\"center\" class=\"red\">No details found, please try again later</p>";
# $playerid = date("dmyHis");
$tournamentid = date("dmyHis");
/**************** DATE STAMP SECTION ****************/
$dateadded = date("j F Y");     // Date and time of info request
$version = "v0.13";

?>

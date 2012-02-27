<?php

include('config.php');

// Connect to MySQL Database
#function connect_mysql(){

$link = mysql_connect($mysqlhost, $mysqluser, $mysqlpass);
	if (!$link) {
	    die('keine Verbindung möglich: ' . mysql_error());
	}
#}


function decho($string) {
  if(defined('DEBUG') && DEBUG) {
    printf("%s - %s", date('D, d M Y H:i:s'), $string);
  }
}


// Read Mac-Addresses from Dell 54xx-Series Switches
function readmac5424n($switchf,$macarrayf) {
  $a = snmp2_walk("$switchf", "public", ".1.3.6.1.2.1.17.4.3.1.1");
  foreach ($a as $val) {
    $mac_ = substr($val, 12,-1);
    $pattern = '/([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)$/';
    preg_match( $pattern, $mac_, &$mac ) ;
    $rmac =  $mac[1].":".$mac[2].":".$mac[3].":".$mac[4].":".$mac[5].":".$mac[6];
    $ip_ = $switchf;
    $npattern = '/([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)$/';
    preg_match( $npattern, $ip_, &$ip ) ;
    $network =  $ip[2].".".$ip[3];
    if ($network == "43.128"){$network = "43.28";}
    if ($network == "43.126"){$network = "43.26";}
    $macarrayf[] = "".$rmac.";".$network."";
  }
  return($macarrayf);
}

// Read Mac-Addresses from Dell 62xx-Series Switches
function readmac6248($switchf,$macarrayf){
  $a = snmp2_real_walk("$switchf", "public", "SNMPv2-SMI::mib-2.17.7.1.2.2.1.3.1");
  foreach (array_keys($a) as $val) {
    $pattern = '/([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)$/';
    preg_match( $pattern, $val, &$mac ) ; 
    $rmac = sprintf("%02X:%02X:%02X:%02X:%02X:%02X", $mac[1], $mac[2], $mac[3],
                                                     $mac[4], $mac[5], $mac[6]);
    
    $ip_ = $switchf;
    $npattern = '/([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)$/';
    preg_match( $npattern, $ip_, &$ip ) ;
    $network =  $ip[2].".".$ip[3];
    if ($network == "43.128"){$network = "43.28";}
    if ($network == "43.126"){$network = "43.26";}
    $macarrayf[] = "".$rmac.";".$network."";
  }
  return($macarrayf);
}
// Read Mac-Addresses from HP 28-Series Switches
function readmacHP28($switchf,$macarrayf){
  $a = snmp2_walk("$switchf", "public", ".1.3.6.1.4.1.11.2.14.11.5.1.9.4.2.1.2");
  foreach ($a as $val) {
    $mac_ = substr($val, 12,-1);
    $pattern = '/([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)$/';
    preg_match( $pattern, $mac_, &$mac ) ;
    $rmac =  $mac[1].":".$mac[2].":".$mac[3].":".$mac[4].":".$mac[5].":".$mac[6];
    $ip_ = $switchf;
    $npattern = '/([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)$/';
    preg_match( $npattern, $ip_, &$ip ) ;
    $network =  $ip[2].".".$ip[3];
    if ($network == "43.128"){$network = "43.28";}
    if ($network == "43.126"){$network = "43.26";}
    $macarrayf[] = "".$rmac.";".$network."";
  }
  return($macarrayf);
}

// Read Mac-Addresses from HP 18-Series Switches
function readmacHP18($switchf,$macarrayf){
  $a = snmp2_walk("$switchf", "public", ".1.3.6.1.2.1.17.4.3.1.1.0");
  foreach ($a as $val) {
    $mac_ = substr($val, 12,-1);
    $pattern = '/([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)\ ([A-F0-9]*)$/';
    preg_match( $pattern, $mac_, &$mac ) ;
    $rmac =  $mac[1].":".$mac[2].":".$mac[3].":".$mac[4].":".$mac[5].":".$mac[6];
    $ip_ = $switchf;
    $npattern = '/([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)$/';
    preg_match( $npattern, $ip_, &$ip ) ;
    $network =  $ip[2].".".$ip[3];
    if ($network == "43.128"){$network = "43.28";}
    if ($network == "43.126"){$network = "43.26";}
    $macarrayf[] = "".$rmac.";".$network."";
  }
  return($macarrayf);
}

// Send Mail that a new Device is Connected to the Network
function sendmail_unknown($badmac){
$empfaenger = '';
$betreff = 'Unbekanntes Netzwerkgeraet wurde am Netzwerk angeschlossen';
$nachricht = 'das Netzwerkgaeret mit der MAC: '.$badmac.' wurde am Netzwerk angeschlossen';
$header = 'From: ' . "\r\n" .
    'Reply-To: ' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($empfaenger, $betreff, $nachricht, $header);
}

// Send Mail that a Device which is not allowed in the network is Connected (not implemented yet)
function sendmail_notallowed($badmac){
$empfaenger = '';
$betreff = 'Nicht authentifiziertes Netzwerkgeraet angeschlossen';
$nachricht = 'das Netzwerkgaeret mit der MAC: '.$badmac.' wurde am Netzwerk angeschlossen';
$header = 'From: ' . "\r\n" .
    'Reply-To: ' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($empfaenger, $betreff, $nachricht, $header);
}

//Write Time and Network into lastseen-field
function write_lastseen($mac,$nw){
$datetime = date("Y-m-d G:i:s");
$update_lastseen = mysql_query('update devices set last_seen ="'.$datetime.'",last_network="'.$nw.'" where address = "'.$mac.'"');
if (!$update_lastseen) {
   die('Anfrage fehlgeschlagen: ' . mysql_error());
}
}

//Insert new Device into Database
function insert_new_device($mac,$nw){
$datetime = date("Y-m-d G:i:s");
$create_entry = mysql_query('INSERT INTO devices (`device_id`, `address`, `device_description`, `allowed`, `first_seen`, `first_network`) VALUES (NULL,"'.$mac.'","","0","'.$datetime.'","'.$nw.'" )');
#echo $create_entry;
	  if (!$create_entry) {
	   die('insert fehlgeschlagen: ' . mysql_error());
		}
}

//Change Device Entry
function update_device_entry($device_id,$address,$device_description,$allowed){
$update_entry = mysql_query('update devices set address ="'.$address.'" , device_description ="'.$device_description.'" , allowed = "'.$allowed.'" where device_id = "'.$device_id.'"');
if (!$update_entry) {
   die('Anfrage fehlgeschlagen: ' . mysql_error());
}
}

//Print HTML-Header
function html_header(){

echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>MAC-Adressen im Netzwerk</title>
</head>
<body>
';
}

// Print Table Header
function table_header(){

echo '

<tr>
<th>device_ID</th>
<th>Allowed</th>
<th>MAC-Adresse</th>
<th>Beschreibung</th>
<th>first_seen</th>
<th>last_seen</th>
<th>first_network</th>
<th>last_network</th>
<th>vendor</th>
<th>Netdisco Search</th>
<th>change Entry</th>
</tr>
';
}

function save_changes($device_id,$mac,$allowed,$description){
if ($allowed == ""){$allowed="0";}
$SQL = 'UPDATE devices set device_description = "'.$description.'" , allowed="'.$allowed.'" where device_id = "'.$device_id.'" and address = "'.$mac.'"';
$update = mysql_query($SQL);
if (!$update) {
   die('Anfrage fehlgeschlagen: ' . mysql_error());
}
}

// View and change Description and allowed-field of a device
function change_view($device_id){

$SQL = ' SELECT * FROM devices where device_id = "'.$device_id.'" ';
$device = mysql_query($SQL);
if (!$device) {
   die('Anfrage fehlgeschlagen: ' . mysql_error());
}
$row = mysql_fetch_array($device);
$mac = $row["address"];
$description = $row["device_description"];
//echo $description;
$device_id = $row["device_id"];
$allowed = $row["allowed"];
if ($allowed == "1"){$checked = 'checked="1"';}

echo '<form name="fa" action="index.php?submit=1" method="post">
<b>device_id: '.$row["device_id"].'</b> <input type="hidden" name="device_id" value="'.$device_id.'"  size=40> <br>
<b>mac:  '.$mac.'</b> <input type="hidden" name="mac" value="'.$mac.'" size=40><br>
<b>Allowed: </b> <input type="checkbox" name="allowed" value="1" '.$checked.'size=40><br>
<b>Description: </b> <input name="description" value="'.$description.'" size=80><br>
<p><input type="submit" value="Submit"></p>
</form>
';}

// Inserted on each Page
function links(){
echo '<a href="index.php?allowed=0">list not_allowed</a>
        <a href="index.php">list all</a>';
}

?>

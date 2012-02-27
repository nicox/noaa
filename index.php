<?php

include './functions.php';

#connect_mysql();
mysql_select_db($db_name);
html_header();
if ($_GET["change"] != ""){
#	echo "test";
	change_view($_GET["change"]);
	links();
	exit;
}
if ($_GET["submit"] == "1"){
	$device_id = $_POST["device_id"];
	$mac = $_POST["mac"];
	$allowed = $_POST["allowed"];
	$description = $_POST["description"];
	save_changes($device_id,$mac,$allowed,$description);
	change_view($device_id);
	links();
	exit;
}
  links();
  $SQL = " SELECT * FROM devices LEFT JOIN mac_vendors ON mac_prefix = SUBSTRING( address, 1, 8 )";
  if ($_GET["allowed"] == "0"){ $SQL = $SQL."where allowed = '0'";}
//  $SQL = $SQL."order by address asc";
  $retid = mysql_query($SQL);
  if (!$retid) { echo( mysql_error()); }
	
#$row = mysql_fetch_array($retid);
$siteurl = $row["siteurl"];
echo '<h1>Devices im Netzwerk</h1>';
echo '<table border="1">';
table_header();
$count = 1;
while ($row = mysql_fetch_array($retid)) { 
	$count = $count + 1;
	$mac = $row["address"]; 
	$description = $row["device_description"]; 
	$first_seen = $row["first_seen"];
	$last_seen = $row["last_seen"];
	$device_id = $row["device_id"];
	$first_nw = $row["first_network"];
	$last_nw = $row["last_network"];
	$vendor = $row["vendor"];
	if ($row["allowed"] == "1"){$allowed = "yes";}else{$allowed = "no";}
	if ( $description == ""){$description = "N/A";}
	if ( $count == 30 ){
		table_header();
		$count = 1;
		} 
	echo ' <tr>
	    <td>'.$device_id.'</td>
	    <td>'.$allowed.'</td>
	    <td>'.$mac.'</td>
	    <td>'.$description.'</td>
	    <td>'.$first_seen.'</td>
	    <td>'.$last_seen.'</td>
	    <td>'.$first_nw.'</td>
	    <td>'.$last_nw.'</td>
	    <td>'.$vendor.'</td>
	    <td><a href="http://rt.sber.co.at/netdisco/node.html?node='.$mac.'" target="_blank">search in Netdisco</a></td>
	    <td><a href="index.php?change='.$device_id.'">change entry</a></td>
	</tr>
	';

#echo ("<dd><a href='$mac'>$description</a></dd>\n");
#echo ("<dd><a href='$mac'>$device_description</a></dd>\n"); 
} 
echo ("</table>"); 

?>

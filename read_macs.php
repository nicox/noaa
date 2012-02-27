<?php

#exit;
define('DEBUG', true);
error_reporting(E_ERROR);
include 'functions.php';

#connect_mysql();
$macarray = array();


foreach ($switche5424 as $switch) {
	$macarray = readmac5424n($switch,$macarray);
}
foreach ($switche6248 as $switch) {
	$macarray = readmac6248($switch,$macarray);
}
foreach ($switcheHP28 as $switch) {
	$macarray = readmacHP28($switch,$macarray);
}
foreach ($switcheHP18 as $switch) {
        $macarray = readmacHP18($switch,$macarray);
}


$unique_mac = array_unique($macarray);
foreach ($unique_mac as $i) {
	mysql_select_db($dbname);
	$split = split(';',$i);
	$mac = $split[0];
	$nw = $split[1];

	$result = mysql_query('select address, allowed from devices where address like "%'.$mac.'%" and allowed="1"');
	if (!$result) {
	   die('Anfrage fehlgeschlagen: ' . mysql_error());
	}	
	$count = mysql_num_rows($result);
	if ($count ==  "0"){
#	  sendmail_notallowed($mac);
	}
		$result1 = mysql_query('select address, allowed from devices where address like "%'.$mac.'%"');
		if (!$result1) {
	           die('Anfrage1 fehlgeschlagen: ' . mysql_error());
	        }
		$count1 = mysql_num_rows($result1);
		if ($count1 == "0"){
		  sendmail_unknown($mac);
		  insert_new_device($mac,$nw);
	}
write_lastseen($mac,$nw);
}


?>

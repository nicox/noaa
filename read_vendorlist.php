<?php

#exit;
define('DEBUG', true);

include 'functions.php'
include 'mysql_connection.php';

$db = get_mysql_con($dbname);

$sth_insert = $db->prepare('INSERT INTO mac_vendors VALUES (?, ?)');

$db->exec("DELETE FROM mac_vendors");

$file_handle = fopen("http://standards.ieee.org/develop/regauth/oui/oui.txt", "r");
while (!feof($file_handle)) {
   $line = fgets($file_handle);
   if( preg_match('/^([0-9ABCDEF]{2})-([0-9ABCDEF]{2})-([0-9ABCDEF]{2})   \(hex\)(.*)$/',
                  $line, $treffer) ) {
      $mac = $treffer[1].":".$treffer[2].":".$treffer[3];
      $vendor = trim($treffer[4]);
      $affectedRows = $sth_insert->execute(array($mac, $vendor));
//      echo $mac . " => " . $vendor . "<br />\n";
   }
}
fclose($file_handle);

?>

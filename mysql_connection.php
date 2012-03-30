<?php

  require_once 'MDB2.php';

   /**
    * Stellt eine DB Verbindung zur MySQL Datenbank her.
    * @param: $dbname   Datenbank Name
    */
   function get_mysql_con($dbname, $options = array()){
     $dbuser = $mysqluser;
     $dbpass = $mysqlpass;
     $dbhost = $mysqlhost;

     $dsn  = "mysql://$dbuser:$dbpass@$dbhost/$dbname";
     $myDb = MDB2::connect($dsn, $options);

     if (PEAR::isError($myDb)) {

        $message = "MySQL Verbindungsaufbau Fehlgeschlagen: " . $myDb->getMessage();
        $message .= " [" . $_SERVER['REQUEST_URI'] . "] ";

        if (!empty($myDb->backtrace[1]['file'])) {
            $message .= ' (' . $myDb->backtrace[1]['file'];
            if (!empty($myDb->backtrace[1]['line'])) {
                $message .= ' at line ' . $myDb->backtrace[1]['line'];
            }
            $message .= ')';
        }

        error_reporting(E_ALL ^ E_NOTICE);
        error_log ($message, 0);
        error_reporting(E_ALL);

        die("Es ist ein Fehler beim Aufbau der Datenbank aufgetreten!");
     }

     return $myDb;
   }

?>

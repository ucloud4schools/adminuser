<?php

require('../../config/config.php');
$prefix=$CONFIG["dbtableprefix"];


$dbhost=$CONFIG["dbhost"];
$db=$CONFIG["dbname"];
$dbuser=$CONFIG["dbuser"];
$dbpw=$CONFIG["dbpassword"];

// Damit alle Fehler angezeigt werden
error_reporting(E_ALL & ~E_NOTICE);
//Verbindungsaufbau
define ( 'MYSQL_HOST',      ''.$dbhost.'' );
define ( 'MYSQL_BENUTZER',  ''.$dbuser.'' );
define ( 'MYSQL_KENNWORT',  ''.$dbpw.'' );
define ( 'MYSQL_DATENBANK', ''.$db.'' );
define ( 'MYSQL_PREFIX', ''.$prefix.'' );

?>
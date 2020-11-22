<?php
define('DBHOST', 'localhost');
define('DBNAME', 'art');
define('DBUSER', 'root');
define('DBPASS', '');
// define('DBCONNSTRING', 'sqlite:./databases/art.db');
// location of database(host= .DBHOST)', type of database(mysql), name of database(dbname = .DBNAME)
define('DBCONNSTRING',"mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4;");
?>
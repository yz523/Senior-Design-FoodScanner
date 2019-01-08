<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'saikuk');
define('DB_PASSWORD', 'TECIl4^ya$[R');
define('DB_NAME', 'foodscanner');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connecttttt. " . mysqli_connect_error());
}
?>

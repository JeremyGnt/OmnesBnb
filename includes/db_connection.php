<?php
/*
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'omnesbnb');
*/

define('DB_SERVER', 'fdb1027.your-hosting.net');
define('DB_USERNAME', '4620502_omnesbnb');
define('DB_PASSWORD', 'b3JtIR7w0M]X1Do:');
define('DB_NAME', '4620502_omnesbnb');

//$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, 3306);

if($conn === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}
?>

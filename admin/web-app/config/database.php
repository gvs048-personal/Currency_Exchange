<?php
// $servername = "database-5015301400.webspace-host.com";
// $username = "dbu767070";
// $password = "6n7hnv0Eve71q637";
// $dbname = "dbs12585408";

//localhost
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'exchange_db';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

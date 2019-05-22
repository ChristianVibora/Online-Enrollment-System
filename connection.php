<?php
#  © 2016-2017 →rEVOLution← Studios #

$servername = "localhost";
$username = "root";
$password = "root";
$database = "demo_enrollmentsystemdatabase";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("<span class='error'><b>Error: </b>Connection Failed: " . $connection->connect_error);
}
?>
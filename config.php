<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_booking";


$conn = new mysqli($servername, $username, $password, $dbname,3307);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

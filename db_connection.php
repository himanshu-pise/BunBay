<?php
$servername = "localhost";
$username = "root"; // Change as necessary
$password = ""; // Change as necessary
$dbname = "bunbay";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


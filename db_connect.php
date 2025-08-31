<?php
$servername = "localhost";
$username   = "root";   // XAMPP default
$password   = "";       // XAMPP default is empty
$database   = "Infosystem";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

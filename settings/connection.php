<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "estate_listings";

// Create connection
$conn = new mysqli($host, $username, $password, $database, 3312);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

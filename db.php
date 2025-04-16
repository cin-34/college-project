<?php
// Database connection settings
$servername = "localhost";  // Change to your server (e.g., "localhost")
$username = "root";         // Database username (default in XAMPP is "root")
$password = "";             // Database password (default in XAMPP is an empty string)
$dbname = "bookstore";  // Name of the database you are using (e.g., "library")

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

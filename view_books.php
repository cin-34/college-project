<?php
include 'db.php';

// Fetch books from the database
$result = $conn->query("SELECT * FROM book1");

echo "<h1>Available Books</h1>";
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<h3>" . $row['title'] . "</h3>";
    echo "<p>Author: " . $row['author'] . "</p>";
    echo "<p>Description: " . $row['description'] . "</p>";
    echo "<a href='" . $row['file_path'] . "' download>Download</a>";
    echo "</div>";
}

$conn->close();
?>

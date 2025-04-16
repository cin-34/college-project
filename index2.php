<?php
// Connect to database
$servername = "localhost";
$username = "root"; // default username for XAMPP
$password = ""; // default password is empty for XAMPP
$dbname = "bookstore";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Archives</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to the Digital Archives</h1>
        <nav>
            <ul>
                <li><a href="add_book.php">Add Book</a></li>
                <li><a href="add_patron.php">Add Patron</a></li>
                <li><a href="circulation.php">Circulation</a></li>
                <li><a href="report.php">Reports</a></li>
            </ul>
        </nav>
    </header>

    <h2>Books in the Archive</h2>
    <?php
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>Title</th><th>Author</th><th>Category</th><th>Year Published</th><th>ISBN</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["title"]. "</td><td>" . $row["author"]. "</td><td>" . $row["category"]. "</td><td>" . $row["year_published"]. "</td><td>" . $row["isbn"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No books found";
    }
    ?>
</body>
</html>

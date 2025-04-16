<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$name = $_SESSION['name'];  // Retrieve the user's name from the session

// Get available books
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<h2>Welcome, <?php echo $name; ?></h2>
<h3>Available Books</h3>
<ul>
<?php
while ($book = $result->fetch_assoc()) {
    echo "<li>
            <img src='uploads/" . $book['image'] . "' width='100'>
            <h4>" . $book['title'] . " by " . $book['author'] . "</h4>
            <a href='borrow.php?book_id=" . $book['id'] . "'>Borrow</a>
          </li>";
}
?>
</ul>

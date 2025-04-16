<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'php/db.php';

$result = $conn->query("SELECT * FROM book1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
</head>
<body>
    <h1>Manage Books</h1>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['author']); ?></td>
            <td><?= htmlspecialchars($row['description']); ?></td>
            <td>
                <a href="edit_book.php?id=<?= $row['id']; ?>">Edit</a>
                <a href="delete_book.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
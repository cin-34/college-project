<?php
include 'db.php';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: register.html');
    exit();
}

// Fetch books for the selected subcategory
$subcategory_id = isset($_GET['subcategory_id']) ? $_GET['subcategory_id'] : 0;
$books_sql = "SELECT * FROM books WHERE subcategory_id = '$subcategory_id'";
$books_result = $conn->query($books_sql);

// Fetch subcategory name
$subcategory_sql = "SELECT subcategory_name FROM subcategories WHERE id = '$subcategory_id'";
$subcategory_result = $conn->query($subcategory_sql);
$subcategory = $subcategory_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books in <?php echo $subcategory['subcategory_name']; ?></title>
</head>
<body>
    <h1>Books in <?php echo $subcategory['subcategory_name']; ?></h1>

    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Action</th>
        </tr>
        <?php while ($book = $books_result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td>
                <a href="<?php echo $book['file_path']; ?>" target="_blank">Read</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

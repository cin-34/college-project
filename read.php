<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php?error=login_required");
    exit();
}

require_once 'db.php';
$book_id = $_GET['book_id'];

$query = "SELECT file_path FROM books WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if ($book) {
    header("Content-Type: application/pdf");
    readfile($book['file_path']);
} else {
    echo "Book not found.";
}
?>

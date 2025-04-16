<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle book borrowing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];

    // Borrow the book (assuming no existing borrow)
    $query = "INSERT INTO borrowed_books (book_id, patron_id, borrowed_date) 
              VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $book_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Book borrowed successfully!";
    } else {
        $_SESSION['message'] = "Error: Could not borrow book.";
    }

    // Redirect back to the borrowed books page or wherever needed
    header("Location: book_list.php");
    exit();
}
?>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$successMessage = '';  // Variable to hold the success message

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];

    // Check if book is actually borrowed
    $checkQuery = "SELECT * FROM borrowed_books WHERE patron_id = ? AND book_id = ? AND returned IS NULL";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $user_id, $book_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Mark as returned
        $query = "UPDATE borrowed_books SET returned = NOW() WHERE patron_id = ? AND book_id = ? AND returned IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $successMessage = "Book returned successfully!";
        } else {
            $successMessage = "Error: Could not return book.";
        }
    } else {
        $successMessage = "Error: No matching borrowed book found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .message-box {
            background-color: #4caf50;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            margin: 20px auto;
            width: 50%;
        }

        .message-box.error {
            background-color: #f44336;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <?php if ($successMessage): ?>
        <div class="message-box <?= strpos($successMessage, 'success') !== false ? '' : 'error' ?>">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <a href="borrowed_books.php" class="button">Back to Borrowed Books</a>

</body>
</html>

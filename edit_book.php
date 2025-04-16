<?php
session_start();

// Include database connection
require_once 'db.php';

// Get the book ID from the URL
if (!isset($_GET['book_id']) || empty($_GET['book_id'])) {
    die('Book ID is required.');
}

$book_id = intval($_GET['book_id']);

// Fetch the book details
$sql = "SELECT * FROM books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Book not found.');
}

$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Back button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #007bff;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.3);
        }

        .back-button i {
            font-size: 18px;
        }

        .back-button:hover {
            background: #0056b3;
        }

        .form-container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            height: 120px;
        }

        .form-actions {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Back to Manage Books Button -->
    <a href="manage_book.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Manage Books
    </a>

    <div class="form-container">
        <h2>Edit Book</h2>
        <form action="update_book.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">

            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($book['book_title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?= htmlspecialchars($book['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="book_image">Book Image</label>
                <input type="file" name="book_image" id="book_image">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Book</button>
            </div>
        </form>
    </div>

</body>
</html>

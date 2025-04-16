<?php
session_start();

// Include database connection
require_once 'db.php';  // Ensure this path is correct

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? "info"; // Default to info
    echo "<script>alert('$message');</script>";
    unset($_SESSION['message']); // Remove message after displaying
    unset($_SESSION['message_type']);
}

// Query to fetch all books from the database
$sql = "SELECT * FROM books ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List - Library</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .book-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            width: 220px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            flex-shrink: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .book-item h3 {
            font-size: 1.4rem;
            margin-top: -10px;
            margin-bottom: -15px;
            color: black;
        }

        .book-item p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 15px;
        }

        .book-item .description {
            font-size: 0.95rem;
            color: black;
            margin-bottom: 20px;
            text-align: justify;
        }

        .book-item img {
            width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        .book-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .book-actions .btn {
            padding: 12px 25px;
            text-align: center;
            font-size: 1.1rem;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            background-color: #28a745;
            transition: background-color 0.3s;
        }

        .book-actions .btn:hover {
            background-color: #218838;
        }

        .book-actions .btn.download {
            background-color: #007bff;
        }

        .book-actions .btn.download:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .book-item {
                width: 90%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            ?>
            <div class="book-item">
                <img src="<?= htmlspecialchars($book['book_image_path']) ?>" alt="Book Cover">
                <h3><?= htmlspecialchars($book['book_title']) ?></h3>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                
                <!-- Description Section -->
                <p class="description"><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>

                <div class="book-actions">
                    <!-- Read and Download buttons -->
                    <a href="<?= htmlspecialchars($book['book_file_path']) ?>" target="_blank" class="btn">Read</a>
                    <a href="<?= htmlspecialchars($book['book_file_path']) ?>" download class="btn download">Download</a>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='no-books'><p>No books found. Please check back later.</p></div>";
    }
    $conn->close();
    ?>
</div>

</body>
</html>

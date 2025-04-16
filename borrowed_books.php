<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the logged-in user's name
$query_user = "SELECT username FROM patrons WHERE id = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$username = $user['username'] ?? 'User';

// Check if a success message exists in the session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear message after it's displayed

// Fetch borrowed books from the database
$query = "SELECT books.book_id, books.book_title, books.author, books.book_file_path, borrowed_books.borrowed_date 
          FROM borrowed_books 
          JOIN books ON borrowed_books.book_id = books.book_id 
          WHERE borrowed_books.patron_id = ? 
          AND borrowed_books.returned IS NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Borrowed Books</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* User profile section */
        .user-profile {
            position: absolute;
            top: 10px;
            right: 20px;
            display: flex;
            align-items: center;
            background: #fff;
            padding: 8px 12px;
            border-radius: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .user-profile i {
            font-size: 20px;
            color: #007bff;
            margin-right: 8px;
        }

        .user-profile span {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-top: 50px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .container h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            width: 45%;
            box-sizing: border-box;
        }

        .container p {
            font-size: 16px;
            line-height: 1.6;
        }

        /* Button styles */
        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            color: #ff5733;
            text-decoration: none;
            font-size: 18px;
        }

        a:hover {
            text-decoration: underline;
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

        .back-link {
            display: block;
            text-align: center;
            font-size: 18px;
            color: #007bff;
            margin-top: 20px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

    </style>

    <script>
        // Function to display the success message and hide it after 3 seconds
        window.onload = function() {
            <?php if ($message): ?>
                var messageDiv = document.createElement("div");
                messageDiv.classList.add("success-message");
                messageDiv.textContent = "<?= htmlspecialchars($message) ?>";
                document.body.insertBefore(messageDiv, document.querySelector('.container'));
                messageDiv.style.display = "block"; // Show the message

                // Hide the message after 3 seconds
                setTimeout(function() {
                    messageDiv.style.display = "none";
                }, 3000);
            <?php endif; ?>
        };
    </script>

</head>
<body>

<!-- User Profile Section -->
<div class="user-profile">
<i class="fa-solid fa-user-circle"></i>

    <span><?= htmlspecialchars($username) ?></span>
</div>

<h2>Your Borrowed Books</h2>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($book = $result->fetch_assoc()): ?>
            <div>
                <h3><?= htmlspecialchars($book['book_title']) ?></h3>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p><strong>Borrowed On:</strong> <?= $book['borrowed_date'] ?></p>
                <div class="book-actions">
                    <!-- Read and Download buttons -->
                    <a href="<?= htmlspecialchars($book['book_file_path']) ?>" target="_blank" class="btn">Read</a>
                    <a href="download.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn download">Download</a>

                </div>

                <!-- Return Book -->
                <form method="post" action="return.php">
                    <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                    <button type="submit">Return</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No borrowed books.</p>
    <?php endif; ?>

    <a href="book_list.php" class="back-link">Back to Book List</a>
</div>

</body>
</html>

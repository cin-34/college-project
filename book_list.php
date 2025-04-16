<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Check if a book was borrowed successfully
$successMessage = "";
if (isset($_GET['borrowed']) && $_GET['borrowed'] == "success") {
    $successMessage = "Book borrowed successfully!";
}

// Fetch books and check if they are already borrowed by the logged-in user
$sql = "SELECT books.*, 
               (SELECT COUNT(*) FROM borrowed_books 
                WHERE borrowed_books.book_id = books.book_id 
                AND borrowed_books.patron_id = ? 
                AND (borrowed_books.returned IS NULL OR borrowed_books.returned = '0000-00-00 00:00:00')) AS already_borrowed
        FROM books 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .top-bar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1.1rem;
            color: white;
            border-radius: 5px;
            margin-left: 10px;
        }

        .btn-view {
            background-color: #007bff;
        }

        .btn-view:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: stretch;
}


        .book-item {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 20px;
    width: 220px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    min-height: 450px; 
    
}


        .book-item h3 {
            font-size: 1.4rem;
            margin-bottom: 5px;
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
            flex-grow: 1; /* Allows the description to expand but keeps button alignment */
            min-height: 100px; /* Adjust as needed for uniform height */
    overflow: hidden; /* Prevent content overflow */
    text-align: justify;

        }
       

        .book-item img {
            width: 100%;
    height: 330px; /* Adjust as needed */
    object-fit: cover;
        }

      
.book-actions {
    margin-top: auto; /* Push button to the bottom */
    display: flex;
    justify-content: center; /* Center-align button horizontally */
    align-items: center;
    height: 80px; /* Ensure uniform height */

}



        .book-actions .btn {
            padding: 12px 25px;
            width:auto;
            text-align: center;
            font-size: 1.1rem;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            background-color: #28a745;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            margin-top: auto; /* Pushes the button to the bottom */
    display: flex;
    justify-content: center;
        }

        .book-actions .btn:hover {
            background-color: #218838;
        }

        .disabled {
            background-color: gray !important;
            cursor: not-allowed !important;
        }

        /* Success Popup */
        .popup {
            display: <?= !empty($successMessage) ? 'block' : 'none' ?>;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 1000;
        }

        .popup button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .popup button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="borrowed_books.php" class="btn-view">View Borrowed Books</a>
    <a href="../index.html" class="btn-back">Back</a>
</div>

<?php if (!empty($successMessage)): ?>
    <div class="popup">
        <p><?= $successMessage ?></p>
        <button onclick="document.querySelector('.popup').style.display='none';">OK</button>
    </div>
<?php endif; ?>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            ?>
            <div class="book-item">
                <img src="<?= htmlspecialchars($book['book_image_path']) ?>" alt="Book Cover">
                <h3><?= htmlspecialchars($book['book_title']) ?></h3>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p class="description"><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>
                <div class="book-actions">
                    <form method="post" action="borrow.php">
                        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                        <button type="submit" class="btn <?= ($book['already_borrowed'] > 0) ? 'disabled' : '' ?>" <?= ($book['already_borrowed'] > 0) ? 'disabled' : '' ?>>Borrow</button>
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='no-books'><p>No books found. Please check back later.</p></div>";
    }
    $stmt->close();
    $conn->close();
    ?>
</div>

</body>
</html>

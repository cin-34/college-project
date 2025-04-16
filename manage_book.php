<?php
session_start();

// Include database connection
require_once 'db.php';

// Handle book deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: manage_book.php");
        exit();
    }
}

// Fetch all books from the database
$sql = "SELECT * FROM books ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Books</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        .btn {
            padding: 8px 12px;
            font-size: 0.9rem;
            text-decoration: none;
            border-radius: 5px;
            color: #fff;
            margin-right: 5px;
        }

        .btn.edit {
            background-color: #ffc107;
        }

        .btn.edit:hover {
            background-color: #e0a800;
        }

        .btn.delete {
            background-color: #dc3545;
        }

        .btn.delete:hover {
            background-color: #c82333;
        }

        /* Back button styles */
        .back-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .back-btn i {
            margin-right: 8px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="admin_dashboard.php" class="back-btn">
        <i class="fa fa-arrow-left"></i> Back
    </a>

    <div class="container">
        <h1>Admin - Manage Books</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($book = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($book['book_id']) ?></td>
                            <td><?= htmlspecialchars($book['book_title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars(substr($book['description'], 0, 50)) ?>...</td>
                            <td>
                                <a href="edit_book.php?book_id=<?= htmlspecialchars($book['book_id']) ?>" class="btn edit">Edit</a>
                                <a href="manage_book.php?delete_id=<?= htmlspecialchars($book['book_id']) ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No books found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

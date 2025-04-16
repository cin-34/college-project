<?php
session_start();

// Include database connection
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = intval($_POST['book_id']);
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];

    // Handle image upload if a new image is provided
    $image_path = null;
    if (!empty($_FILES['book_image']['name'])) {
        $image_name = $_FILES['book_image']['name'];
        $image_tmp = $_FILES['book_image']['tmp_name'];
        $upload_dir = 'uploads/';
        $image_path = $upload_dir . basename($image_name);

        // Move uploaded file to the desired location
        move_uploaded_file($image_tmp, $image_path);
    }

    // Update book details in the database
    $sql = "UPDATE books SET book_title = ?, author = ?, description = ?";
    if ($image_path) {
        $sql .= ", book_image_path = ?";
    }
    $sql .= " WHERE book_id = ?";

    $stmt = $conn->prepare($sql);

    if ($image_path) {
        $stmt->bind_param('ssssi', $title, $author, $description, $image_path, $book_id);
    } else {
        $stmt->bind_param('sssi', $title, $author, $description, $book_id);
    }

    if ($stmt->execute()) {
        // Display the modal
        echo "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }

                .modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .modal-content {
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    text-align: center;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    width: 90%;
                    max-width: 400px;
                }

                .modal-content h2 {
                    margin: 0 0 10px;
                    color: #28a745;
                }

                .modal-content p {
                    margin-bottom: 20px;
                    color: #555;
                }

                .close-btn {
                    background-color: #007bff;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                }

                .close-btn:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class='modal'>
                <div class='modal-content'>
                    <h2>Book Updated Successfully</h2>
                    <p>'$title' has been updated.</p>
                    <button class='close-btn' onclick='redirectToDashboard()'>OK</button>
                </div>
            </div>
            <script>
                function redirectToDashboard() {
                    window.location.href = 'admin_dashboard.php';
                }
            </script>
        </body>
        </html>";
    } else {
        echo "<script>
            alert('Error updating book: " . addslashes($stmt->error) . "');
            window.location.href = 'php/admin_dashboard.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

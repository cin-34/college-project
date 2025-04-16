<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login1.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];

    $target_dir = "project/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $stmt = $conn->prepare("INSERT INTO book1 (title, author, description, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $author, $description, $target_file);

    if ($stmt->execute()) {
        echo "Book uploaded successfully.";
    } else {
        echo "Error uploading book: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required><br>

    <label>Author:</label>
    <input type="text" name="author" required><br>

    <label>Description:</label>
    <textarea name="description" required></textarea><br>

    <label>Upload File:</label>
    <input type="file" name="file" required><br>

    <button type="submit">Upload</button>
</form>

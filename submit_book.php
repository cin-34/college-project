<?php
// Start the session
session_start();

// Include database connection
require_once 'db.php';  // Make sure the path to db.php is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $published_year = $_POST['published_year'];
    $description = $_POST['description'];

    // Handle the uploaded image file
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $image_path = 'uploads/' . $_FILES['book_image']['name'];
        move_uploaded_file($_FILES['book_image']['tmp_name'], $image_path);
    }

    // Get the URL for the book PDF
    $book_file_url = $_POST['book_file_url'];  // User provides the URL to the PDF

    // Insert the data into the database
    $sql = "INSERT INTO books (book_title, author, published_year, description, book_image_path, book_file_path) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssisss", $book_title, $author, $published_year, $description, $image_path, $book_file_url);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to book list page after successful insertion
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

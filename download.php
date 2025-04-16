<?php
session_start();
require_once 'db.php'; // Ensure database connection

// 🔹 Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("❌ User not logged in.");
}

// 🔹 Check if book_id is provided
if (!isset($_GET['book_id'])) {
    die("❌ Invalid request.");
}

$book_id = intval($_GET['book_id']); // Ensure it's an integer

// 🔹 Fetch the book file path and name from the database
$query = "SELECT book_file_path, book_title FROM books WHERE book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("❌ Book not found in the database.");
}

$file_path = $book['book_file_path']; // File path stored in the database
$book_name = $book['book_title']; // Book name stored in the database

// 🔹 If the file path is an external URL, redirect to it
if (filter_var($file_path, FILTER_VALIDATE_URL)) {
    header("Location: $file_path");
    exit;
}

// 🔹 Construct the correct file path (Assuming files are inside php/uploads/)
$full_path = __DIR__ . "/uploads/" . basename($file_path);

// 🔍 Debugging: Print the exact path being checked
if (!file_exists($full_path)) {
    die("❌ File not found on the server. <br> Checked path: <strong>$full_path</strong>");
}

// 🔹 Detect the correct MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $full_path);
finfo_close($finfo);

// 🔹 Set a proper download filename (use the book name from DB or fallback to the original file name)
$download_filename = !empty($book_title) ? $book_title . ".pdf" : basename($full_path);

// 🔹 Force file download instead of opening in the browser
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream"); // Force download for any file type
header("Content-Disposition: attachment; filename=\"" . $download_filename . "\"");
header("Content-Length: " . filesize($full_path));
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

// 🔹 Read the file and send it to the user
readfile($full_path);
exit;
?>

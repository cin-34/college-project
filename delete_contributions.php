<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Get file path before deleting the record
    $query = $conn->prepare("SELECT file_path FROM contributions WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $file_path = $row['file_path'];

        // Delete file from server if it exists
        if ($file_path && file_exists($file_path)) {
            unlink($file_path); // Delete the file
        }

        // Delete the record from the database
        $deleteQuery = $conn->prepare("DELETE FROM contributions WHERE id = ?");
        $deleteQuery->bind_param("i", $id);

        if ($deleteQuery->execute()) {
            echo "<script>alert('Contribution Deleted Successfully!'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error Deleting Contribution.'); window.location.href='admin_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Contribution Not Found!'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>

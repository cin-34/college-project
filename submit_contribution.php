<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $details = $_POST['details'];
    $file_path = NULL;

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        }
    }

    $sql = "INSERT INTO contributions (name, email, type, details, file_path) VALUES ('$name', '$email', '$type', '$details', '$file_path')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Contribution Submitted Successfully!'); window.location.href='../indexshare.html';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

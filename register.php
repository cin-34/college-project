<?php
// Include the database connection
include 'db.php';

if (isset($_POST['register'])) {
    // Collect and sanitize form data
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['Address']);
    $username = trim($_POST['username']);
    $password = $_POST['password']; // Will hash this later

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Validate password length
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists (Use Prepared Statements)
    $stmt = $conn->prepare("SELECT * FROM patrons WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or Email already exists!";
    } else {
        // Insert user details securely
        $stmt = $conn->prepare("INSERT INTO patrons (name, email, phone, Address, username, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $address, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! You can now <a href='../login.html'>login</a>";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

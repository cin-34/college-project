<?php
session_start();
include 'db.php'; // Database connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Admin credentials
    $adminUsername = 'admin';
    $adminPassword = 'admin@123';

    // Check if admin
    if ($username === $adminUsername && $password === $adminPassword) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true; // Set session for admin
        $_SESSION['username'] = $username;
        header('Location: admin_dashboard.php'); // Redirect to admin dashboard
        exit;
    }

    // Check user credentials in the database
    $stmt = $conn->prepare("SELECT * FROM patrons WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // User login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Check if there's a redirect URL (e.g., for login to read or download)
        if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
            $redirect_url = $_GET['redirect'];
        } else {
            $redirect_url = 'book_list.php';  // Default redirect if not set
        }

        header("Location: $redirect_url");  // Redirect user to the page they tried to access
        exit;
    }

    // If login fails
    echo "<div style='
            color: #fff; 
            background-color: #ff4d4d; 
            padding: 10px; 
            border-radius: 5px; 
            text-align: center; 
            font-weight: bold;
            width: 50%;
            margin: 20px auto;
        '>
            Invalid username or password! <a href='../login.html' style='color: yellow; text-decoration: underline;'>Try again</a>.
        </div>";
    $stmt->close();
}

$conn->close();
?>

<?php
session_start();

// Establish the database connection
require_once 'db.php'; 

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the username is passed in the URL
if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo "<script>
            alert('Username is missing! Enter username correctly.');
            window.location.href = '../forget_password.html';
          </script>";
    exit();
}

// Sanitize username
$username = htmlspecialchars($_GET['username']);

// Check if the username exists in the database
$stmt = $conn->prepare("SELECT * FROM patrons WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// If username does not exist
if ($result->num_rows === 0) {
    echo "<script>
            alert('Username not found! Please enter your username correctly.');
            window.location.href = 'http://localhost/project/forget_password.html';
          </script>";
    exit();
}

// Handle the password reset form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation (min 8 characters, at least 1 number, 1 uppercase, and 1 special char)
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[\W]/", $password)) {
        echo "<script>alert('Password must be at least 8 characters long and include an uppercase letter, a number, and a special character.');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE patrons SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashed_password, $username);

        if ($stmt->execute()) {
            echo "<script>alert('Password reset successful! Please login.'); window.location='../login.html';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again later.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
       body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Form Container */
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Heading */
        .form-container h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }

        /* Input Fields */
        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-box label {
            font-size: 14px;
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        .input-box input {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }

        .input-box input:focus {
            border-color: #6e8efb;
            box-shadow: 0px 0px 8px rgba(110, 142, 251, 0.5);
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: #6e8efb;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5a7ded;
        }

        /* Responsive Design */
        @media (max-width: 400px) {
            .form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    
    
    <div class="form-container">
        <h2>Reset Your Password</h2>
        <form action="" method="POST">
            <div class="input-box">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="input-box">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>

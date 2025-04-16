<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patron</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="box">
        <div class="form">
            <h2>Register</h2>
            
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "bookstore");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Collect and validate form data
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone']);
                $address = trim($_POST['address']);
                
                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<p style='color:red;'>Invalid email format</p>";
                } 
                // Check if phone contains only numbers
                elseif (!ctype_digit($phone)) {
                    echo "<p style='color:red;'>Invalid phone number. Only numbers are allowed.</p>";
                } 
                else {
                    // Prepared statement to prevent SQL injection
                    $stmt = $conn->prepare("INSERT INTO patrons (name, email, phone, address) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $name, $email, $phone, $address);

                    if ($stmt->execute()) {
                        echo "<p style='color:green;'>New patron added successfully.</p>";
                    } else {
                        // If email is duplicate, inform the user
                        if ($conn->errno == 1062) {
                            echo "<p style='color:red;'>This email is already registered.</p>";
                        } else {
                            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
                        }
                    }

                    // Close the statement
                    $stmt->close();
                }
            }

            // Close the database connection
            $conn->close();
            ?>

            <form action="add_patron.php" method="POST">
                <div class="inputBox">
                    <input type="text" name="name" id="name" required>
                    <span>Name</span>
                    <i></i>
                </div>
                
                <div class="inputBox">
                    <input type="email" name="email" id="email" required>
                    <span>Email</span>
                    <i></i>
                </div>
                
                <div class="inputBox">
                    <input type="text" name="phone" id="phone" required>
                    <span>Phone</span>
                    <i></i>
                </div>
                
                <div class="inputBox">
                    <input type="text" name="address" id="address">
                    <span>Address</span>
                    <i></i>
                </div>

                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</body>
</html>

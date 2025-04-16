<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        /* Dashboard Header */
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .dashboard-header i {
            font-size: 32px;
            color: #ffcc00;
        }

        .dashboard-header h1 {
            font-size: 28px;
            color: #fff;
        }

        /* Main Container */
        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
        }

        /* Admin Options List */
        .admin-options {
            list-style: none;
            padding: 0;
        }

        .admin-options li {
            margin: 15px 0;
        }

        .admin-options a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            background: linear-gradient(90deg, #ff8c00, #e52e71);
            padding: 12px 20px;
            border-radius: 8px;
            display: block;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 3px 10px rgba(255, 140, 0, 0.6);
        }

        .admin-options a:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 140, 0, 0.8);
        }

        .admin-options a i {
            margin-right: 10px;
        }

    </style>
</head>
<body>

    <!-- Admin Dashboard Header -->
    <div class="dashboard-header">
        <i class="fas fa-user-shield"></i>
        <h1>Admin Dashboard</h1>
    </div>

    <!-- Admin Options -->
    <div class="container">
        <ul class="admin-options">
            <li><a href="../add_book.html"><i class="fas fa-plus"></i> Upload Book</a></li>
            <li><a href="manage_book.php"><i class="fas fa-edit"></i> Book Management</a></li>
            <li><a href="editphotos.php"><i class="fas fa-images"></i> Edit gallery</a></li>
            <li><a href="main.php"><i class="fas fa-cogs"></i> Upload Photos</a></li>
            <li><a href="admin_gallery.php"><i class="fas fa-cogs"></i>Update year ranges</a></li>
            <li><a href="admindetails.php"><i class="fas fa-user"></i>Manage Borrowed details</a></li>
            <li><a href="admin_contributions.php"><i class="fas fa-user"></i>Contribution Details</a></li>
        </ul>
    </div>

</body>
</html>

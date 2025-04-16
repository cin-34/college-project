<?php
include 'db.php'; // Database connection

$upload_dir = "uploads/"; // Image storage directory

// Handle Update Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if (!empty($_POST['id']) && !empty($_POST['title'])) {
        $year_id = $_POST['id'];
        $year_range = $_POST['title'];
        $new_image = $_FILES['cover_image']['name'];

        // Fetch old image path
        $get_old_image = "SELECT cover_image FROM year_Ranges WHERE id = ?";
        $stmt = $conn->prepare($get_old_image);
        $stmt->bind_param("i", $year_id);
        $stmt->execute();
        $stmt->bind_result($old_image);
        $stmt->fetch();
        $stmt->close();

        // Check if a new image is uploaded
        if (!empty($new_image)) {
            $filename = basename($new_image);
            $target_file = $upload_dir . $filename;

            // Move new image to the uploads folder
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                // Delete old image if exists
                if (!empty($old_image) && file_exists($upload_dir . $old_image)) {
                    unlink($upload_dir . $old_image);
                }

                // Update with new image
                $update_query = "UPDATE year_Ranges SET title = ?, cover_image = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssi", $year_range, $filename, $year_id);
            } else {
                echo "<script>alert('Image upload failed!');</script>";
                exit;
            }
        } else {
            // Update without changing the image
            $update_query = "UPDATE year_Ranges SET title = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $year_range, $year_id);
        }

        // Execute update query
        if ($stmt->execute()) {
            echo "<script>alert('Year range updated successfully!'); window.location='admin_gallery.php';</script>";
        } else {
            echo "<script>alert('Error updating year range!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Year ID or year range is missing!');</script>";
    }
}

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!empty($_POST['id'])) {
        $year_id = $_POST['id'];

        // Get current image path
        $get_image_query = "SELECT cover_image FROM year_Ranges WHERE id = ?";
        $stmt = $conn->prepare($get_image_query);
        $stmt->bind_param("i", $year_id);
        $stmt->execute();
        $stmt->bind_result($image_path);
        $stmt->fetch();
        $stmt->close();

        // Delete image file if exists
        if (!empty($image_path) && file_exists($upload_dir . $image_path)) {
            unlink($upload_dir . $image_path);
        }

        // Delete entry from database
        $delete_query = "DELETE FROM year_Ranges WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $year_id);

        if ($stmt->execute()) {
            echo "<script>alert('Year range deleted successfully!'); window.location='admin_gallery.php';</script>";
        } else {
            echo "<script>alert('Error deleting year range!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Year ID is missing!');</script>";
    }
}

// Fetch Gallery Data
$query = $query = "SELECT * FROM year_Ranges ORDER BY id ASC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color:#004d40;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        /* Image Styling */
        img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Form Inputs */
        input[type="text"], input[type="file"] {
            width: 90%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Buttons */
        .btn {
            padding: 8px 12px;
            font-size: 14px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .edit {
            background-color: #28a745;
        }

        .edit:hover {
            background-color: #218838;
        }

        .delete {
            background-color: #dc3545;
        }

        .delete:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            input[type="text"], input[type="file"] {
                width: 100%;
            }
        }
         /* Back Button Styling */
        .back-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        .back-btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
<a href="admin_dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <h2>Manage Year Ranges</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Year Range</th>
            <th>Cover Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form method="POST" action="" enctype="multipart/form-data">
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
                </td>
                <td>
                    <?php if (!empty($row['cover_image'])) { ?>
                        <img src="uploads/<?= htmlspecialchars($row['cover_image']) ?>" alt="Cover Image">
                    <?php } ?>
                    <input type="file" name="cover_image">
                </td>
                <td>
                    <button type="submit" name="update" class="btn edit">Update</button>
                    <button type="submit" name="delete" class="btn delete" onclick="return confirm('Are you sure?');">Delete</button>
                </td>
            </form>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>

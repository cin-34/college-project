<?php
include 'db.php';
$result = $conn->query("SELECT * FROM contributions ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }

        /* Back Button */
        .back-icon {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 22px;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }
        .back-icon:hover {
            color: gray;
        }

        /* Table Styling */
        table {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007BFF;
            color: white;
        }

        td a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }

        /* Delete Button */
        button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #a71d2a;
        }
    </style>
</head>
<body>

    <!-- Back Icon -->
    <a href="admin_dashboard.php" class="back-icon">← Back</a>

    <h2>Admin Dashboard - User Contributions</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Details</th>
            <th>File</th>
            <th>Submitted On</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo $row['details']; ?></td>
            <td>
                <?php if ($row['file_path']) { ?>
                    <a href="<?php echo $row['file_path']; ?>" download>Download</a>
                <?php } else { echo "No File"; } ?>
            </td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <form action="delete_contributions.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>

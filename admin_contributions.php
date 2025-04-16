<?php
include 'db.php'; // Ensure correct path to your database connection

$sql = "SELECT id, name, email, type, details, file_path  FROM contributions ";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Contributions</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007BFF; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .file-link { color: blue; text-decoration: none; }
        .file-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h2>Admin - User Contributions</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Details</th>
            <th>File</th>
            
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['type']}</td>
                    <td>{$row['details']}</td>
                    <td>";
                if (!empty($row['file_path'])) {
                    echo "<a href='{$row['file_path']}' class='file-link' target='_blank'>View File</a>";
                } else {
                    echo "No File";
                }
                echo "</td>
                    
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No contributions found.</td></tr>";
        }
        ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>

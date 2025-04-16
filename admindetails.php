<?php
include 'db.php';



// Fetch borrowed books along with book titles, patron names, and returned date/time
$query = "SELECT borrowed_books.*, books.book_title, patrons.name 
          FROM borrowed_books 
          JOIN books ON borrowed_books.book_id = books.book_id 
          JOIN patrons ON borrowed_books.patron_id = patrons.id 
          ORDER BY borrowed_books.borrowed_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Borrowed Books - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Back button */
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.3);
        }

        .back-button i {
            font-size: 18px;
        }

        .back-button:hover {
            background: #0056b3;
        }

        /* Heading */
        h2 {
            text-align: center;
            color: #007bff;
            margin-top: 60px;
            font-size: 28px;
        }

        /* Table Container */
        .table-container {
            width: 90%;
            max-width: 1100px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            overflow-x: auto;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: 0.3s ease;
        }

        /* Status Labels */
        .returned {
            color: #28a745;
            font-weight: bold;
        }

        .not-returned {
            color: #dc3545;
            font-weight: bold;
        }

        /* No books message */
        .no-books {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            body {
                padding: 15px;
            }

            .table-container {
                padding: 10px;
                overflow-x: auto;
            }

            th, td {
                padding: 10px;
                font-size: 14px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <!-- Back to Admin Dashboard Button -->
    <a href="admin_dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Admin Dashboard
    </a>

    <!-- Page Title -->
    <h2>Borrowed Books (Admin View)</h2>

    <!-- Borrowed Books Table -->
    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Patron Name</th>
                        <th>Borrowed Date</th>
                        <th>Returned Status</th>
                        <th>Returned Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['book_title']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td> <?= date("d-m-Y h:i:s A", strtotime($row['borrowed_date'])) ?></td>
                            <td>
                                <?= $row['returned'] ? 
                                    "<span class='returned'>Returned</span>" : 
                                    "<span class='not-returned'>Not Returned</span>" 
                                ?>
                            </td>
                            <td>
                            <?php 
            if (!empty($row['returned'])) {
                echo date("d-m-Y h:i:s A", strtotime($row['returned'])); 
            } else {
                echo "<span class='not-returned'>Not Returned</span>"; 
            }
        ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-books">No borrowed books found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

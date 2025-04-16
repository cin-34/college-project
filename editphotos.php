<?php
include 'db.php'; // Include the database connection

// Fetch Events for Display
$events = $conn->query("SELECT * FROM Events");
echo "<h1 class='page-title'>Event Gallery Edit Page</h1>";

// Back Icon/Link
echo "<a href='admin_dashboard.php' class='back-link'>← Back to Admin Dashboard</a><br><br>";

echo "<div class='event-list'>";

while ($event = $events->fetch_assoc()) {
    echo "<div class='event-container'>";
    echo "<h3>" . htmlspecialchars($event['name']) . "</h3>";
    echo "<a href='edit_event_photos.php?event_id=" . htmlspecialchars($event['id']) . "' class='edit-link'>Edit</a>";
    echo " | ";
    echo "<a href='delete_event.php?event_id=" . htmlspecialchars($event['id']) . "' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this event?\");'>Delete</a>";
    echo "</div>";
}

echo "</div>";
?>

<style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        .back-link {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 18px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .event-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 100px 20px 20px;
            width: 90%;
            max-width: 1200px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #ffeb3b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: -50px 0px;
            padding-top: 80px;
        }

        .event-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .event-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .event-container h3 {
            margin: 0 0 12px;
            font-size: 22px;
            color: #fff;
        }

        .edit-link {
            font-size: 16px;
            color: #ffeb3b;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .edit-link:hover {
            color: #ffc107;
        }

        .delete-link {
            font-size: 16px;
            color: #ff4d4d;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .delete-link:hover {
            color: #ff1a1a;
        }
</style>

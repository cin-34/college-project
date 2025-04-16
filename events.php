<?php
include 'db.php';
$year_id = $_GET['year_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- For the arrow icon -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Full-page styling */
        body {
            background-color: #f4f4f4;
        }

        /* Half-page background */
        .background-container {
            position: relative;
            height: 50vh;
            background: url('../Images/gallery.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-direction: column;
            text-align: center;
        }

        /* Inner box with animation and background image */
        .inner-box {
            width: 50%;
            height: 50%;
            background: url('../Images/gallery.jpg') no-repeat center center/cover; /* Add background image here */
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 10px;
            animation: moveUpDown 3s infinite ease-in-out;
            color: white; /* Make text white so it contrasts with the background */
        }

        @keyframes moveUpDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Links inside the inner box */
        .inner-box a {
            color: white;
            text-decoration: none;
            margin: 10px;
            font-size: 18px;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }
        .inner-box a:nth-child(2) {
            position: relative;
            top: -40px;  /* Move the link up */
            right: -75px;  /* Move the link to the right */
        }

        .inner-box a:hover {
            color: #ffcc00;
        }

        .inner-box i {
            margin-left: 5px;
        }

        /* Gallery title */
        .gallery-title {
            font-size: 24px;
            color: white;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Events container for the gallery layout using CSS Grid */
        .events-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns in a row */
            gap: 20px; /* Spacing between the items */
            padding: 20px;
        }

        /* Event box */
        .event {
            height: 350px; /* Increased height */
            text-align: center;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .event:hover {
            transform: translateY(-10px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.4);
        }

        /* Event Image - Ensure image fits well without cropping */
        .event img {
            width: 100%;
            height: 200px;  /* Set the height to adjust the image size */
            object-fit: contain; /* Ensures the image fits inside the box without cropping */
            border-radius: 8px;
            cursor: pointer;
        }

        /* Event Details */
        .event-details {
            display: flex;
            flex-direction: column;
            align-items: center;  /* Center the content horizontally */
            justify-content: center;  /* Center the content vertically */
            width: 100%;
            padding: 15px 10px; /* Increased padding for better spacing */
            text-align: center; /* Center the text inside the event details box */
        }

        /* Change the font and increase the size for event details */
        .event-details p {
            font-size: 16px;  /* Increased font size */
            font-family: 'Georgia', serif; /* Use a different font */
            color: #444;  /* A slightly darker color for better contrast */
            margin: 8px 0; /* Adjusted margin to reduce the gap */
            font-weight:bold;
        }
        .event-details p:first-child { /* For Date */
            margin-bottom: 1px;
            margin-top:-15px;
        }

        .event-details p:nth-child(2) { /* For Place */
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .event-details p:last-child { /* For Occasion */
            margin-top: -3px;
        }

        .event h3 {
            font-size: 20px;
            font-family: 'Georgia', serif;
            margin-top: 10px;
            color: #333;
        }

        /* Hover Effect on Title & Details */
        .event:hover h3 { color: #ff6600; }
        .event:hover p { color: #444; }

    </style>
</head>
<body>

    <!-- Background with links inside the inner box -->
    <div class="background-container">
        <div class="inner-box">
            <a href="../index.html">Home <i class="fas fa-arrow-right"></i></a>
            <a href="#">Gallery</a>
        </div>
    </div>

    <!-- Events container -->
    <div class="events-container">
        <?php
        $index = 0;
        $events = $conn->query("SELECT * FROM Events WHERE year_range_id = $year_id");

        while ($event = $events->fetch_assoc()) {
            echo "<div class='event'>";
            echo "<h3>{$event['name']}</h3>";
            echo "<a href='event_photos.php?event_id={$event['id']}'><img src='uploads/{$event['event_image']}' alt='Event Image'></a>";
            echo "<div class='event-details'>";
            echo "<p><strong>Date-</strong> {$event['event_date']}</p>";
            echo "<p><strong>Place-</strong> {$event['event_place']}</p>";
            echo "<p><strong>Occasion-</strong> {$event['occasion']}</p>";
            echo "</div>";
            echo "</div>";
            $index++;
        }
        ?>
    </div>

</body>
</html>

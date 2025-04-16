<?php
include 'db.php';
$event_id = $_GET['event_id'];

// Fetch Event Details (including description)
$event = $conn->query("SELECT * FROM Events WHERE id = $event_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Photos</title>
    <style>
        /* General Styling */
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            max-width: 1200px;  /* Adjust width to fit 4 columns */
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        /* Description Styling */
        .description {
            font-size: 18px;
            font-style: italic;
            margin-bottom: 20px;
            text-align: left; /* Align description to the left */
            color: #555;
        }

        /* Photo grid */
        .photo-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start; /* Ensure the first image starts from the left */
            padding: 20px;
            
        }

        /* Styling individual photo blocks */
        .photo {
            text-align: center;
            flex-basis: calc(25% - 20px); /* 4 images per row (25% of the width) */
            margin-bottom: 20px;
        }

        /* Default image styling (same size for all images) */
        .photo img {
            width: 100%;
            height: 250px; /* Keep the height fixed */
            object-fit: contain; /* Ensure the image fits without cropping */
            border-radius: 8px;
            transition: transform 0.3s ease-in-out; /* Smooth scale transition */
        }

        /* Hover effect on all images */
        .photo:hover img {
            transform: scale(1.1); /* Slight zoom effect */
            transform-origin: left center; /* Zoom starts from left */
        }

        /* Background Section */
        .background-section {
            position: relative;
            height: 50vh;
            background-image: url('../Images/gallery.jpg');
            background-size: cover;
            background-position: center;
            overflow: hidden;
            margin-left:-20px;
            margin-right:-30px;
            margin-top:-30px;
        }

        .image-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 50%;
            background-image: url('../Images/gallery.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 18px;
            text-align: center;
            animation: moveUpDown 3s ease-in-out infinite;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .image-box a {
            color: #f39c12;
            font-size: 24px;
            text-decoration: none;
            margin: 0 20px;
            display: flex;
            align-items: center;
        }

        .image-box a i {
            margin-left: 10px;
        }

        @keyframes moveUpDown {
            0%, 100% {
                transform: translate(-50%, -50%);
            }
            50% {
                transform: translate(-50%, -40%);
            }
        }
    </style>
</head>
<body>

<!-- Background Section with Box and Image -->
<div class="background-section">
    <div class="image-box">
        <a href="../index.html">Home <i class="fas fa-arrow-right"></i></a>
        <a href="#">Gallery</a>
    </div>
</div>

<div class="container">
    <h2>Event Photos</h2>

    <!-- Display Event Description Above Images -->
    <?php if ($event['description']) { ?>
        <p class="description"><?php echo nl2br($event['description']); ?></p>
    <?php } ?>

    <!-- Display Event Photos -->
    <div class="photo-gallery">
        <?php
        // Fetch Photos for the Selected Event
        $photos = $conn->query("SELECT * FROM Event_Photos WHERE event_id = $event_id");
        while ($photo = $photos->fetch_assoc()) {
            echo "<div class='photo'>";
            echo "<img src='uploads/{$photo['photo']}'>";

            echo "</div>";
        }
        ?>
    </div>
</div>

</body>
</html>

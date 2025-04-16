<?php
include 'db.php'; // Include the database connection

// Ensure the admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.html'); // Redirect to login page if not logged in
    exit;
}

// Get and validate the event_id from the URL
if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
} else {
    die("Error: Invalid event ID.");
}

// Handle the form submission for updating event details (event name, year range, photos, date, place, occasion, description)
if (isset($_POST['update_event'])) {
    // Update event name, year range, date, place, occasion, and description
    $event_name = $_POST['event_name'];
    $year_range_id = $_POST['year_range_id'];
    $event_date = $_POST['event_date'];
    $event_place = $_POST['event_place'];
    $occasion = $_POST['occasion'];
    $event_description = $_POST['event_description'];

    // Update event in the database
    $conn->query("UPDATE Events SET name = '$event_name', year_range_id = '$year_range_id', event_date = '$event_date', 
                  event_place = '$event_place', occasion = '$occasion', description = '$event_description' WHERE id = $event_id");

    // Handle year range image update
    $year_range_image = $_FILES['year_range_image']['name'];
    if ($year_range_image) {
        // Move the uploaded image to the uploads directory
        $temp_name = $_FILES['year_range_image']['tmp_name'];
        $upload_path = 'uploads/' . basename($year_range_image);
        move_uploaded_file($temp_name, $upload_path);

        // Get the current image and delete it if a new image is uploaded
        $year_range_result = $conn->query("SELECT cover_image FROM Year_Ranges WHERE id = $year_range_id");
        $year_range_data = $year_range_result->fetch_assoc();
        $current_image = $year_range_data['cover_image'];
        if ($current_image && file_exists("uploads/$current_image")) {
            unlink("uploads/$current_image"); // Delete old image
        }

        // Update the year range image in the database
        $conn->query("UPDATE Year_Ranges SET cover_image = '$year_range_image' WHERE id = $year_range_id");
    }

    // Handle event photo update
    if (isset($_FILES['photos']['name']) && is_array($_FILES['photos']['name']) && count($_FILES['photos']['name']) > 0) {
        foreach ($_FILES['photos']['name'] as $key => $photo) {
            $photo_tmp = $_FILES['photos']['tmp_name'][$key];
            $existing_photo = $_POST['existing_photos'][$key];

            // Delete the old photo if a new one is uploaded
            if ($photo) {
                unlink("uploads/$existing_photo"); // Remove old photo from the server
                move_uploaded_file($photo_tmp, "uploads/$photo"); // Upload new photo
                $conn->query("UPDATE Event_Photos SET photo='$photo' WHERE photo='$existing_photo' AND event_id='$event_id'");
            }
        }
    }
}

// Handle photo deletion
if (isset($_POST['delete_photo'])) {
    $photo_to_delete = $_POST['delete_photo'];

    // Delete photo from the server
    if (file_exists("uploads/$photo_to_delete")) {
        unlink("uploads/$photo_to_delete"); // Delete the file
    }

    // Remove photo from the database
    $conn->query("DELETE FROM Event_Photos WHERE photo = '$photo_to_delete' AND event_id = $event_id");

    // Redirect to the same page to refresh the photos
    header("Location: edit_event_photos.php?event_id=$event_id");
    exit;
}

// Fetch event details and associated photos
$event_result = $conn->query("SELECT * FROM Events WHERE id = $event_id");
$event = $event_result->fetch_assoc();

// Fetch all year ranges for the dropdown
$year_ranges_result = $conn->query("SELECT * FROM Year_Ranges");

// Fetch photos for the selected event
$photos = $conn->query("SELECT * FROM Event_Photos WHERE event_id = $event_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event Details and Photos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .photo { display: inline-block; margin: 10px; text-align: center; }
        .photo img { width: 200px; height: auto; cursor: pointer; }
        .photo input[type="file"] { display: block; margin-top: 5px; }
        .back-link { font-size: 20px; text-decoration: none; color: #007BFF; }
        .back-link:hover { text-decoration: underline; }
        /* General Form Styles */
/* General Form Styles */
form {
    background: #f4f4f4; /* Light Gray Background */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: 900px; /* Maintains wider layout */
    margin: 20px auto;
    text-align: left;
    transition: all 0.3s ease-in-out;
}

/* Labels */
label {
    display: block;
    font-weight: 600;
    margin: 10px 0 5px;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Input Fields */
input[type="text"], 
select, 
textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    background: #fff; /* White background for better contrast */
    transition: 0.3s;
    outline: none;
}

/* Input Focus Effect */
input:focus, 
select:focus, 
textarea:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    background: #fff;
}

/* Select Dropdown */
select {
    appearance: none;
    background: url('data:image/svg+xml;utf8,<svg fill="%23007BFF" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5.3 7.7a1 1 0 011.4 0L10 11l3.3-3.3a1 1 0 011.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z"/></svg>') no-repeat right 12px center;
    background-size: 14px;
    cursor: pointer;
    padding-right: 35px;
}

/* Textarea Styling */
textarea {
    height: 140px;
    resize: vertical;
    font-family: Arial, sans-serif;
}

/* File Input Styling */
input[type="file"] {
    display: none; /* Hide default input */
}

/* Custom File Upload Button */
.file-upload {
    display: inline-block;
    background: #007BFF;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: 0.3s ease-in-out;
    text-align: center;
    border: none;
}

.file-upload:hover {
    background: #0056b3;
    box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
}

/* Smaller Submit Button */
button {
    background: #007BFF;
    color: white;
    padding: 10px 16px; /* Reduced size */
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    width: auto; /* No full width */
    transition: 0.3s ease-in-out;
}

button:hover {
    background: #0056b3;
    box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
}

/* Responsive Design */
@media (max-width: 1000px) {
    form {
        width: 95%; /* Adjusts width for smaller screens */
        padding: 25px;
    }
}


    </style>
</head>
<body>

<!-- Back Icon/Link -->
<a href="editphotos.php" class="back-link">← Back to Edit Photos</a><br><br>

<h2>Edit Event Details and Photos</h2>

<!-- Form for updating event name, year range, and photos -->
<form method="POST" enctype="multipart/form-data">
    <!-- Event Name -->
    <label for="event_name">Event Name</label>
    <input type="text" name="event_name" value="<?php echo $event['name']; ?>" required><br><br>

    <!-- Year Range -->
    <label for="year_range_id">Year Range</label>
    <select name="year_range_id" required>
        <?php while ($year_range = $year_ranges_result->fetch_assoc()) { ?>
            <option value="<?php echo $year_range['id']; ?>" <?php if ($year_range['id'] == $event['year_range_id']) echo 'selected'; ?>>
                <?php echo $year_range['title']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <!-- Year Range Image -->
    <label for="year_range_image">Change Year Range Image</label>
    <input type="file" name="year_range_image"><br><br>
    <label class="file-upload" for="year_range_image">Choose File</label>
<input type="file" name="year_range_image" id="year_range_image">

    <!-- Event Date -->
    <label for="event_date">Event Date</label>
    <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>"><br><br>

    <!-- Event Place -->
    <label for="event_place">Event Place</label>
    <input type="text" name="event_place" value="<?php echo $event['event_place']; ?>"><br><br>

    <!-- Occasion -->
    <label for="occasion">Occasion</label>
    <input type="text" name="occasion" value="<?php echo $event['occasion']; ?>"><br><br>

    <!-- Event Description -->
    <label for="event_description">Event Description</label>
    <textarea name="event_description"><?php echo $event['description']; ?></textarea><br><br>
    
    


    <h3>Event Photos</h3>
    <?php
    if ($photos->num_rows > 0) {
        while ($photo = $photos->fetch_assoc()) {
            echo "<div class='photo'>";
            echo "<img src='uploads/{$photo['photo']}' alt='Event Photo'>";
            echo "<input type='file' name='photos[]'>";
            echo "<input type='hidden' name='existing_photos[]' value='{$photo['photo']}'>";
            
            // Add a delete button for each photo
            echo "<button type='submit' name='delete_photo' value='{$photo['photo']}'>Delete</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No photos found for this event.</p>";
    }
    ?>

    <button type="submit" name="update_event">Update Event</button>
</form>

</body>
</html>

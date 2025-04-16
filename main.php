<?php
include 'db.php'; // Use existing database connection

/* Insert Year Range */
if (isset($_POST['add_year_range'])) {
    $title = $_POST['title'];
    $cover_image = $_FILES['cover_image']['name'];
    move_uploaded_file($_FILES['cover_image']['tmp_name'], "uploads/$cover_image");
    $conn->query("INSERT INTO Year_Ranges (title, cover_image) VALUES ('$title', '$cover_image')");
}

/* Insert Event */
if (isset($_POST['add_event'])) {
    $year_range_id = $_POST['year_range_id'];
    $event_name = $_POST['event_name'];
    $event_image = $_FILES['event_image']['name'];
    $event_date = $_POST['event_date'];
    $event_place = $_POST['event_place'];
    $occasion = $_POST['occasion'];

    move_uploaded_file($_FILES['event_image']['tmp_name'], "uploads/$event_image");
    $conn->query("INSERT INTO Events (year_range_id, name, event_image, event_date, event_place, occasion) 
                  VALUES ('$year_range_id', '$event_name', '$event_image', '$event_date', '$event_place', '$occasion')");
}

/* Insert Multiple Event Photos */
if (isset($_POST['add_event_photos'])) {
    $event_id = $_POST['event_id'];
    foreach ($_FILES['photos']['name'] as $key => $photo) {
        $photo_tmp = $_FILES['photos']['tmp_name'][$key];
        move_uploaded_file($photo_tmp, "uploads/$photo");
        $conn->query("INSERT INTO Event_Photos (event_id, photo) VALUES ('$event_id', '$photo')");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gallery Admin</title>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Body Styling */
        /* General Body Styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #1e1e2f; /* Darker background for a modern look */
    margin: 0;
    padding: 0;
    color: #e0f7fa; /* Light cyan text for better contrast */
}

/* Headings */
h2 {
    font-size: 24px;
    color: #4db6ac; /* Teal accent for headings */
    margin-bottom: 20px;
    text-align: center;
}

/* Form Styling */
form {
    background-color: #2c2c3e; /* Dark grey-blue */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    padding: 20px;
    margin-bottom: 30px;
    max-width: 800px;
    margin: 0 auto;
}

.form-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 15px;
}

.form-group label {
    width: 30%;
    font-weight: bold;
    color: #4db6ac; /* Matching heading color */
}

.form-group input, .form-group select, .form-group textarea {
    width: 65%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 4px;
    border: 1px solid #555;
    background-color: #333; /* Darker input background */
    color: #fff;
}

.form-group input[type="file"] {
    padding: 5px;
}

/* Submit Button */
button {
    background-color: #4db6ac;
    color: #1e1e2f;
    border: none;
    cursor: pointer;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: bold;
    transition: 0.3s;
}

button:hover {
    background-color: #388e80; /* Darker teal on hover */
}

/* Back Icon Styling (Now in Top-Right) */
.back-icon {
    font-size: 16px;
    color: #4db6ac;
    text-decoration: none;
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.1);
    padding: 10px 15px;
    border-radius: 50px;
    transition: background 0.3s ease;
}

.back-icon:hover {
    background: rgba(255, 255, 255, 0.3);
    text-decoration: none;
}

/* Responsive Layout */
@media (max-width: 768px) {
    body {
        padding: 15px;
    }

    .form-group {
        flex-direction: column;
    }

    .form-group label {
        width: 100%;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
    }

    .back-icon {
        font-size: 14px;
        top: 15px;
        right: 15px;
        padding: 8px 12px;
    }
}

    </style>
</head>
<body>
    <!-- Back Icon to Navigate to Admin Dashboard -->
    <a href="admin_dashboard.php" class="back-icon">
        <i class="fas fa-arrow-left"></i> Back to Admin Dashboard
    </a>

    <!-- Add Year Range Form -->
    <h2>Add Year Range</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Year Range</label>
            <input type="text" name="title" placeholder="Enter Year Range" required>
        </div>
        <div class="form-group">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" required>
        </div>
        <button type="submit" name="add_year_range">Add Year Range</button>
    </form>

    <!-- Add Event Form -->
    <h2>Add Event</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="year_range_id">Year Range</label>
            <select name="year_range_id" required>
                <option value="">Select Year Range</option>
                <?php
                $res = $conn->query("SELECT * FROM Year_Ranges");
                while ($row = $res->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" name="event_name" placeholder="Enter Event Name" required>
        </div>
        <div class="form-group">
            <label for="event_image">Event Image</label>
            <input type="file" name="event_image" required>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" name="event_date" required>
        </div>
        <div class="form-group">
            <label for="event_place">Event Place</label>
            <input type="text" name="event_place" placeholder="Enter Event Place" required>
        </div>
        <div class="form-group">
            <label for="occasion">Occasion</label>
            <input type="text" name="occasion" placeholder="Enter Occasion" required>
        </div>
        <button type="submit" name="add_event">Add Event</button>
    </form>

    <!-- Add Event Photos Form -->
    <h2>Add Event Photos</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="event_id">Event</label>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php
                $res = $conn->query("SELECT * FROM Events");
                while ($row = $res->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="event_description">Event Description</label>
            <textarea name="event_description" placeholder="Enter event description"></textarea>
        </div>
        <div class="form-group">
            <label for="photos">Photos</label>
            <input type="file" name="photos[]" multiple required>
        </div>
        <button type="submit" name="add_event_photos">Upload Photos</button>
    </form>
</body>
</html>

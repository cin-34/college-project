<?php
session_start();
require_once 'db.php'; // Include database connection

if (isset($_GET['query']) && isset($_GET['search_type']) && isset($_GET['category'])) {
    $query = strtolower(trim($_GET['query']));
    $searchType = trim($_GET['search_type']);
    $category = strtolower(trim($_GET['category']));

    if ($category === 'books') {
        $allowedSearchTypes = ['book_title', 'author', 'description', 'year_range'];
        $table = 'books';
    } elseif ($category === 'events') {
        $allowedSearchTypes = ['name', 'event_date', 'title', 'occasion', 'event_place', 'year_range'];
        $table = 'events';
    } elseif ($category === 'year_ranges') {
        // Special case for Year Ranges
        $yearRanges = $conn->query("SELECT * FROM Year_Ranges");
    } else {
        die("Invalid category: " . htmlspecialchars($category));
    }

    if ($category !== 'year_ranges' && !in_array($searchType, $allowedSearchTypes)) {
        die("Invalid search type: " . htmlspecialchars($searchType));
    }

    if ($category !== 'year_ranges') {
        $checkColumn = $conn->query("SHOW COLUMNS FROM `$table` LIKE 'created_at'");
        $orderByClause = ($checkColumn->num_rows > 0) ? "ORDER BY created_at DESC" : "";

        $sql = "SELECT * FROM $table WHERE LOWER(`$searchType`) LIKE LOWER(?) $orderByClause";
        $stmt = $conn->prepare($sql);
        $searchQuery = "%$query%";
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device=device-width, initial-scale=1.0">
    <title>Search Results - Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .book-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            width: 220px; /* Adjust width for better spacing */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            flex-shrink: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .book-item h3 {
    font-size: 1.4rem; /* Increased font size for title */
    margin-top: -10px; /* Move the title up */
    margin-bottom: -15px; /* Adjust bottom margin to keep spacing between title and author */
    color:black;

}


        .book-item p {
            font-size: 1.1rem; /* Increased font size for better readability */
            color: #555;
            margin-bottom: 15px;
        }

        .book-item .description {
            font-size: 0.95rem;
            color:black;
            margin-bottom: 20px; /* Added margin for spacing */
            text-align: justify;
        }

        .book-item img {
            width: 100%;
            height: auto;
            margin-bottom: 15px; /* Increased margin for spacing */
        }

        .book-actions {
            display: flex;
            justify-content: center;
        }

        .book-actions .btn {
            padding: 12px 25px;
            text-align: center;
            font-size: 1.1rem;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            background-color: #28a745;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }

        .book-actions .btn:hover {
            background-color: #218838;
        }

        .disabled {
            background-color: gray !important;
            cursor: not-allowed !important;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .book-item {
                width: 90%;
                margin: 10px 0;
            }
        }

        .year-range {
            width: 220px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .year-range:hover {
            transform: scale(1.05);
        }

        .year-range img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .year-range h3 {
            font-size: 1.4rem;
            margin-top: 10px;
            color: black;
        }
        .back-icon {
    position: absolute;  /* Allows precise positioning */
    top: -80px; /* Adjust as needed */
    left: 10px; /* Move it to the left, change to right: 10px; for right */
    font-size: 28px; /* Increases text & emoji size */
    font-weight: bold;
    text-decoration: none;
    color: #007bff;
    display: flex;  /* Ensures proper alignment */
    align-items: center;
    gap: 8px; /* Space between icon and text */
}

.back-icon span {
    font-size: 40px; /* Adjust text size separately if needed */
}

.back-icon.move-right {
    left: auto;
    right: 10px; /* Moves it to the right */
}



        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .year-range {
                width: 90%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($category === 'year_ranges') {
        while ($year = $yearRanges->fetch_assoc()) {
            echo "<div class='year-range'>";
            echo "<a href='events.php?year_id={$year['id']}'>";
            echo "<img src='uploads/{$year['cover_image']}' alt='{$year['title']}'>";
            echo "<h3>{$year['title']}</h3>";  // Year range inside the image at the bottom
            echo "</a>";
            echo "</div>";
        }
    } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="book-item">
                <?php if ($category === 'books') { 
                    
                ?>
                    <a href="book_list.php" class="back-icon" title="Back to Book List">
    <span style="font-size: 20px;">🔙</span> VIEW ALL
</a>

                    <img src="<?= htmlspecialchars($row['book_image_path']) ?>" alt="Book Cover">
                    <h3><?= htmlspecialchars($row['book_title'] ?? 'Unknown Title') ?></h3>
                    <p><strong>Author:</strong> <?= htmlspecialchars($row['author'] ?? 'Unknown Author') ?></p>
                    <p><strong>Year Range:</strong> <?= htmlspecialchars($row['year_range'] ?? 'N/A') ?></p>
                    <p class="description"><strong>Description:</strong> <?= htmlspecialchars($row['description'] ?? 'No Description Available') ?></p>
                   <div class="book-actions">
                    <form method="post" action="../index1.html">
                        <input type="hidden" name="book_id" value="<?= $row['book_id'] ?>">
                        <?php 
$already_borrowed = isset($row['already_borrowed']) ? $row['already_borrowed'] : 0; 
?>
<button type="submit" class="btn <?= ($already_borrowed > 0) ? 'disabled' : '' ?>" <?= ($already_borrowed > 0) ? 'disabled' : '' ?>>Borrow</button>

                    </form>
                </div>
                <?php } elseif ($category === 'events') {
                    
                     
                     
                    $eventImage = !empty($row['event_image']) ? 'uploads/' . htmlspecialchars($row['event_image']) : 'images/default_event.jpg';
                ?>
               
               
                
           
    
    
    <!-- Event Image (Not Affected) -->
    <img src="<?= $eventImage ?>" alt="Event Image">
                    <h3><?= htmlspecialchars($row['name'] ?? 'No Name') ?></h3>
                    
                    <p><strong>Date:</strong> <?= htmlspecialchars($row['event_date'] ?? 'N/A') ?></p>
                    <p><strong>Occasion:</strong> <?= htmlspecialchars($row['occasion'] ?? 'N/A') ?></p>
                    <p><strong>Event Place:</strong> <?= htmlspecialchars($row['event_place'] ?? 'N/A') ?></p>
                  
                    
                    
                <?php } ?>
            </div>
            
            <?php
        }
    } else {
        echo "<div class='no-items'><p>No results found. Please try another search.</p></div>";
    }
    $conn->close();
    ?>
</div>

</body>
</html>

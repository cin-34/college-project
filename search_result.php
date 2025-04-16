<?php
// search.php
include 'db.php'; // Ensure database connection

if (isset($_GET['category']) && isset($_GET['query']) && isset($_GET['search_type'])) {
    $category = $_GET['category'];
    $query = strtolower(trim($_GET['query']));
    $searchType = trim($_GET['search_type']);

    // Ensure query and searchType are not empty
    if (empty($query) || empty($searchType)) {
        die("Error: Search query and type cannot be empty.");
    }

    // Convert "gallery" category to use "events" table
    if ($category == "gallery") { 
        $category = "events";
    }

    $sql = ""; // Initialize SQL query variable

    if ($category == "books") {
        // Search in the 'books' table
        switch ($searchType) {
            case 'book_title':
                $sql = "SELECT * FROM books WHERE LOWER(book_title) LIKE LOWER('%$query%') ORDER BY book_title ASC";
                break;
            case 'author':
                $sql = "SELECT * FROM books WHERE LOWER(author) LIKE LOWER('%$query%') ORDER BY author ASC";
                break;
            case 'published_year':
                $sql = "SELECT * FROM books WHERE LOWER(published_year) LIKE LOWER('%$query%') ORDER BY published_year DESC";
                break;
            default:
                die("Error: Invalid search type.");
        }
    } elseif ($category == "events") { 
        // Search in 'events' (includes gallery)
        switch ($searchType) {
            case 'event_date':
                $sql = "SELECT * FROM events WHERE LOWER(event_date) LIKE LOWER('%$query%') ORDER BY event_date DESC";
                break;
            case 'event_place':
                $sql = "SELECT * FROM events WHERE LOWER(event_place) LIKE LOWER('%$query%') ORDER BY event_place ASC";
                break;
            case 'occasion':
                $sql = "SELECT * FROM events WHERE LOWER(occasion) LIKE LOWER('%$query%') ORDER BY occasion ASC";
                break;
            case 'name':
                $sql = "SELECT * FROM events WHERE LOWER(name) LIKE LOWER('%$query%') ORDER BY name ASC";
                break;
            default:
                die("Error: Invalid search type.");
        }
    } elseif ($category == "year_ranges") {
        // Search in the 'year_ranges' table
        if ($searchType == 'title') {
            $sql = "SELECT * FROM year_ranges WHERE LOWER(CAST(`title` AS CHAR)) LIKE LOWER('%$query%') ORDER BY title ASC";
        } else {
            die("Error: Invalid search type.");
        }
    } else {
        die("Error: Invalid category.");
    }

    // Execute the query only if $sql is set
    if (!empty($sql)) {
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("SQL Error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            header("Location: search.php?category=$category&search_type=$searchType&query=$query");
            exit();
        } else {
            echo "No results found.";
        }
    } else {
        echo "Error: No valid search query executed.";
    }
} else {
    echo "Error: Missing search parameters.";
}
?>
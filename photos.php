<?php
include 'db.php';

// Fetch the minimum and maximum years from the database
$query = "SELECT MIN(year) AS min_year, MAX(year) AS max_year FROM photos";
$result = $conn->query($query);
$row = $result->fetch_assoc();

$min_year = $row['min_year'];
$max_year = $row['max_year'];

// Define range size (e.g., 20 years per range)
$range_size = 20;

// Calculate year ranges
$year_ranges = [];
for ($start = $min_year; $start <= $max_year; $start += $range_size) {
    $end = min($start + $range_size - 1, $max_year); // Avoid exceeding max_year
    $year_ranges[] = ['start' => $start, 'end' => $end];
}

// Fetch a sample photo for each range
function getSamplePhoto($conn, $start_year, $end_year) {
    $query = "SELECT filename FROM photos WHERE year BETWEEN $start_year AND $end_year LIMIT 1";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <style>
        .gallery-preview {
            text-align: center;
            margin: 20px;
        }
        img {
            width: 300px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Photo Archive</h1>

    <?php foreach ($year_ranges as $range): 
        $start_year = $range['start'];
        $end_year = $range['end'];
        $samplePhoto = getSamplePhoto($conn, $start_year, $end_year);
    ?>
        <div class="gallery-preview">
            <?php if ($samplePhoto): ?>
                <img src="uploads/<?php echo $samplePhoto['filename']; ?>" alt="Photos from <?php echo $start_year; ?> to <?php echo $end_year; ?>">
            <?php else: ?>
                <p>No photos available for this range.</p>
            <?php endif; ?>
            <p>Years: <?php echo "$start_year - $end_year"; ?></p>
            <button onclick="viewPhotos('<?php echo $start_year; ?>', '<?php echo $end_year; ?>')">View Photos</button>
        </div>
    <?php endforeach; ?>

    <script>
        function viewPhotos(startYear, endYear) {
            window.location.href = `all-photos.php?start_year=${startYear}&end_year=${endYear}`;
        }
    </script>
</body>
</html>

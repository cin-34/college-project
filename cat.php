<?php
include 'db.php';

// Fetch categories for dropdown
$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
</head>
<body>
    <h1>Categories</h1>

    <!-- Display categories and subcategories as dropdown -->
    <ul>
        <?php while ($category = $categories_result->fetch_assoc()) {
            // Fetch subcategories for the current category
            $subcategory_sql = "SELECT * FROM subcategories WHERE category_id=" . $category['id'];
            $subcategory_result = $conn->query($subcategory_sql);
        ?>
        <li>
            <a href="#"><?php echo $category['category_name']; ?></a>
            <ul class="sub-dropdown">
                <?php while ($subcategory = $subcategory_result->fetch_assoc()) { ?>
                    <li><a href="category_page.php?subcategory_id=<?php echo $subcategory['id']; ?>"><?php echo $subcategory['subcategory_name']; ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>
    </ul>
</body>
</html>

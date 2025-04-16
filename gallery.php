<?php
include 'db.php'; // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #333;
        }
        .logo {
            display: flex;
            align-items: center;
            font-family: 'Playfair Display', serif;
        }
        .logo i {
            font-size: 28px;
            color: #f39c12;
            margin-right: 10px;
        }
        .logo h2 {
            font-size: 22px;
            color: white;
        }
        nav ul {
            list-style: none;
            display: flex;
            align-items: center;
            padding: 0;
            padding-left: 550px;
        }
        nav ul li {
            margin: 0 15px;
            position: relative;
        }
        nav ul li:not(:last-child)::after {
            content: '';
            height: 20px;
            width: 1px;
            background-color: #fff;
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }
        nav ul li a:hover {
            color: yellow;
            text-decoration: underline;
        }

        /* Dropdown Styles */
        #categories:hover {
    color: yellow;
    text-decoration: underline;
}
        #categories {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            font-family: Georgia, 'Times New Roman', Times, serif;
            
        }

        .dropdown {
            display: none;
            position: absolute;
            background-color: rgba(0, 0, 0, 0.9);
            padding:5px;
            list-style: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            top: 100%;
            left: 0;
            min-width: 100px;
            z-index: 999;
        }

        .dropdown li {
            margin: 5px 0;
            position: relative;
        }

        .dropdown li a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            display: block;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        .dropdown li a:hover {
            background-color: #f39c12;
            color: #000;
        }

        /* Sub-dropdown Styles */
        .sub-dropdown {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background-color: rgba(0, 0, 0, 0.9);
            padding: 5px;
            list-style: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            min-width: 120px;
        }

        .sub-dropdown li {
            margin: 5px 0;
        }

        .sub-dropdown li a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            display: block;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        .sub-dropdown li a:hover {
            background-color: #f39c12;
            color: #000;
        }

        /* Show Dropdown and Sub-dropdown on hover */
        nav ul li:hover > .dropdown {
            display: block;
        }

        .dropdown li:hover > .sub-dropdown {
            display: block;
        }

        .icons {
            display: flex;
            align-items: center;
        }
        .icons a {
            color: white;
            font-size: 24px;
            text-decoration: none;
        }
        .icons a:not(:first-child) {
            margin-left: 20px;
        }

        /* Background Section */
        .background-section {
            position: relative;
            height: 50vh;
            background-image: url('../Images/gallery.jpg');
            background-size: cover;
            background-position: center;
            overflow: hidden;
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
    

        /* Gallery Grid */
        .content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Responsive */
            gap: 20px;
            padding: 20px;
            justify-items: center;
        }

        /* Image Container */
        .year-range {
            position: relative;
            width: 300px;
            height: 300px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .year-range:hover {
            transform: scale(1.05);
        }

        /* Image Styling */
        .year-range img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Ensures full image is shown without cropping */
        }

        /* Year Range Title Inside Image */
        .year-range h3 {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 18px;
            text-align: center;
            padding: 10px;
            margin: 0;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .year-range:hover h3 {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body>
<header>
        <div class="logo">
            <i class="fas fa-archive"></i>
            <h2>Digital Archives</h2>
        </div>
        <nav>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li>
                    <a href="#" id="categories">Categories</a>
                    <ul class="dropdown">
                        <li>
                            <a href="#">Fiction</a>
                            <ul class="sub-dropdown">
                                <li><a href="php/book_list.php">Fantasy</a></li>
                                <li><a href="#">Mystery</a></li>
                                <li><a href="#">Sci-Fi</a></li>
                                <li><a href="#">Adventure</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Non-Fiction</a>
                            <ul class="sub-dropdown">
                                <li><a href="#">Biography</a></li>
                                <li><a href="#">Self-Help</a></li>
                                <li><a href="#">History</a></li>
                                <li><a href="#">Science</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="#">Patrons</a></li>
                <li><a href="#">Journal</a></li>
                <li><a href="#">Gallery</a></li>
            </ul>
        </nav>
        <div class="icons">
            <a href="login.html"><i class="material-icons">login</i></a>
            <a href="index1.html"><i class="material-icons">person_add</i></a>
        </div>
    </header>

    <!-- Background Section with Box and Image -->
    <div class="background-section">
        <div class="image-box">
            <a href="../index.html">Home <i class="fas fa-arrow-right"></i></a>
            <a href="#">Gallery</a>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
    const categories = document.getElementById("categories");
    const dropdown = document.querySelector(".dropdown");
    
    categories.addEventListener("click", (e) => {
      e.preventDefault();
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });
  
    const dropdownItems = dropdown.querySelectorAll("li > a");
    
    dropdownItems.forEach((item) => {
      const subDropdown = item.nextElementSibling;
      if (subDropdown && subDropdown.classList.contains("sub-dropdown")) {
        item.addEventListener("mouseenter", () => {
          subDropdown.style.display = "block";
        });
        item.addEventListener("mouseleave", () => {
          subDropdown.style.display = "none";
        });
        subDropdown.addEventListener("mouseenter", () => {
          subDropdown.style.display = "block";
        });
        subDropdown.addEventListener("mouseleave", () => {
          subDropdown.style.display = "none";
        });
      }
    });
  });
  </script>


<!-- Main Content Area -->
<div class="content">
    <!-- Gallery Section -->
    <?php
    // Fetch Year Ranges
    $yearRanges = $conn->query("SELECT * FROM Year_Ranges");
    while ($year = $yearRanges->fetch_assoc()) {
        echo "<div class='year-range'>";
        echo "<a href='events.php?year_id={$year['id']}'>";
        echo "<img src='uploads/{$year['cover_image']}' alt='{$year['title']}'>";
        echo "<h3>{$year['title']}</h3>";  // Year range inside the image at the bottom
        echo "</a>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>

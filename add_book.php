<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="book.css">
</head>
<body>
    <div class="container">
        <h2>Add a New Book</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $category = $_POST['category'];
            $year = $_POST['year'];
            $isbn = $_POST['isbn'];

            $conn = new mysqli("localhost", "root", "", "bookstore");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO books (title, author, category, year_published, isbn)
                    VALUES ('$title', '$author', '$category', $year, '$isbn')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
        ?>

        <form action="add_book.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required><br>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category"><br>

            <label for="year">Year Published:</label>
            <input type="number" id="year" name="year"><br>

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn"><br>

            <input type="submit" value="Add Book">
        </form>
    </div>
</body>
</html>

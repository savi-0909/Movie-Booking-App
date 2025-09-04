<?php
// Include configuration file and establish connection
include 'config.php';

// Initialize variables
$message = '';
$errorMessage = '';

// Check if connection is established
if ($conn) {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['delete_movie_id'])) {
            // Handle movie deletion
            $deleteMovieId = $_POST['delete_movie_id'];

            // Delete movie image
            $queryImage = "SELECT image FROM movies WHERE id = ?";
            $stmtImage = $conn->prepare($queryImage);
            $stmtImage->bind_param("i", $deleteMovieId);
            $stmtImage->execute();
            $stmtImage->bind_result($image);
            $stmtImage->fetch();
            $stmtImage->close();

            if ($image && file_exists('uploads/' . $image)) {
                unlink('uploads/' . $image);
            }

            // Delete movie and showtimes
            $queryDeleteShowtime = "DELETE FROM showtimes WHERE movie_id = ?";
            $stmtDeleteShowtime = $conn->prepare($queryDeleteShowtime);
            $stmtDeleteShowtime->bind_param("i", $deleteMovieId);
            $stmtDeleteShowtime->execute();
            $stmtDeleteShowtime->close();

            $queryDeleteMovie = "DELETE FROM movies WHERE id = ?";
            $stmtDeleteMovie = $conn->prepare($queryDeleteMovie);
            $stmtDeleteMovie->bind_param("i", $deleteMovieId);
            $stmtDeleteMovie->execute();
            $stmtDeleteMovie->close();

            $message = "Movie deleted successfully!";
        } else {
            // Handle form submission for adding a movie
            $title = $_POST['title'];
            $description = $_POST['description'];
            $showtime = $_POST['showtime'];
            $price = $_POST['price'];

            // Handle file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageSize = $_FILES['image']['size'];
                $imageError = $_FILES['image']['error'];
                $imageType = $_FILES['image']['type'];

                $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                $allowedExt = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($imageExt, $allowedExt)) {
                    if ($imageError === 0) {
                        if ($imageSize < 5000000) { // Limit file size to 5MB
                            $imageNewName = uniqid('', true) . '.' . $imageExt;
                            $imageDestination = 'uploads/' . $imageNewName;

                            // Check if uploads directory exists
                            if (!is_dir('uploads')) {
                                mkdir('uploads', 0777, true); // Create directory if it doesn't exist
                            }

                            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                               
                                $queryMovie = "INSERT INTO movies (title, description, price, image) VALUES (?, ?, ?, ?)";
                                $stmtMovie = $conn->prepare($queryMovie);

                                if ($stmtMovie) {
                                    $stmtMovie->bind_param("ssds", $title, $description, $price, $imageNewName);

                                    if ($stmtMovie->execute()) {
                                        $movieId = $stmtMovie->insert_id;

                                        $queryShowtime = "INSERT INTO showtimes (movie_id, showtime) VALUES (?, ?)";
                                        $stmtShowtime = $conn->prepare($queryShowtime);

                                        if ($stmtShowtime) {
                                            $stmtShowtime->bind_param("is", $movieId, $showtime);

                                            if ($stmtShowtime->execute()) {
                                                $message = "Movie, price, showtime, and image added successfully!";
                                            } else {
                                                $errorMessage = "Error inserting showtime: " . $stmtShowtime->error;
                                            }

                                            $stmtShowtime->close();
                                        } else {
                                            $errorMessage = "Error preparing showtime statement: " . $conn->error;
                                        }
                                    } else {
                                        $errorMessage = "Error inserting movie: " . $stmtMovie->error;
                                    }

                                    $stmtMovie->close();
                                } else {
                                    $errorMessage = "Error preparing movie statement: " . $conn->error;
                                }
                            } else {
                                $errorMessage = "Error uploading image.";
                            }
                        } else {
                            $errorMessage = "Image file size too large.";
                        }
                    } else {
                        $errorMessage = "Error with image upload.";
                    }
                } else {
                    $errorMessage = "Invalid image file type.";
                }
            } else {
                $errorMessage = "Image not uploaded.";
            }
        }
    }

    // Fetch movies from the database
    $queryMovies = "SELECT movies.id, movies.title, movies.description, movies.price, movies.image, showtimes.showtime 
                    FROM movies 
                    LEFT JOIN showtimes ON movies.id = showtimes.movie_id";
    $resultMovies = $conn->query($queryMovies);
} else {
    $errorMessage = "Database connection error!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            background-image: url('mm1.jpg'); +
            background-size:cover
            background-position: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        form input[type="text"],
        form textarea,
        form select,
        form input[type="number"],
        form input[type="datetime-local"],
        form input[type="file"] {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .delete-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add Movie</h1>

    <?php if ($message): ?>
        <div class="message success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="message error">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="showtime">Showtime:</label>
        <input type="datetime-local" id="showtime" name="showtime" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <input type="submit" value="Add Movie">
        <a href="main.php">BACK</a>
    </form>

    <h2>Movies List</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Showtime</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php if ($resultMovies && $resultMovies->num_rows > 0): ?>
            <?php while ($row = $resultMovies->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['showtime']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" width="50"></td>
                    <td>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display:inline;">
                            <input type="hidden" name="delete_movie_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-button">Delete</button><br><br>
                            <button type="submit" class="delete-button">EDIT</button>
                        </form>
                    </td>
                </tr>
                
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No movies found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>

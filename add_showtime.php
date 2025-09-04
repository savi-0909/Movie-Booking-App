<?php

include 'config.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $movie_id = $_POST['movie_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
   

   
    $query = "INSERT INTO showtimes (movie_id, date, time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
       
        $stmt->bind_param("iss", $movie_id, $date, $time);

       
        if ($stmt->execute()) {
            echo "Showtime added successfully!";
           
        } else {
            echo "Error: " . $stmt->error;
        }

        
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Showtime</title>
    <style>
       
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Showtime</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="movie_id">Movie:</label>
            <select id="movie_id" name="movie_id" required>
                <?php
               
                $query = "SELECT id, title FROM movies";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>

          

            <button type="submit">Add Showtime</button>
        </form>
    </div>
</body>
</html>

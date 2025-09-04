<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php'; // Ensure this includes your database connection

// Initialize $bookings as an empty array
$bookings = array();

$query = "SELECT id, movie_id, date, time, name, email FROM bookings";

$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($id, $movie, $date, $time, $name, $email);

    // Fetch data into $bookings array
    while ($stmt->fetch()) {
        $booking = array(
            'id' => $id,
            'movie_id' => $movie,
            'date' => $date,
            'time' => $time,
            'name' => $name,
            'email' => $email
        );
        $bookings[] = $booking;
    }

    // Close statement
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
        }
        h1 {
            text-align: center;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'templates/header.php'; ?>
    <div class="container">
        <h1>Dashboard - Movie Bookings</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movie</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)) : ?>
                    <tr>
                        <td colspan="6">No bookings found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['movie_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a class="back-btn" href="index.php">Back to Main Page</a>
    </div>
</body>
</html>

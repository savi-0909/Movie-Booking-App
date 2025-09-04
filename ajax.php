<?php
include 'config.php';
include 'classes/Booking.php';

$booking = new Booking($conn);
$result = $booking->read();

while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['name'] . " booked " . $row['movie'] . " on " . $row['date'] . " at " . $row['time'] . "</p>";
}
?>

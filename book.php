<?php

include 'config.php';
include 'classes/Booking.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$movieTitle = '';
$movieDate = '';
$movieTime = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking = new Booking($conn);

    if (isset($_POST['movie_id'], $_POST['date'], $_POST['time'], $_POST['name'], $_POST['email'], $_POST['tickets'])) {
        $booking->movie = $_POST['movie_id'];
        $booking->date = $_POST['date'];
        $booking->time = $_POST['time'];
        $booking->name = $_POST['name'];
        $booking->email = $_POST['email'];
        $booking->tickets = $_POST['tickets'];

        $queryMovie = "SELECT title FROM movies WHERE id = ?";
        $stmtMovie = $conn->prepare($queryMovie);

        if ($stmtMovie) {
            $stmtMovie->bind_param("i", $_POST['movie_id']);
            $stmtMovie->execute();
            $stmtMovie->bind_result($movieTitle);
            $stmtMovie->fetch();
            $stmtMovie->close();
        } else {
            echo "Error fetching movie details: " . $conn->error;
        }

        $pricePerTicket = 100;
        $totalPrice = $pricePerTicket * $_POST['tickets'];

        if ($booking->create()) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->SMTPDebug = 0; 
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nandacumaars@gmail.com'; 
                $mail->Password = 'yvow soxs oujg xpld'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                ];

                $mail->setFrom('nandacumaars@gmail.com', 'Movie Booking');
                $mail->addAddress($booking->email, $booking->name);

                $mail->isHTML(true);
                $mail->Subject = 'Movie Booking Confirmation';
                $mailContent = "
                    <h2>Booking Confirmation</h2>
                    <p>Thank you for booking with us. Here are your booking details:</p>
                    <p><strong>Movie:</strong> {$movieTitle}</p>
                    <p><strong>Date:</strong> {$booking->date}</p>
                    <p><strong>Time:</strong> {$booking->time}</p>
                    <p><strong>Tickets:</strong> {$booking->tickets}</p>
                    <p><strong>Total Price:</strong> rs->{$totalPrice}</p>
                ";
                $mail->Body = $mailContent;

                $mail->send();
                echo "Booking confirmed and email sent!";
            } catch (Exception $e) {
                echo "Booking confirmed but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            echo <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Booking Confirmation</title>
                <style>
                    body {
                        font-family: 'Roboto', sans-serif;
                        background-color: #f0f0f0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                    }
                    .container {
                        max-width: 600px;
                        width: 100%;
                        background-color: #fff;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        text-align: center;
                    }
                    .container h2 {
                        color: #333;
                        margin-top: 0;
                    }
                    .details {
                        margin-top: 20px;
                        font-size: 18px;
                        color: #666;
                    }
                    .details p {
                        margin: 5px 0;
                    }
                    .details strong {
                        color: #333;
                    }
                    .total {
                        margin-top: 20px;
                        font-size: 24px;
                        color: #007bff;
                    }
                    .button {
                        margin-top: 20px;
                    }
                    .button a {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 5px;
                        transition: background-color 0.3s;
                    }
                    .button a:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Booking Successful!</h2>
                    <div class="details">
                        <p><strong>Movie:</strong> {$movieTitle}</p>
                        <p><strong>Date:</strong> {$_POST['date']}</p>
                        <p><strong>Time:</strong> {$_POST['time']}</p>
                        <p><strong>Total Price:</strong> rs->{$totalPrice}</p>
                    </div>
                    <div class="button">
                        <a href="index.php">Back to Home</a>
                    </div>
                </div>
            </body>
            </html>
HTML;
        } else {
            echo "Error: Could not book.";
        }
    } else {
        echo "Error: Missing required form data.";
    }
}
?>

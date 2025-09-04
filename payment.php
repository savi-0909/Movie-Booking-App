<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dummy Payment Page</title>
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
        form {
            margin-top: 20px;
            text-align: left;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="date"],
        form select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Details</h2>
        <form action="book.php" method="POST">
            <input type="hidden" name="movie_id" value="<?php echo $_POST['movie_id']; ?>">
            <input type="hidden" name="price" value="<?php echo $_POST['price']; ?>">
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>
            <label for="expiry">Expiry Date:</label>
            <input type="date" id="expiry" name="expiry" required>
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>

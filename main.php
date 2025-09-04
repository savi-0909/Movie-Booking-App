<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('mm1.jpeg'); +
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            border-radius: 50px;
            
        }

        h1 {
            color: #007BFF;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        button {
            padding: 15px 30px;
            margin: 15px;
            font-size: 18px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05); 
        }

        button:active {
            transform: scale(0.95); 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> Movie Buster</h1>
        <form action="" method="post">
            <button type="submit" name="admin">Admin</button>
            <button type="submit" name="user">User</button>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['admin'])) {
        header("Location: add_movie.php");
        exit();
    } elseif (isset($_POST['user'])) {
        header("Location: login.php");
        exit();
    }
}
?>

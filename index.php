<?php

include 'config.php';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

$sql = "SELECT m.id, m.title, m.description, m.price, m.image, s.showtime 
        FROM movies m
        LEFT JOIN showtimes s ON m.id = s.movie_id";
$result = $conn->query($sql);

$movies = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movieId = $row['id'];
        $movies[$movieId]['title'] = $row['title'];
        $movies[$movieId]['description'] = $row['description'];
        $movies[$movieId]['price'] = $row['price'];
        $movies[$movieId]['image'] = $row['image']; 
        if (!isset($movies[$movieId]['showtimes'])) {
            $movies[$movieId]['showtimes'] = [];
        }
        if (!empty($row['showtime'])) {
            $movies[$movieId]['showtimes'][] = $row['showtime'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ececec;
            background-image: url('mm1.jpeg'); 
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 900px;
            width: 100%;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .movie {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .movie img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .movie h2 {
            color: #333;
            margin-top: 0;
        }
        .movie p {
            color: #666;
        }
        .movie .price {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .showtimes {
            margin-top: 10px;
            color: #555;
        }
        .showtimes ul {
            padding-left: 20px;
        }
        .showtimes li {
            list-style-type: disc;
        }
        form {
            margin-top: 20px;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="date"],
        form select,
        form input[type="number"] {
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
        .sidebar {
            width: 300px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .sidebar h2 {
            color: #333;
            margin-bottom: 10px;
        }
        #recommended-movies {
            list-style-type: none;
            padding: 0;
        }
        #recommended-movies li {
            margin-bottom: 5px;
            cursor: pointer;
        }
        #recommended-movies li:hover {
            text-decoration: underline;
        }
        .sidebar {
            width: 300px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .sidebar h2 {
            color: #333;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        #recommended-movies {
            list-style-type: none;
            padding: 0;
        }
        #recommended-movies li {
            margin-bottom: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        #recommended-movies li:hover {
            text-decoration: underline;
        }
        .movie-icon {
            width: 50px;
            height: auto;
            margin-right: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .movie-details {
            flex: 1;
        }
        .movie-details h3 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        .movie-details p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .chat-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .chat-messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .chat-messages p {
            margin: 5px 0;
        }
        .chat-input {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .chat-send {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .chat-send:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>Nanda</h1>
    </header>


<div class="container">
    <?php include 'templates/header.php'; ?>
    <h1>Movie Buster</h1>  

    <div class="movies-list">
        <?php foreach ($movies as $movieId => $movie) : ?>
            <div class="movie">
                <img src="uploads/<?php echo $movie['image']; ?>" alt="<?php echo $movie['title']; ?>">
                <h2><?php echo $movie['title']; ?></h2>
                <p class="price">₹<?php echo number_format($movie['price'], 2); ?></p>
                <p><?php echo $movie['description']; ?></p>
                <?php if (!empty($movie['showtimes'])) : ?>
                    <p class="showtimes"><strong>Showtimes:</strong></p>
                    <ul>
                        <?php foreach ($movie['showtimes'] as $showtime) : ?>
                            <li><?php echo date('D, d M Y H:i', strtotime($showtime)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="showtimes">No showtimes available</p>
                <?php endif; ?>
                <form action="book.php" method="POST">
                    <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
                    <input type="hidden" name="price" value="<?php echo $movie['price']; ?>">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" required>
                    <label for="time">Available show time:</label>
                    <select id="time" name="time" required>
                        <?php foreach ($movie['showtimes'] as $showtime) : ?>
                            <option value="<?php echo $showtime; ?>">
                                <?php echo date('H:i', strtotime($showtime)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="tickets">Number of Tickets:</label>
                    <input type="number" id="tickets" name="tickets" min="1" value="1" required>
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit">Book Now</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<aside class="sidebar">
    <h2>Recommended Movies</h2>
    <ul id="recommended-movies">
        <!-- Recommendations will be dynamically populated here -->
    </ul>
</aside>

<div class="chat-container">
    <div class="chat-messages" id="chat-messages"></div>
    <input type="text" class="chat-input" id="chat-input" placeholder="Type your message here...">
    <button class="chat-send" id="chat-send">Send</button>
</div>

<script>
    fetch('recommendations.json')
        .then(response => response.json())
        .then(data => {
            const recommendedMovies = document.getElementById('recommended-movies');
            recommendedMovies.innerHTML = '';
            data.forEach(movie => {
                const li = document.createElement('li');
                li.textContent = `${movie.title} - Rating: ${movie.rating}`;
                recommendedMovies.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching recommendations:', error));

    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const chatSend = document.getElementById('chat-send');

    const ws = new WebSocket('ws://localhost:8080');

    ws.onmessage = event => {
        const message = document.createElement('p');
        message.textContent = `Bot: ${event.data}`;
        chatMessages.appendChild(message);
    };

    chatSend.addEventListener('click', () => {
        const message = chatInput.value;
        if (message) {
            ws.send(message);
            const userMessage = document.createElement('p');
            userMessage.textContent = `You: ${message}`;
            chatMessages.appendChild(userMessage);
            chatInput.value = '';
        }
    });

    chatInput.addEventListener('keypress', event => {
        if (event.key === 'Enter') {
            chatSend.click();
        }
    });
</script>

</body>
</html>

<?php
$conn->close();
?>

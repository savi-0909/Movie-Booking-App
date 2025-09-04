<?php
include 'config.php';
include 'classes/User.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['username'] = $user->username;
        header("Location: index.php");
        
    } else {
        
        header("Location: register.php");
        
        
    }
}
?>

<?php include 'templates/header2.php'; ?>

<h1>Login</h1>

<form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
    <button href ="register.php">register</button>
</form>

<?php include 'templates/footer.php'; ?>

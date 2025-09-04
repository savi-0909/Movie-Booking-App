<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php'; // Include database connection configuration
include 'classes/User.php'; // Include the User class definition

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a new User object with the database connection
    $user = new User($conn);

    // Retrieve username and password from POST data
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($user->password, PASSWORD_DEFAULT);

    // Set the hashed password back to the user object
    $user->password = $hashed_password;

    // Call the register method of the User class
    if ($user->register()) {
        echo "User registered successfully!";
    } else {
        // Output specific error message or log detailed error
        echo "Error: Could not register user. " . $user->error;
    }
}
?>

<!-- HTML content starts here -->
<?php include 'templates/header2.php'; ?>

<h1>Register</h1>

<form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
</form>

<?php include 'templates/footer.php'; ?>

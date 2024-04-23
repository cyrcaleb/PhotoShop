<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
// Start or resume a session
session_start();

// Include the database connection script
require 'includes/database-connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    // Query the database to check if the username and hashed password match
    $query = "SELECT * FROM Photographer WHERE email = ? AND password = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email, $hashedPassword]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Set session variables to indicate user login status and type
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = 'Photographer';
        $_SESSION['user_id'] = $user['photographerID']; // Assuming you have a photographerID field in your Photographer table

        // Redirect the user to the dashboard or some other page
        header('Location: about.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "Invalid username or password";
    }
}
?>

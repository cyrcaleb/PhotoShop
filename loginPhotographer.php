<?php
include 'includes/sessions.php';
// Include the database connection script
require 'includes/database-connection.php';

if ($logged_in){
    header('Location: about.php');
    exit;
}

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
        // Login successful, redirect the user to the dashboard or some other page
        login();
        header('Location: about.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "Invalid username or password";
    }
}
?>
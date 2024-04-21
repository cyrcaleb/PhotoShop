<?php
// Include the database connection script
require 'includes/database-connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check if the username and password match
    $query = "SELECT * FROM Account WHERE username = ? AND password = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Login successful, redirect the user to the dashboard or some other page
        header('Location: dashboard.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "Invalid username or password";
    }
}
?>
<?php
// Start the session
session_start();

error_reporting(E_ALL); ini_set('display_errors', 1);
// Include the database connection script
require 'includes/database-connection.php';

// Assume you have a session started already

// Check if the user is logged in
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Photographer') {
    // User is logged in as a photographer
    $isPhotographerLoggedIn = true;
} else {
    // User is not logged in as a photographer
    $isPhotographerLoggedIn = false;
}
?>
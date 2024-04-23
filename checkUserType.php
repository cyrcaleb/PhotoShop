<?php
// Start the session
session_start();

error_reporting(E_ALL); ini_set('display_errors', 1);
// Include the database connection script
require 'includes/database-connection.php';

// Check if the user is logged in
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Photographer') {
    //Print "WE MADE IT IN"
    echo "WE MADE IT IN";

    // User is logged in as a photographer
    $isPhotographerLoggedIn = true;
} else {
    // User is not logged in as a photographer
    echo "WE MADE IT OUT";
    $isPhotographerLoggedIn = false;
}
?>
<?php
session_start();                                         // Start/renew session
$logged_in = $_SESSION['logged_in'] ?? false;            // Is user logged in?

function login($user_type)                                         // Remember user passed login
{
    session_regenerate_id(true);                         // Update session id
    $_SESSION['user_type'] = $user_type;
    $_SESSION['logged_in'] = true;                       // Set logged_in key to true
}   

function logout()                                        // Terminate the session
{
    $_SESSION = [];                                      // Clear contents of array

    $params = session_get_cookie_params();               // Get session cookie parameters
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'],
        $params['secure'], $params['httponly']);         // Delete session cookie

    session_destroy();                                   // Delete session file
}

function require_login($logged_in)                       // Check if user logged in
{
    if ($logged_in == false) {                           // If not logged in
        header('Location: startScreen.php');                   // Send to login page
        exit;                                            // Stop rest of page running
    }
}
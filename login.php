<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_type']) && ($_POST['user_type'] == "Photographer" || $_POST['user_type'] == "Customer")) {
        $user_type = $_POST['user_type'];
        if ($user_type == "Photographer") {
            header("Location: photographer_login.php");
            exit;
        } elseif ($user_type == "Customer") {
            header("Location: customer_login.php");
            exit;
        }
    } else {
        echo "Invalid user type.";
    }
}
?>
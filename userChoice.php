<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['user_type'] === 'Photographer') {
        //set session 'user_type' to 'Photographer'
        $_SESSION['user_type'] = 'Photographer';
        header('Location: photographerLogin.php');
        exit;
    } elseif ($_POST['user_type'] === 'Customer') {
        header('Location: customerLogin.php');
        exit;
    } elseif ($_POST['user_type'] === 'New') {
        header('Location: customerSignUp.php');
        exit;
    }
    else {
        // Handle invalid user type
        echo "Invalid user type selected";
    }
} else {
    // Handle if form is not submitted
    echo "Form not submitted";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose User Type</title>
</head>
<body>
    <h1>Choose Your User Type</h1>
    <form action="login.php" method="post">
        <input type="submit" name="user_type" value="Photographer">
        <input type="submit" name="user_type" value="Customer">
    </form>
</body>
</html>

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
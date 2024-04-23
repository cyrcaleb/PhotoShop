<?php
// Include the database connection script
require 'includes/database-connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phoneNum = $_POST['phoneNum'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password using SHA-256
    $hashedPassword = hash('sha256', $password);

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Fetch the last accountID from the Account table
        $stmt = $pdo->query("SELECT accountID FROM Account ORDER BY accountID DESC LIMIT 1");
        $lastAccountID = $stmt->fetchColumn();

        // Generate the next accountID by incrementing the last one
        $nextAccountID = $lastAccountID + 1;

        //Fetch the last custID from the Customer table
        $stmt = $pdo->query("SELECT custID FROM Customer ORDER BY custID DESC LIMIT 1");
        $lastCustID = $stmt->fetchColumn();

        //Generate the next custID by incrementing the last one
        $custID = $lastCustID + 1;

        // Insert data into Customer table
        $stmt = $pdo->prepare("INSERT INTO Customer (custID, fname, lname, email, phoneNum) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$custID, $fname, $lname, $email, $phoneNum]);

        // Insert data into Account table with the generated accountID
        $stmt = $pdo->prepare("INSERT INTO Account (accountID, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$nextAccountID, $username, $hashedPassword]);

        // Insert data into owns_a table
        $stmt = $pdo->prepare("INSERT INTO owns_a (custID, accountID) VALUES (?, ?)");
        $stmt->execute([$custID, $nextAccountID]);

        // Commit the transaction
        $pdo->commit();

        // Success and redirect to the start screen
        echo "<script>alert('Sign up successful!'); window.location.href = 'startScreen.php';</script>";
        exit;
    } catch (PDOException $e) {
        // Rollback the transaction if an error occurred
        $pdo->rollBack();
        // Display an error message
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Sign Up</title>
</head>
<body>
    <h1>Customer Sign Up</h1>
    <form method="post" action="">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" required><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phoneNum">Phone Number:</label>
        <input type="text" id="phoneNum" name="phoneNum" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>

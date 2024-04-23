<?php
include 'includes/sessions.php';
require_login($logged_in);                  // Redirect user if not logged in
// Include the database connection script
require 'includes/database-connection.php';

function createNewShoot($email, $account, $location, $date, $pdo)
{
    try {
        // Check if any of the fields are empty
        if (empty($email) || empty($account) || empty($location) || empty($date)) {
            throw new Exception("Please fill in all fields.");
        }

        // Validate date format
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            throw new Exception("Invalid date format. Please use Year-Month-Day (e.g., 2000-01-01).");
        }

        // Split date into components
        $dateComponents = explode('-', $date);
        $year = $dateComponents[0];
        $month = $dateComponents[1];
        $day = $dateComponents[2];

        // Check if the month is between 1 and 12
        if ($month < 1 || $month > 12) {
            throw new Exception("Invalid month. Please enter a month between 1 and 12.");
        }

        // Check if the day is between 1 and 31
        if ($day < 1 || $day > 31) {
            throw new Exception("Invalid day. Please enter a day between 1 and 31.");
        }

        // Check if the email exists in the Photographer table
        $stmt_photographer = $pdo->prepare("SELECT photographerID FROM Photographer WHERE email = :email");
        $stmt_photographer->bindParam(':email', $email);
        $stmt_photographer->execute();
        $row_photographer = $stmt_photographer->fetch(PDO::FETCH_ASSOC);
        if (!$row_photographer) {
            throw new Exception("Photographer email not found.");
        }
        $photographerID = $row_photographer['photographerID'];

        // Check if the account username exists in the Account table
        $stmt_account = $pdo->prepare("SELECT accountID FROM Account WHERE username = :account");
        $stmt_account->bindParam(':account', $account);
        $stmt_account->execute();
        $row_account = $stmt_account->fetch(PDO::FETCH_ASSOC);
        if (!$row_account) {
            throw new Exception("Account username not found.");
        }
        $accountID = $row_account['accountID'];

        // Find custID from owns_a table
        $stmt_custID = $pdo->prepare("SELECT custID FROM owns_a WHERE accountID = :accountID");
        $stmt_custID->bindParam(':accountID', $accountID);
        $stmt_custID->execute();
        $row_custID = $stmt_custID->fetch(PDO::FETCH_ASSOC);
        $custID = $row_custID['custID'];

        // Prepare the SQL query to get the last inserted shootID
        $sql_last_id = "SELECT MAX(shootID) AS last_id FROM Photoshoot";
        $stmt_last_id = $pdo->query($sql_last_id);
        $row_last_id = $stmt_last_id->fetch(PDO::FETCH_ASSOC);
        $last_id = $row_last_id['last_id'];
        // Generate the new shootID
        $new_shootID = $last_id + 1;

        // Prepare the SQL query to insert the new photoshoot
        $sql_photoshoot = "INSERT INTO `Photoshoot` (`shootID`, `location`, `date`) 
                    VALUES (:shootID, :location, :date)";

        // Prepare and execute the statement for inserting photoshoot
        $stmt_photoshoot = $pdo->prepare($sql_photoshoot);
        $stmt_photoshoot->bindParam(':shootID', $new_shootID);
        $stmt_photoshoot->bindParam(':location', $location);
        $stmt_photoshoot->bindParam(':date', $date);
        $stmt_photoshoot->execute();

        // Prepare the SQL query to insert into customer_shoot table
        $sql_customer_shoot = "INSERT INTO `customer_shoot` (`custID`, `shootID`, `photographerID`) 
                    VALUES (:custID, :shootID, :photographerID)";

        // Prepare and execute the statement for inserting into customer_shoot
        $stmt_customer_shoot = $pdo->prepare($sql_customer_shoot);
        $stmt_customer_shoot->bindParam(':custID', $custID);
        $stmt_customer_shoot->bindParam(':shootID', $new_shootID);
        $stmt_customer_shoot->bindParam(':photographerID', $photographerID);
        $stmt_customer_shoot->execute();

        // Return the new shootID
        return $new_shootID;
    } catch (PDOException $e) {
        // Print the error message
        return "Error: " . $e->getMessage();
    } catch (Exception $e) {
        // Print the error message
        return "Error: " . $e->getMessage();
    }
}

// Check if the request method is POST (i.e., form submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input (make sure to trim and sanitize the input if necessary)
    $email = $_POST['email'] ?? '';
    $account = $_POST['account'] ?? '';
    $location = $_POST['location'] ?? '';
    $date = $_POST['date'] ?? '';

    // Call the createNewShoot function
    $success = createNewShoot($email, $account, $location, $date, $pdo);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Photoshoot</title>
    <link rel="stylesheet" href="css/photostyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="imgs/PhotoShopLogo.png" alt="PhotoShop Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="photographer_catalog.php">Photographers</a></li>
					<li><a href="photos.php">Photos</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="userLogout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-right">
            <ul>
                <li><a href="p_order.php">Check Order</a></li>
                <li><a href="upload.php">Upload Photos</a></li>
                <li><a href="newShoot.php">New Photoshoot</a></li>
            </ul>
        </div>
    </header>
    <main>
        <div class="order-lookup-container">
            <h1>Create New Photoshoot</h1>
            <div class="order-lookup-container">
                <form action="newShoot.php" method="POST">
                    <div class="form-group">
                        <label for="email">Photographer Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="account">Customer Account Username:</label>
                        <input type="text" id="account" name="account" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date (Year-Month-Day | Example: 2000-01-01):</label>
                        <input type="text" id="date" name="date" required>
                    </div>
                    <button type="submit">Create</button>
                </form>
            </div>
           <?php if (isset($success) && $success === true): ?>
            <div class="order-details">
                <h1>New Photoshoot Details</h1>
                <p><strong>Photoshoot ID Number: </strong><?= $success ?></p>
                <p><strong>Location: </strong><?= $location ?></p>
                <p><strong>Date: </strong><?= $date ?></p>
                <p>Email chain between you and the customer has been established and customer has been given the photoshoot ID number for tracking.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>

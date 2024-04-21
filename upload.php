<?php
// Include the database connection script
require 'includes/database-connection.php';

function addPhotos($shootID, $printCanvas, $size, $price, $imgSrc, $pdo) {
    try {
        // Convert "print" or "canvas" to corresponding integer values
        $printCanvas = strtolower($printCanvas);
        $print = ($printCanvas === "print") ? 1 : 0;
        $canvas = ($printCanvas === "canvas") ? 1 : 0;

        // Prepare the SQL query to get the last inserted photoID
        $sql_last_id = "SELECT MAX(photoID) AS last_id FROM Photo";
        $stmt_last_id = $pdo->query($sql_last_id);
        $row = $stmt_last_id->fetch(PDO::FETCH_ASSOC);
        $last_id = $row['last_id'];
        // Generate the new photoID
        $new_photoID = $last_id + 1;

        // Prepare the SQL query to insert the new photo
        $sql_photo = "INSERT INTO `Photo` (`photoID`, `print`, `canvas`, `size`, `price`, `imgSrc`) 
                VALUES (:photoID, :print, :canvas, :size, :price, :imgSrc)";
        
        // Prepare and execute the statement for inserting photo
        $stmt_photo = $pdo->prepare($sql_photo);
        $stmt_photo->execute([
            ':photoID' => $new_photoID,
            ':print' => $print,
            ':canvas' => $canvas,
            ':size' => $size,
            ':price' => $price,
            ':imgSrc' => $imgSrc
        ]);

        // Prepare the SQL query to insert into contains table
        $sql_contains = "INSERT INTO `contains` (`shootID`, `photoID`) 
                VALUES (:shootID, :photoID)";
        
        // Prepare and execute the statement for inserting into contains
        $stmt_contains = $pdo->prepare($sql_contains);
        $stmt_contains->execute([
            ':shootID' => $shootID,
            ':photoID' => $new_photoID
        ]);
    } catch (PDOException $e) {
        // Print the error message
        echo "Error: " . $e->getMessage();
    }
}

// Check if the request method is POST (i.e., form submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shootID = $_POST['orderNum'];
    $printCanvas = $_POST['printCanvas'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $imgSrc = $_POST['Image'];

    // Call the function to add the photo
    addPhotos($shootID, $printCanvas, $size, $price, $imgSrc, $pdo);

    // Redirect to the order details page to avoid resubmission
    header("Location: order_details.php?shootID=$shootID");
    exit; // Ensure that script stops executing after redirection
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photo</title>
    <link rel="stylesheet" href="css/upload_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Header content -->
    </header>
    <main>
        <div class="upload-container">
            <h1>Upload Photo</h1>
            <div class="upload-form-container">
                <form action="upload.php" method="POST">
                    <div class="form-group">
                        <label for="orderNum">Photoshoot ID Number:</label>
                        <input type="text" id="orderNum" name="orderNum" required>
                    </div>
                    <div class="form-group">
                        <label for="printCanvas">Print or Canvas:</label>
                        <input type="text" id="printCanvas" name="printCanvas" required>
                    </div>
                    <div class="form-group">
                        <label for="size">Size:</label>
                        <input type="text" id="size" name="size" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="Image">Image:</label>
                        <input type="text" id="Image" name="Image" required>
                    </div>
                    <button type="submit">Upload Photo</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>

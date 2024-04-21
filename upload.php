<?php
// Include the database connection script
require 'includes/database-connection.php';

// Define variables to hold the order details
$shootID = "";
$photos = [];
$location = "";

// Check if the request method is POST (i.e., form submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shootID = $_POST['orderNum'];
    $printCanvas = $_POST['printCanvas'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $imgSrc = $_POST['Image'];

    // Call the function to add the photo
    addPhotos($shootID, $printCanvas, $size, $price, $imgSrc, $pdo);

    // Retrieve photos and location based on shoot ID
    $photos = retrievePhotos($shootID, $pdo);
    $location = retrieveLocation($shootID, $pdo); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
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
                    <li><a href="index.php">Toy Catalog</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-right">
            <ul>
                <li><a href="order.php">Check Order</a></li>
                <li><a href="upload.php">Upload Photos</a></li>
                <li><a href="newShoot.php">New Photoshoot</a></li>
            </ul>
        </div>
    </header>
    <main>
        <div class="order-lookup-container">
            <h1>Upload Photo</h1>
            <div class="order-lookup-container">
                <form action="upload.php" method="POST">
                    <div class="form-group">
                        <label for="orderNum">Photoshoot ID Number:</label>
                        <input type="text" id="orderNum" name="orderNum" value="<?= $shootID ?>" required>
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
            <?php if (!empty($photos)): ?>
                <div class="order-details">
                    <h1>Order Details</h1>
                    <p><strong>Photoshoot ID Number: </strong><?= $shootID ?></p>
                    <p><strong>Location: </strong><?= $location ?></p>
                    <div class="photo-container flex-container">
                        <?php foreach ($photos as $photo): ?>
                            <div class="photo orderImg-card">
                                <img src="<?= $photo['imgSrc'] ?>" alt="Photo">
                                <p style="margin: 5px 0;"><strong>Price: </strong><span class="price"><?= $photo['price'] ?></span></p>
                                <?php if ($photo['print'] == 1): ?>
                                    <p style="margin: 5px 0;"><strong>Type: </strong>Print</p>
                                <?php elseif ($photo['canvas'] == 1): ?>
                                    <p style="margin: 5px 0;"><strong>Type: </strong>Canvas</p>
                                <?php else: ?>
                                    <p style="margin: 5px 0;">No specific product type specified for this image.</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

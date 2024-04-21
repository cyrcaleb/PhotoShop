<?php
// Include the database connection script
require 'includes/database-connection.php';

// Retrieve shoot ID from query parameters
$shootID = $_GET['shootID'] ?? null;

if ($shootID) {
    // Retrieve photos and location based on shoot ID
    $photos = retrievePhotos($shootID, $pdo);
    $location = retrieveLocation($shootID, $pdo);
}

// Display the order details page
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/order_details_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Header content -->
    </header>
    <main>
        <div class="order-details-container">
            <?php if ($shootID && !empty($photos)): ?>
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
            <?php else: ?>
                <p>No order details found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

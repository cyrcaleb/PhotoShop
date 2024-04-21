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


	function retrievePhotos($shootID, $pdo) {
		try {
			// Validate shootID to ensure it only contains digits
			if (!ctype_digit($shootID)) {
				throw new Exception("Invalid shootID");
			}
			
			$sql = "SELECT * \n"
				. "FROM contains \n"
				. "JOIN Photo ON contains.photoID = Photo.photoID\n"
				. "WHERE contains.shootID = :shootID;";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['shootID' => $shootID]);
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			// Print error message
			echo "Error retrieving photos: " . $e->getMessage();
			// You can handle the error further, like logging it or redirecting the user
			// For simplicity, just printing the error here
			return false;
		} catch (Exception $e) {
			// Print error message for invalid shootID
			echo "Invalid input: " . $e->getMessage();
			// You can handle the error further, like displaying a user-friendly message
			// For simplicity, just printing the error here
			return false;
		}
	}
	
	function retrieveLocation($shootID, $pdo) {
		try {
			$sql = "SELECT location FROM Photoshoot WHERE shootID = :shootID";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['shootID' => $shootID]);
			$location = $stmt->fetchColumn();
			return $location;
		} catch (PDOException $e) {
			// Print error message
			echo "Error retrieving location: " . $e->getMessage();
			// You can handle the error further, like logging it or redirecting the user
			// For simplicity, just printing the error here
			return false;
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

		$shootID = $_POST['orderNum'];
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
					<li><a href="photographer_catalog.php">Photographers</a></li>
					<li><a href="about.php">About</a></li>
				</ul>
			</nav>
		</div>
		<div class="header-right">
			<ul>
				<li><a href="CustomerView/order.php">Check Order</a></li>
				<li><a href="CustomerView/upload.php">Upload Photos</a></li>
				<li><a href="CustomerView/newShoot.php">New Photoshoot</a></li>
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
			<?php if (!empty($photos)): ?>
				<div class="order-details">
					<h1>Order Details</h1>
					<p><strong>Photoshoot ID Number: </strong><?= $photos[0]['shootID'] ?></p>
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
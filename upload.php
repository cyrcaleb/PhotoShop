<?php   										
	require 'includes/database-connection.php';

	function addPhotos($shootID, $printCanvas, $size, $price, $imgSrc, $pdo) {
		try {
			// Validate input parameters
			if(empty($shootID) || empty($printCanvas) || empty($size) || empty($price) || empty($imgSrc)) {
				throw new Exception("One or more required parameters are missing.");
			}
			
			// Convert "print" or "canvas" to corresponding integer values
			$printCanvas = strtolower($printCanvas);
			$print = ($printCanvas === "print") ? 1 : 0;
			$canvas = ($printCanvas === "canvas") ? 1 : 0;
	
			// Validate and sanitize size and price inputs
			$size = filter_var($size, FILTER_VALIDATE_INT);
			$price = filter_var($price, FILTER_VALIDATE_FLOAT);
			if($size === false || $price === false) {
				throw new Exception("Invalid size or price format.");
			}
	
			// Prepare the SQL query to get the last inserted photoID
			$sql_last_id = "SELECT MAX(photoID) AS last_id FROM Photo";
			$stmt_last_id = $pdo->prepare($sql_last_id);
			$stmt_last_id->execute();
			$row = $stmt_last_id->fetch(PDO::FETCH_ASSOC);
			$last_id = $row['last_id'];
			// Generate the new photoID
			$new_photoID = $last_id + 1;
	
			// Prepare the SQL query to insert the new photo
			$sql_photo = "INSERT INTO `Photo` (`photoID`, `print`, `canvas`, `size`, `price`, `imgSrc`) 
					VALUES (:photoID, :print, :canvas, :size, :price, :imgSrc)";
			
			// Prepare and execute the statement for inserting photo
			$stmt_photo = $pdo->prepare($sql_photo);
			$stmt_photo->bindParam(':photoID', $new_photoID, PDO::PARAM_INT);
			$stmt_photo->bindParam(':print', $print, PDO::PARAM_INT);
			$stmt_photo->bindParam(':canvas', $canvas, PDO::PARAM_INT);
			$stmt_photo->bindParam(':size', $size, PDO::PARAM_INT);
			$stmt_photo->bindParam(':price', $price, PDO::PARAM_STR);
			$stmt_photo->bindParam(':imgSrc', $imgSrc, PDO::PARAM_STR);
			$stmt_photo->execute();
	
			$sql_contains = "INSERT INTO `contains` (`shootID`, `photoID`) 
					VALUES (:shootID, :photoID)";
			
			$stmt_contains = $pdo->prepare($sql_contains);
			$stmt_contains->bindParam(':shootID', $shootID, PDO::PARAM_INT);
			$stmt_contains->bindParam(':photoID', $new_photoID, PDO::PARAM_INT);
			$stmt_contains->execute();
		} catch (PDOException $e) {
			return "Error: " . $e->getMessage();
		} catch (Exception $e) {
			return "Error: " . $e->getMessage();
		}
	}
	


	function retrievePhotos($shootID, $pdo) {
		try {
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
			return false;
		} catch (Exception $e) {
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
		$is_string_success = addPhotos($shootID, $printCanvas, $size, $price, $imgSrc, $pdo);

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
	<title>Upload Photo</title>
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
				</ul>
			</nav>
		</div>
		<div class="header-right">
		<ul>
			<li><a href="p_order.php">Check Order</a></li>
			<!-- only display Upload Photos and New Photoshoot if session user_type is Photographer -->
			<?php
			$user_type = $_SESSION['user_type'] ?? ''; // Retrieve user_type from session
			if ($user_type == "Photographer") { ?>
				<li><a href="upload.php">Upload Photos</a></li>
				<li><a href="newShoot.php">New Photoshoot</a></li>
			<?php } ?>
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
						<label for="size">Size (Inches Diagonally):</label>
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
			<?php if (!is_string($is_string_success) && !empty($photos)): ?>
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

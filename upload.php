<?php   										
	// Include the database connection script
	require 'includes/database-connection.php';

	/*
	 * Define a function that retrieves photos based on shootID and userEmail
	 */
	function addPhotos($shootID, $pdo) {
	}
	

	// Check if the request method is POST (i.e., form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$shootID = $_POST['orderNum'];
		
		$location = retrieveLocation($shootID, $pdo); // Retrieve location
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
			</ul>
		</div>
	</header>
	<main>
		<<div class="order-lookup-container">
			<h1>Upload Photo</h1>
			<div class="order-lookup-container">
				<form action="order.php" method="POST">
					<div class="form-group">
						<label for="orderNum">Photoshoot ID Number:</label>
						<input type="text" id="orderNum" name="orderNum" required>
					</div>
                    <div class="form-group">
						<label for="email">Customer Email:</label>
						<input type="text" id="email" name="email" required>
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

<?php   										
	// Include the database connection script
	require 'includes/database-connection.php';

	/*
	 * Define a function that retrieves photos based on shootID and userEmail
	 */
	function retrievePhotos($shootID, $pdo) {
		$sql = "SELECT * \n"
			. "FROM contains \n"

			. "JOIN Photo ON contains.photoID = Photo.photoID\n"

			. "WHERE contains.shootID = :shootID;";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['shootID' => $shootID]);
		return $stmt->fetchAll();
	}
	

	// Check if the request method is POST (i.e., form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Retrieve the value of the 'shootID' field from the POST data
		$shootID = $_POST['shootID'];
		/*
		 * Retrieve photos from the database using provided PDO connection
		 */
		$photos = retrievePhotos($shootID, $userEmail, $pdo);
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
			</ul>
		</div>
	</header>
	<main>
		<div class="order-lookup-container">
			<h1>Order Lookup</h1>
			<div class="order-lookup-container">
				<form action="order.php" method="POST">
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="email" id="email" name="email" required>
					</div>
					<div class="form-group">
						<label for="orderNum">Order Number:</label>
						<input type="text" id="orderNum" name="orderNum" required>
					</div>
					<button type="submit">Lookup Order</button>
				</form>
			</div>
			<?php if (!empty($orderInfo)): ?>
				<div class="order-details">
					<h1>Order Details</h1>
					<p><strong>Photoshoot ID Number: </strong><?= $orderInfo['shootID'] ?></p>
					<p><strong>Image: </strong><?= $orderInfo['imgSrc'] ?></p>
					<p><strong>Price: </strong><?= $orderInfo['price'] ?></p>
				</div>
			<?php endif; ?>
		</div>
	</main>
</body>
</html>
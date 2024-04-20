<?php   										
	// Include the database connection script
	require 'includes/database-connection.php';

	/*
	 * TO-DO: Define a function that retrieves photos based on shootID
	 */
	function retrievePhotos($shootID, $pdo) {
		$sql = "SELECT * FROM photos WHERE shootID = :shootID";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['shootID' => $shootID]);
		return $stmt->fetchAll();
	}

	// Check if the request method is POST (i.e., form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Retrieve the value of the 'shootID' field from the POST data
		$shootID = $_POST['shootID'];

		/*
		 * TO-DO: Retrieve photos from the db using provided PDO connection
		 */
		$photos = retrievePhotos($shootID, $pdo);
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
				<img src="imgs/logo.png" alt="Toy R URI Logo">
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
		<div class="photo-gallery-container">
			<h1>Photo Gallery</h1>
			<form action="order.php" method="POST">
				<div class="form-group">
					<label for="shootID">Shoot ID:</label>
					<input type="text" id="shootID" name="shootID" required>
				</div>
				<button type="submit">View Photos</button>
			</form>
			<?php if (!empty($photos)): ?>
				<div class="photographer-catalog">
					<?php foreach ($photos as $photo): ?>
						<div class="photographer-card">
							<img src="<?= $photo['photo_url'] ?>" alt="<?= $photo['photo_description'] ?>">
							<h2><?= $photo['photo_description'] ?></h2>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</main>
</body>
</html>
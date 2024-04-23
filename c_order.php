<?php   										
	require 'includes/database-connection.php';

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
			echo "Error retrieving photos: " . $e->getMessage();
			return false;
		} catch (Exception $e) {
			echo "Invalid input: " . $e->getMessage();
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
			echo "Error retrieving location: " . $e->getMessage();
			return false;
		}
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
	<title>Check Order</title>
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
				<li><a href="c_order.php">Check Order</a></li>
			</ul>
		</div>
	</header>
	<main>
		<div class="order-lookup-container">
			<h1>Order Lookup</h1>
			<div class="order-lookup-container">
				<form action="c_order.php" method="POST">
					<div class="form-group">
						<label for="orderNum">Photoshoot ID Number:</label>
						<input type="text" id="orderNum" name="orderNum" required>
					</div>
					<button type="submit">Lookup Order</button>
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

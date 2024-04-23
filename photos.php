<?php
    require 'includes/database-connection.php';

    /*
	 * Retrieves a specified number of photos and their information from the database.
	 * 
	 * @param PDO $pdo          An instance of the PDO class.
	 * @param int $numPhotos    The number of photos to retrieve.
	 */
    function RetrievePhotos($pdo, int $numPhotos) {
        $sql = "SELECT * FROM Photo
		    JOIN contains ON Photo.photoID = contains.photoID
			JOIN Photoshoot ON Photoshoot.shootID = contains.shootID
            JOIN customer_shoot ON contains.shootID = customer_shoot.shootID
			JOIN Photographer ON Photographer.photographerID = customer_shoot.photographerID
			LIMIT :numPhotos;";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['numPhotos' => $numPhotos]);

        return $stmt->fetchAll();
    }
	$photos = RetrievePhotos($pdo, 100);

	// Randomize the photos array in PHP instead of using SQL.
	shuffle($photos);
?>

<!DOCTYPE>
<html> 
    <head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>PhotoShop</title>
		<link rel="stylesheet" href="css/photostyle.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
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
		
		<div class="photo-page-container flex-container">
			<?php foreach ($photos as $photo): ?>
				<div class="photo orderImg-card photo-card">
					<a href="photographer.php?photographerID=<?= $photo['photographerID'] ?>">
						<img src="<?= $photo['imgSrc'] ?>" alt="<?= $photo['photoId'] ?>">
					</a>
					<h2><?= $photo['location'] ?></h2>
					<h4>Taken by <?= $photo['fname'] ?> <?= $photo['lname'] ?></h4>
				</div>
			<?php endforeach; ?>
		</div>
    </body>
</html>
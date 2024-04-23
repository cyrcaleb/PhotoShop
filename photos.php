<?php
    require 'includes/database-connection.php';

    /*
	 * Retrieves a specified number of random photos from the database.
	 * 
	 * @param PDO $pdo          An instance of the PDO class.
	 * @param int $numPhotos    The number of photos to retrieve.
	 */
    function RetrieveRandomPhotos($pdo, int $numPhotos) {
        $sql = "SELECT * FROM Photo
		    JOIN contains ON Photo.photoID = contains.photoID
			JOIN Photoshoot ON Photoshoot.shootID = contains.shootID
            JOIN customer_shoot ON contains.shootID = customer_shoot.shootID
			JOIN Photographer ON Photographer.photographerID = customer_shoot.photographerID
			ORDER BY RAND() LIMIT :numPhotos;";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['numPhotos' => $numPhotos]);

        return $stmt->fetchAll();
    }

	$randomPhotos = RetrieveRandomPhotos($pdo, 15);
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
					<li><a href="p_order.php">Check Order</a></li>
					<!-- <li><a href="upload.php">Upload Photos</a></li>
					<li><a href="newShoot.php">New Photoshoot</a></li> -->
		    	</ul>
		    </div>
		</header>
		
		<div class="photo-container flex-container">
			<?php foreach ($randomPhotos as $photo): ?>
				<div class="photo orderImg-card">
					<a href="photographer.php?photographerID=<?= $photo['photographerID'] ?>">
						<img src="<?= $photo['imgSrc'] ?>" alt="<?= $photo['photoId'] ?>">
					</a>
					<h2><?= $photo['location'] ?></h2>
					<h3>Taken by <?= $photo['fname'] ?> <?= $photo['lname'] ?></h3>
				</div>
			<?php endforeach; ?>
		</div>
    </body>
</html>
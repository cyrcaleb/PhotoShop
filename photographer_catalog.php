<?php

    // Include the database connection script
    require 'includes/database-connection.php';

    function get_photographer(PDO $pdo) {

		// SQL query to retrieve every photographer in the database
		$sql = "SELECT * 
			FROM Photographer;";

		// Execute the SQL query using the pdo function and fetch the result
		$photographers = pdo($pdo, $sql)->fetchAll();
		
		// Return the photographer information (associative array)
		return $photographers;
	}

	// Retrieve info for ALL photographers from the db
	$photographers = get_photographer($pdo);

?>


<!DOCTYPE html>
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
					<li><a href="upload.php">Upload Photos</a></li>
					<li><a href="newShoot.php">New Photoshoot</a></li>
		    	</ul>
		    </div>
		</header>

  		<main>
  			<section class="photographer-catalog">
  				<?php
					// Loop through the photographers array and display the photographer information
					foreach ($photographers as $photographer) {
				?>
					<div class="photographer-card">
						<a href="photographer.php?photographerID=<?= $photographer['photographerID'] ?>">
							<img src="<?= $photographer['pfpSrc'] ?>" alt="<?= $photographer['fname'] ?> <?= $photographer['lname']?>">
						</a>
						<h2><?= $photographer['fname'] ?> <?= $photographer['lname']?></h2>
						<a href="photographer.php?photographerID=<?= $photographer['photographerID'] ?>"> 
							<p> See Photographer Profile </p>
						</a> 
					</div>
				<?php
					}
				?>
  			</section>
  		</main>

	</body>
</html>

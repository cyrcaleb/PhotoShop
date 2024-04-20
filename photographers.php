<?php

    // Include the database connection script
    require 'includes/database-connection.php';

    function get_photographer(PDO $pdo, string $id) {

		// SQL query to retrieve photographer information based on the photographer ID
		$sql = "SELECT * 
			FROM Photographer
			WHERE photographerID = :id;";	// :id is a placeholder for value provided later 
		                               // It's a parameterized query that helps prevent SQL injection attacks and ensures safer interaction with the database.


		// Execute the SQL query using the pdo function and fetch the result
		$photographer = pdo($pdo, $sql, ['id' => $id])->fetch();		// Associative array where 'id' is the key and $id is the value. Used to bind the value of $id to the placeholder :id in  SQL query.

		// Return the photographer information (associative array)
		return $photographer;
	}
	
	// Retrieve info for ALL photographers from the db
	$photographers = []; // Initialize an array to store info for all photographers

	// Fetch data for each remaining photographer using a loop
	for ($i = 1; $i < 6; $i++) {
	    $photographer_id = '3' . sprintf('%010d', $i); // Format the photograher ID with leading zeros
	    $photographers[] = get_photographer($pdo, $photographer_id); // Fetch photographer info and add to the array
	}

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
  			<section class="photographer-catalog">
  				<?php
					// Loop through the photographers array and display the photographer information
					foreach ($photographers as $photographer) {
				?>
					<div class="photographer-card">
						<a href="Photographer.php?photographerID=<?= $photographer['photographerID'] ?>">
							<img src="<?= $photographer['pfpSrc'] ?>" alt="<?= $photographer['fname'] ?> <?= $photographer['lname']?>">
						</a>
						<h2><?= $photographer['fname'] ?> <?= $photographer['lname']?></h2>
						<a href="Photographer.php?photographerID=<?= $photographer['photographerID'] ?>"> 
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

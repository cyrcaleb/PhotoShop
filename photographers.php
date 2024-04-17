<?php

    // Include the database connection script
    require 'includes/database-connection.php';

    function get_photographer(PDO $pdo, string $id) {

		// SQL query to retrieve photographer information based on the toy ID
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
	    $photographer_id = '3' . sprintf('%10d', $i); // Format the photograher ID with leading zeros
	    $photographers[] = get_toy($pdo, $photographer_id); // Fetch photographer info and add to the array
	}

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Toys R URI</title>
  		<link rel="stylesheet" href="css/style.css">
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
  			<section class="toy-catalog">

  				<div class="toy-card">
  					<!-- Create a hyperlink to toy.php page with toy number as parameter -->
  					<a href="toy.php?toynum=<?= $toy1['toynum'] ?>">

  						<!-- Display image of toy with its name as alt text -->
  						<img src="<?= $toy1['imgSrc'] ?>" alt="<?= $toy1['name'] ?>">
  					</a>

  					<!-- Display name of toy -->
  					<h2><?= $toy1['name'] ?></h2>

  					<!-- Display price of toy -->
  					<p>$<?= $toy1['price'] ?></p>
  				</div>


  				<!-- 
				  -- TO DO: Fill in the rest of the cards for ALL remaining toys from the db
  				  -->
  				<?php
					// Populate the HTML elements for each remaining toy card dynamically
					foreach ($remaining_toys as $toy) {
				?>
				    <div class="toy-card">
				        <a href="toy.php?toynum=<?= $toy['toynum'] ?>">
				            <img src="<?= $toy['imgSrc'] ?>" alt="<?= $toy['name'] ?>">
				        </a>
				        <h2><?= $toy['name'] ?></h2>
				        <p>$<?= $toy['price'] ?></p>
				    </div>
				<?php
					}
				?>

  			</section>
  		</main>

	</body>
</html>

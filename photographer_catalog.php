<?php
	include 'includes/sessions.php';
	require_login($logged_in);                  // Redirect user if not logged in
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
	
	// Get the number of photographers in the database
	function count_photographers(PDO $pdo) {
		// SQL query to count the number of photographers in the database
		$sql = "SELECT COUNT(*) AS num_photographers
				FROM Photographer;";

		// Execute the SQL query using the pdo function and fetch the result
		$photographer_count = pdo($pdo, $sql)->fetch();

		// Return the number of photographers in the database
		return $photographer_count;
	}

	// Retrieve info for ALL photographers from the db
	$photographers = []; // Initialize an array to store info for all photographers
	$num_photographers = count_photographers($pdo)['num_photographers']; // Get the total number of photographers in the database

	// Fetch data for each remaining photographer using a loop
	for ($i = 1; $i <= $num_photographers; $i++) {
	    $photographer_id = '3' . sprintf('%010d', $i); // Format the photograher ID with leading zeros
	    $photographers[] = get_photographer($pdo, $photographer_id); // Fetch photographer info and add to the array
	}

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

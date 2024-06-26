<?php   										// Opening PHP tag
	include 'includes/sessions.php';
	require_login($logged_in);                  // Redirect user if not logged in
	// Include the database connection script
	require 'includes/database-connection.php';

	// Retrieve the value of the 'photographerID' parameter from the URL query string
	//		i.e., ../photographer.php?photographerID=3000000001
	$photographer_id = $_GET['photographerID'];


	// Get a spefic photographer's information from the database using the 'photographerID'
	function get_photographer(PDO $pdo, string $id) {
		
		// SQL query to retrieve photographer information based on the photographer ID
		$sql = "SELECT fname, lname, salary, email, phoneNum, pfpSrc
				FROM Photographer
                WHERE photographerID = :id;";


		// Execute the SQL query using the pdo function and fetch the result
		$photographer = pdo($pdo, $sql, ['id' => $id])->fetch();

		// Return the photographer information (associative array)
		return $photographer;

	}

    // Get the number of photoshoots for a specific photographer
    function count_shoots(PDO $pdo, string $id) {

        // SQL query to count how many times a given photographerID has been used in the customer_shoot table
        $sql = "SELECT COUNT(*) AS numshoots
                FROM customer_shoot
                WHERE photographerID = :id;";

        // Execute the SQL query using the pdo function and fetch the result
        $shoot_count = pdo($pdo, $sql, ['id' => $id])->fetch();

        // Return the number of photoshoots for the given photographer
        return $shoot_count;

    }

    // Get the photos shot by specific photographer
    function get_photos(PDO $pdo, string $id) {

        // SQL query to retrieve photos shot by a specific photographer
        // Use the photographerID to find their shoots
        // Use those shoots to find all the photos within
        // Get and return the image sources of those photos
        $sql = "SELECT Photo.imgSrc, Photo.photoID
                FROM Photo
                JOIN contains ON Photo.photoID = contains.photoID
                JOIN customer_shoot ON contains.shootID = customer_shoot.shootID
                WHERE customer_shoot.photographerID = :id;";

        // Execute the SQL query using the pdo function and fetch the result
        $photos = pdo($pdo, $sql, ['id' => $id])->fetchAll();

        // Return the photos shot by the given photographer
        return $photos;

    }

// Closing PHP tag  ?> 

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
			
            <!-- Get the photographer's information and photos -->
			<?php $photographer = get_photographer($pdo, $photographer_id); ?>
            <?php $shoot_count = count_shoots($pdo, $photographer_id); ?>
            <?php $photos = get_photos($pdo, $photographer_id); ?>

			<div class="photographer-details-container">
				<div class="photographer-image">
					<!-- Display image of toy with its name as alt text -->
					<img src="<?= $photographer['pfpSrc'] ?>" alt="<?= $photographer['fname'] ?> <?=$photographer['lname'] ?>">
				</div>

				<div class="photographer-details">
					<!-- Display name of photographer -->
			        <h1><?= $photographer['fname'] ?> <?=$photographer['lname']?></h1>
			        <hr />
			        <h3>Photographer Details</h3>
			        <!-- Number of photoshoots -->
			        <p><strong>Total photoshoots:</strong> <?= $shoot_count['numshoots'] ?></p>
			        <!-- Yearly salary -->
			        <p><strong>Salary:</strong> $<?= $photographer['salary'] ?></p>
			        <!-- Contact email -->
			        <p><strong>Email:</strong> <?= $photographer['email'] ?></p>
			        <!-- Contact phone number -->
			        <p><strong>Phone number:</strong> <?= $photographer['phoneNum'] ?></p>
			    </div>
            </div>

            <!-- Display photos taken by the photographer -->
            <div class="order-details">
                <h2><?= $photographer['fname'] ?> <?=$photographer['lname'] ?>'s photos</h2>
                <div class="photo-container flex-container">
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo orderImg-card">
                            <img src="<?= $photo['imgSrc'] ?>" alt="Photo Num <?= $photo['photoID'] ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
			
		</main>

	</body>
</html>

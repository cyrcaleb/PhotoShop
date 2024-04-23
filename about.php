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
				<li><a href="p_order.php">Check Order</a></li>
				<?php
					// Include the PHP script to check if the user is logged in as a photographer
					require_once 'check_photographer_login.php';
					// Check if the user is logged in as a photographer
					if !($isPhotographerLoggedIn) {
						echo '<li><a href="upload.php">Upload Photos</a></li>';
						echo '<li><a href="newShoot.php">New Photoshoot</a></li>';
					}
				?>
			</ul>
		</div>
	</header>
	<main>
    <h1>Welcome to Photoshop!</h1>
    <p>We help to pair photographers with clients, and this serves as a platform where both parties can access the photos from the respective photoshoot. Customers are encouraged to find potential photographers using this application and retrieve their recent photos after a photoshoot, and photographers are encouraged to build an outstanding portfolio through this application and showcase their best work!</p>

    <h2>For Photographers:</h2>
    <p>Photographers have the opportunity to create new photoshoots for themselves and their customers, upload photos to corresponding photoshoots, and take a look at other photographers and the work they have completed.</p>

    <h2>For Customers:</h2>
    <p>For customers, there is a wide variety of options when navigating through this application. To start, customers can go to the “Photographers” page. They can see the top-rated photographers and filter/organize the results from here. If they see a photographer they like, they can click on them to view their profile. Their profile shows the number of photoshoots they have done, salary, and contact information such as emails and phone numbers.</p>
    
    <p>Another page that customers have access to is “Check order”. After a photoshoot, a customer will be supplied a photoshootID for their photos. Within the check order page, they can simply paste the photoshootID. If successful, you can scroll through the photos that were taken during this photoshoot. You can see the price of each photo and the type (canvas, print).</p>
	</main>
</body>
</html>

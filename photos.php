<?php
    require 'includes/database-connection.php';

    /*
	 * Retrieves a specified number of random photos from the database.
	 * 
	 * @param PDO $pdo          An instance of the PDO class.
	 * @param int $numPhotos    The number of photos to retrieve.
	 */
    function RetrieveRandomPhotos($pdo, int $numPhotos) {
        $sql = "SELECT * FROM photos ORDER BY RAND() LIMIT :numPhotos;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['numPhotos' => $numPhotos]);

        return $stmt->fetchAll();
    }
?>

<!DOCTYPE>
<html> 
    <head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>PhotoShop</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

    <body>
    
    </body>
</html>
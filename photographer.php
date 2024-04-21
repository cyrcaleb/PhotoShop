<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

	// Retrieve the value of the 'photographerID' parameter from the URL query string
	//		i.e., ../photographer.php?photographerID=3000000001
	$photographer_id = $_GET['photographerID'];


	
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

// Closing PHP tag  ?> 
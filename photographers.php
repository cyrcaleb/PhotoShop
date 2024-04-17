<?php

    // Include the database connection script
    require 'includes/database-connection.php';

    function get_photographer(PDO $pdo, string $id) {

		// SQL query to retrieve toy information based on the toy ID
		$sql = "SELECT * 
			FROM Photographer
			WHERE photographerID = :id;";	// :id is a placeholder for value provided later 
		                               // It's a parameterized query that helps prevent SQL injection attacks and ensures safer interaction with the database.


		// Execute the SQL query using the pdo function and fetch the result
		$photographer = pdo($pdo, $sql, ['id' => $id])->fetch();		// Associative array where 'id' is the key and $id is the value. Used to bind the value of $id to the placeholder :id in  SQL query.

		// Return the toy information (associative array)
		return $photographer;
	}

?>
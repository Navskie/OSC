<?php
include_once '../../database/conn.php';

// Get the country and search term from the request
$country = isset($_GET['new_country']) ? $_GET['new_country'] : '';
$q = isset($_GET['q']) ? $_GET['q'] : '';

if ($country) {
    // Handle empty search term
    $searchTerm = "%" . $q . "%"; // Wildcards for LIKE

    // Prepare the query with the selected country and search term
    $stmt = $conn->prepare("
        SELECT id, state_name, state_country
        FROM upti_state
        WHERE state_country = ? AND state_name LIKE ?;
    ");

    // Bind the parameters
    $stmt->bind_param("ss", $country, $searchTerm);

    // Execute the statement
    $stmt->execute();

    // Bind the result columns to PHP variables
    $stmt->bind_result($id, $state_name, $state_country);

    $data = [];
    // Fetch the results
    while ($stmt->fetch()) {
        $data[] = [
            "id" => $id,   // Assuming 'id' is the primary key
            "text" => $state_name
        ];
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Return the data as JSON
    echo json_encode($data);
} else {
    // If no country is provided, return an empty response
    echo json_encode([]);
}
?>
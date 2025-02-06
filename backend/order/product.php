<?php
include_once '../../database/conn.php'; // Ensure this initializes $conn properly

// ✅ Ensure the database connection is valid
if (!isset($conn) || $conn->connect_error) {
    die(json_encode(["error" => "Database Connection Failed: " . ($conn->connect_error ?? "Unknown Error")]));
}

// Check if searching is happening
$searchTerm = isset($_GET['q']) ? $_GET['q'] : "";

// Prepare SQL Query
if ($searchTerm) {
    // Search query (limit 10 results)
    $stmt = $conn->prepare("
        (
            SELECT items_code AS code, items_desc AS description 
            FROM upti_items 
            WHERE (items_desc LIKE ? OR items_code LIKE ?) AND items_status = 'Active'
            LIMIT 10
        )
        UNION 
        (
            SELECT package_code AS code, package_desc AS description 
            FROM upti_package 
            WHERE (package_desc LIKE ? OR package_code LIKE ?) AND package_status = 'Active'
            LIMIT 10
        )
        LIMIT 10
    ");
    
    $searchTerm = "%".$searchTerm."%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    // Default query (limit 5 results)
    $stmt = $conn->prepare("
        (
            SELECT items_code AS code, items_desc AS description 
            FROM upti_items 
            WHERE items_status = 'Active'
            LIMIT 5
        )
        UNION 
        (
            SELECT package_code AS code, package_desc AS description 
            FROM upti_package 
            WHERE package_status = 'Active'
            LIMIT 5
        )
        LIMIT 5
    ");
}

// ✅ Check if the statement was prepared successfully
if (!$stmt) {
    die(json_encode(["error" => "Statement Preparation Failed: " . $conn->error]));
}

// Execute the statement
if (!$stmt->execute()) {
    die(json_encode(["error" => "Query Execution Failed: " . $stmt->error]));
}

// Fetch Data
$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['code'], // Value = item_code / package_code
        "text" => $row['code'] . " - " . $row['description'] // Display = "code - description"
    ];
}

// Close resources
$stmt->close();
$conn->close();

// Return JSON response
echo json_encode($data);
?>

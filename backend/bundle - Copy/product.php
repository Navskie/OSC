<?php
include_once '../../database/conn.php';

if (!isset($conn) || $conn->connect_error) {
    die(json_encode(["error" => "Database Connection Failed: " . ($conn->connect_error ?? "Unknown Error")])); 
}

$searchTerm = isset($_GET['q']) ? $_GET['q'] : "";

$removeCategories = []; // Default na walang restriction

// Check POID to decide whether to remove 'UPSELL' and 'PREMIUM'
if (!empty($rspoid)) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM upti_order_list 
        INNER JOIN upti_code ON code_name = ol_code 
        WHERE ol_poid = ? 
    ");
    $stmt->bind_param("s", $rspoid);
    $stmt->execute();
    $stmt->bind_result($poidCount);
    $stmt->fetch();
    $stmt->close();

    // If the count is greater than 0, include 'UPSELL' and 'PREMIUM'
    if ($poidCount > 0) {
        $removeCategories = ['UPSELL', 'PREMIUM', 'NO UPSELL'];
    }
}

// Dynamically build the WHERE condition for the query based on $removeCategories
$categoryFilter = "";
if (!empty($removeCategories)) {
    // Include 'UPSELL' and 'PREMIUM' if POID exists
    $categoryFilter = "AND code_category IN ('RESELLER', 'UPSELL', 'PREMIUM')";
} else {
    // If no restriction for POID, don't exclude 'UPSELL' and 'PREMIUM'
    $categoryFilter = "AND code_category = 'RESELLER'";
}

if (!empty($searchTerm)) {
    // Prepare the query with search term
    $stmt = $conn->prepare("
        (
            SELECT items_code AS code, items_desc AS description 
            FROM upti_items 
            INNER JOIN upti_code ON code_name = items_code
            WHERE (items_desc LIKE ? OR items_code LIKE ?)
            AND items_status = 'Active'
            $categoryFilter
            LIMIT 10
        )
        UNION 
        (
            SELECT package_code AS code, package_desc AS description 
            FROM upti_package 
            INNER JOIN upti_code ON code_name = package_code
            WHERE (package_desc LIKE ? OR package_code LIKE ?)
            AND package_status = 'Active'
            $categoryFilter
            LIMIT 10
        )
        LIMIT 10
    ");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    // Prepare the query without search term
    $stmt = $conn->prepare("
        (
            SELECT items_code AS code, items_desc AS description 
            FROM upti_items 
            INNER JOIN upti_code ON code_name = items_code
            WHERE items_status = 'Active'
            $categoryFilter
            LIMIT 5
        )
        UNION 
        (
            SELECT package_code AS code, package_desc AS description 
            FROM upti_package 
            INNER JOIN upti_code ON code_name = package_code
            WHERE package_status = 'Active'
            $categoryFilter
            LIMIT 5
        )
        LIMIT 5
    ");
}

// Ensure statement preparation is successful
if (!$stmt) {
    die(json_encode(["error" => "Statement Preparation Failed: " . $conn->error]));
}

// Execute the statement
if (!$stmt->execute()) {
    die(json_encode(["error" => "Query Execution Failed: " . $stmt->error]));
}

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['code'],
        "text" => $row['code'] . " - " . $row['description']
    ];
}

$stmt->close();
$conn->close();

// Return the data as JSON
echo json_encode($data);
?>

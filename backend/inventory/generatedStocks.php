<?php

include_once '../../database/conn.php';

header("Content-Type: application/json"); // Set response type

$country = isset($_POST['country']) ? $conn->real_escape_string($_POST['country']) : '';
$territory = ($country === "USA") ? "TERRITORY 3" : "TERRITORY 1";

$sql = "SELECT * FROM stockist_inventory WHERE si_item_country = ? AND si_item_role = ? AND si_item_code != ''";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $country, $territory);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $stocks = $row['si_item_stock'];
    $criticalStocks = $row['si_item_critical'];
    $status = ($criticalStocks >= $stocks) ? "Low Stocks" : "Good Stocks";

    $data[] = [
        "item_code" => $row['si_item_code'],
        "item_desc" => $row['si_item_desc'],
        "item_stock" => $row['si_item_stock'],
        "status" => $status
    ];
}

$response = ["data" => $data, "count" => count($data)];

echo json_encode($response); // Return JSON

$stmt->close();
$conn->close();


?>

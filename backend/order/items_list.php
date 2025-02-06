<?php
include_once '../../database/conn.php';

$query = "SELECT ol_code, ol_desc, ol_qty, ol_price, ol_subtotal FROM upti_order_list WHERE ol_poid = '$poid'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$orderDetails = [];

while ($row = $result->fetch_assoc()) {
    $orderDetails[] = $row;
}

$stmt->close();

if (empty($orderDetails)) {
    echo json_encode([
        "status" => "error",
        "message" => "No order details found"
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "data" => $orderDetails
    ]);
}

$conn->close();
?>

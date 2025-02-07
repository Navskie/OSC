<?php
include_once '../../database/conn.php';

header('Content-Type: application/json'); // Ensure JSON response

$sql = "SELECT * FROM upti_order_list WHERE ol_poid = '$poid'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
exit;
?>

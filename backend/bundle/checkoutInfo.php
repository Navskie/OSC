<?php
include_once '../../database/conn.php';

$sql = "SELECT * FROM upti_order_list WHERE ol_poid = '$rspoid'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $code = $row['ol_code'];

    // Fetch bundle details
    $sqlBundle = "SELECT p_s_desc, p_s_qty FROM upti_pack_sett WHERE p_s_main = '$code'";
    $resultBundle = mysqli_query($conn, $sqlBundle);
    $bundles = [];

    while ($bundleRow = mysqli_fetch_assoc($resultBundle)) {
        $bundles[] = $bundleRow;
    }

    $data[] = [
        'ol_code' => $row['ol_code'],
        'ol_desc' => $row['ol_desc'],
        'ol_qty' => $row['ol_qty'],
        'ol_price' => $row['ol_price'],
        'ol_subtotal' => $row['ol_subtotal'],
        'bundles' => $bundles
    ];
}

if (!empty($data)) {
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "No data found for the given POID."]);
}
exit;
?>
<?php
include_once '../../database/conn.php';

$poid = $_GET['poid'] ?? '';

if ($poid) {
    $result = $conn->query("SELECT COUNT(*) AS count FROM upti_order_list WHERE ol_poid = '$poid'");
    $hasOrders = $result->fetch_assoc()['count'] > 0;
    echo json_encode(['hasOrders' => $hasOrders]);
}
?>

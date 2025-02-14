<?php

include_once '../../database/conn.php';

// Check if the delete request is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ol_code'])) {
    $orderCode = $_POST['ol_code'];

    // Sanitize the input to prevent SQL injection
    $orderCode = $conn->real_escape_string($orderCode);

    // SQL query to delete the item from the database
    $sql = "DELETE FROM upti_order_list WHERE ol_code = '$orderCode' AND ol_poid = '$rspoid'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete item: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>

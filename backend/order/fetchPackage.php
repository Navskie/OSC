<?php
include_once '../../database/conn.php';

header('Content-Type: application/json'); // Ensure response is JSON

if (!isset($_GET['code']) || empty($_GET['code'])) {
    echo json_encode(["error" => "No code provided"]);
    exit();
}

$code = $_GET['code'];

$sql = "SELECT p_s_desc, p_s_qty FROM upti_pack_sett WHERE p_s_code = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data); // Return data as JSON
} else {
    echo json_encode(["error" => "Query preparation failed"]);
}

$stmt->close();
$conn->close();
?>

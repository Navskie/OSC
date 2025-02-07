<?php
include_once '../../database/conn.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $mainItem = isset($_POST['mainItem']) ? mysqli_real_escape_string($conn, $_POST['mainItem']) : null;
    $freeItem = isset($_POST['freeItem']) ? mysqli_real_escape_string($conn, $_POST['freeItem']) : null;
    $ol_seller = $users_code ?? '';
    $ol_reseller = $users_creator ?? '';
    $ol_country = $_SESSION['country'] ?? '';

    // Validate inputs
    if (!$mainItem || !$freeItem || !$poid) {
        echo json_encode(["status" => "error", "message" => "Main item, free item, and POID are required"]);
        exit;
    }

    // Fetch quantity from the main item (assuming upti_order_list stores it)
    $query = "SELECT ol_qty FROM upti_order_list WHERE ol_code = '$mainItem' AND ol_poid = '$poid'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $quantity = $row['ol_qty']; // Auto-assign the main item quantity
    } else {
        echo json_encode(["status" => "error", "message" => "Main item not found or has no quantity"]);
        exit;
    }

    // Fetch free item description
    $query2 = "SELECT items_desc FROM upti_items WHERE items_code = '$freeItem'";
    $result2 = mysqli_query($conn, $query2);

    if ($result2 && mysqli_num_rows($result2) > 0) {
        $row2 = mysqli_fetch_assoc($result2);
        $ol_desc = $row2['items_desc'];
    } else {
        echo json_encode(["status" => "error", "message" => "Free item description not found"]);
        exit;
    }

    // Insert free item into `upti_order_list`
    $sql = "INSERT INTO upti_order_list (ol_poid, ol_desc, ol_date, ol_seller, ol_reseller, ol_code, ol_qty, ol_country, ol_status) 
            VALUES ('$poid', '$ol_desc', '$now', '$ol_seller', '$ol_reseller', '$freeItem', '$quantity', '$ol_country', 'On Order')";
    
    $insert = mysqli_query($conn, $sql);

    if ($insert) {
        echo json_encode(["status" => "success", "message" => "Free item added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add free item"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>

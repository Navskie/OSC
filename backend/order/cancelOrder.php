<?php
include_once '../../database/conn.php';

// Check if 'poid' is provided
if (isset($_POST['poid'])) {
    $poid = $_POST['poid'];

    // Prepare the query to update the order status to 'Canceled'
    $query = "UPDATE upti_transaction SET trans_status = 'Canceled' WHERE trans_poid = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameter to the query
        $stmt->bind_param("s", $poid); // Assuming trans_poid is a string. If it's an integer, use "i"
        
        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Order canceled successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to cancel the order."]);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the query."]);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "No order ID provided."]);
}
?>

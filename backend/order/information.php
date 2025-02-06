<?php
session_start(); // Ensure session is started at the beginning

// Process the form when it's submitted via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store form data in session
    $_SESSION['poid'] = $_POST['poid'];
    $_SESSION['customer_name'] = $_POST['customer_name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['mobile_number'] = $_POST['mobile_number'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['delivery_option'] = $_POST['delivery_option'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['state'] = $_POST['state'];  // Save the state here

    echo json_encode(['success' => true]);  // Respond with success status
    exit();
}
?>

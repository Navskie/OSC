<?php
include_once '../../database/conn.php';

// Kunin ang customer details
$customer = [
    'name' => $_SESSION['customer_name'],
    'mobile' => $_SESSION['mobile_number'],
    'email' => $_SESSION['email'],
    'address' => $_SESSION['address'],
    'country' => $_SESSION['country'],
    'state' => $_SESSION['state'],
];

// Kunin ang order details
$orderDetails = mysqli_query($conn, "SELECT * FROM upti_order_list WHERE ol_poid = '$poid'");
$orders = [];

while ($row = mysqli_fetch_assoc($orderDetails)) {
    $orders[] = $row;
}

// Ibalik ang data bilang JSON
echo json_encode([
    'customer' => $customer,
    'orders' => $orders
]);
?>

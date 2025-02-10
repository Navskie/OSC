<?php
include_once '../../database/conn.php';

// Get the POID from POST request
$poid = $_POST['poid'];

// Calculate the shipping fee based on your logic
$shippingFee = 0.00; // Default value

// Example of how you might calculate the shipping fee based on POID or other logic
$query = "SELECT (SUM(ol_qty) * 185) as shipping_fee 
          FROM upti_order_list 
          INNER JOIN upti_code ON code_name = ol_code 
          WHERE ol_poid = '$poid' AND ol_country = 'PHILIPPINES'
          AND code_category IN ('SPECIAL PROMO', 'PROMO', 'REQUIRED UPSELL')";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $shippingFee = $row['shipping_fee'];
}

// Return the shipping fee
echo json_encode([
  'status' => 'success',
  'shippingFee' => $shippingFee
]);
?>

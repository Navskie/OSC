<?php
  include_once '../../database/conn.php';

  // Query to fetch purchase orders
  $query = "SELECT trans_poid, trans_date, trans_fname, trans_country, trans_ship, trans_subtotal, trans_status 
            FROM upti_transaction 
            WHERE trans_seller = ? AND trans_status NOT IN ('Delivered', 'RTS', 'Canceled', 'On Order')";
  
  // Prepare and execute query
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $users_code);
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch data into an array
  $purchaseOrders = [];
  while($row = $result->fetch_assoc()) {
      $purchaseOrders[] = $row;
  }

  // Return data as JSON
  echo json_encode($purchaseOrders);
?>

<?php
  include_once '../../database/conn.php';

  $query = "SELECT code_category, SUM(ol_qty) as total_qty 
            FROM upti_order_list 
            INNER JOIN upti_code ON code_name = ol_code 
            WHERE ol_poid = '$rspoid' 
            GROUP BY code_category";

  $result = $conn->query($query);
  $orderNotifications = [];

  while ($row = $result->fetch_assoc()) {
      $orderNotifications[] = $row;
  }

  if (empty($orderNotifications)) {
      echo json_encode(["status" => "error", "message" => "No order notifications found"]);
  } else {
      echo json_encode(["status" => "success", "data" => $orderNotifications]);
  }

  $conn->close();
?>

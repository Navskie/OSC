<?php
  include_once '../../database/conn.php';

  // echo $users_code;

  // Define the status array
  $statusArray = ['Pending','On Process', 'In Transit', 'RTS'];
  $salesData = [];

  foreach ($statusArray as $status) {
    $query = "SELECT SUM(ol.ol_php) AS sales FROM upti_order_list AS ol 
              INNER JOIN upti_transaction AS tr ON tr.trans_poid = ol.ol_poid 
              WHERE YEAR(tr.trans_date) = YEAR(CURDATE()) 
              AND tr.trans_status = '$status' 
              AND ol.ol_seller = '$users_code'";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $salesData[$status] = $row['sales'] ?? 0;
  }

  $query2 = "SELECT SUM(ol.ol_php) AS sales 
           FROM upti_order_list AS ol 
           INNER JOIN upti_activities AS ac ON ac.activities_poid = ol.ol_poid 
           INNER JOIN upti_transaction AS tr ON tr.trans_poid = ol.ol_poid 
           WHERE YEAR(ac.activities_date) = YEAR(CURDATE()) 
           AND MONTH(ac.activities_date) = MONTH(CURDATE()) 
           AND ac.activities_caption = 'Order Delivered' 
           AND tr.trans_seller = '$users_code'";


  $result2 = mysqli_query($conn, $query2);
  $row2 = mysqli_fetch_assoc($result2);
  $deliveredSales = $row2['sales'] ?? 0;

  $query = "SELECT SUM(ol.ol_php) AS today_sales 
            FROM upti_order_list AS ol 
            INNER JOIN upti_transaction AS tr ON tr.trans_poid = ol.ol_poid 
            WHERE DATE(trans_date) = CURDATE() 
            AND tr.trans_status IN ('Pending', 'On Process', 'In Transit') 
            AND tr.trans_seller = '$users_code'";

  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $todaySales = $row['today_sales'] ?? 0;

  // Return all data in a JSON format
  echo json_encode([
      'userId' => $users_code,
      'salesData' => $salesData,
      'deliveredSales' => $deliveredSales,
      'todaySales' => $todaySales
  ]);
?>

<?php
include_once '../../database/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ensure required POST data is set
  if (!isset($_POST['item_code'], $_POST['quantity'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit();
  }

  $ol_code = $_POST['item_code'];
  $ol_qty = (int) $_POST['quantity'];
  $ol_status = 'On Order';
  $ol_seller = $users_code ?? '';
  $ol_reseller = $users_creator ?? '';
  $ol_state = '';
  $ol_country = 'PHILIPPINES';

  // Fetch item description and points
  $stmt = $conn->prepare("SELECT items_desc, items_points FROM upti_items WHERE items_code = ? UNION SELECT package_desc, package_points FROM upti_package WHERE package_code = ?");
  $stmt->bind_param("ss", $ol_code, $ol_code);
  $stmt->execute();
  $result = $stmt->get_result();
  $getDesc = $result->fetch_assoc();
  $stmt->close();

  if (!$getDesc) {
    echo json_encode(["status" => "error", "message" => "Item not found."]);
    exit();
  }

  $ol_desc = $getDesc['items_desc'];
  $ol_points = $getDesc['items_points'];

  // Fetch item price
  $stmt = $conn->prepare("SELECT country_php, country_price FROM upti_country WHERE country_code = ? AND country_name = ?");
  $stmt->bind_param("ss", $ol_code, $ol_country);
  $stmt->execute();
  $result = $stmt->get_result();
  $priceFetch = $result->fetch_assoc();
  $stmt->close();

  if (!$priceFetch) {
    echo json_encode(["status" => "price", "message" => "Price information not found."]);
    exit();
  }

  $ol_php = $priceFetch['country_php'] * $ol_qty;
  $ol_price = $priceFetch['country_price'];
  $ol_subtotal = $ol_price * $ol_qty;

  // Fetch state territory
  $stmt = $conn->prepare("SELECT state_territory FROM upti_state WHERE state_name = ? AND state_country = ?");
  $stmt->bind_param("ss", $ol_state, $ol_country);
  $stmt->execute();
  $result = $stmt->get_result();
  $getStateFetch = $result->fetch_assoc();
  $stmt->close();

  $territory = $getStateFetch['state_territory'] ?? 'TERRITORY 1';

  // Fetch main item code
  $stmt = $conn->prepare("SELECT code_main FROM upti_code WHERE code_name = ?");
  $stmt->bind_param("s", $ol_code);
  $stmt->execute();
  $result = $stmt->get_result();
  $getCodeFetch = $result->fetch_assoc();
  $stmt->close();

  $code_main = $getCodeFetch['code_main'] ?? '';

  // Check inventory
  if (!empty($code_main)) {
    $stmt = $conn->prepare("SELECT si_item_stock FROM stockist_inventory WHERE si_item_code = ? AND si_item_country = ? AND si_item_role = ?");
    $stmt->bind_param("sss", $code_main, $ol_country, $territory);
    $stmt->execute();
    $result = $stmt->get_result();
    $checkSingleStocksFetch = $result->fetch_assoc();
    $stmt->close();

    $si_item_stocks = $checkSingleStocksFetch['si_item_stock'] ?? 0;

    if ($ol_qty > $si_item_stocks) {
      echo json_encode(["status" => "stock", "message" => "Not Enough Stock"]);
      exit();
    }
  } else {
    $stmt = $conn->prepare("SELECT p_s_code, p_s_qty FROM upti_pack_sett WHERE p_s_main = ?");
    $stmt->bind_param("s", $ol_code);
    $stmt->execute();
    $bundleResult = $stmt->get_result();
    $stmt->close();

    while ($data = $bundleResult->fetch_assoc()) {
      $codeName = $data['p_s_code'];
      $qty_sum = $data['p_s_qty'] * $ol_qty;

      $stmt = $conn->prepare("SELECT si_item_stock FROM stockist_inventory WHERE si_item_code = ? AND si_item_country = ? AND si_item_role = ?");
      $stmt->bind_param("sss", $codeName, $ol_country, $territory);
      $stmt->execute();
      $result = $stmt->get_result();
      $checkSingleStocksFetch = $result->fetch_assoc();
      $stmt->close();

      $si_item_stocks = $checkSingleStocksFetch['si_item_stock'] ?? 0;

      if ($qty_sum > $si_item_stocks) {
        echo json_encode(["status" => "stock", "message" => "Not Enough Stock"]);
        exit();
      }
    }
  }

  // Check if order already exists
  $stmt = $conn->prepare("SELECT ol_points, ol_price, ol_php, ol_subtotal, ol_qty FROM upti_order_list WHERE ol_poid = ? AND ol_code = ?");
  $stmt->bind_param("ss", $poid, $ol_code);
  $stmt->execute();
  $result = $stmt->get_result();
  $existingOrder = $result->fetch_assoc();
  $stmt->close();

  if ($existingOrder) {
    // Update existing order
    $new_qty = $existingOrder['ol_qty'] + $ol_qty;
    $new_points = $existingOrder['ol_points'] + $ol_points;
    $new_price = $existingOrder['ol_price'] + $ol_price;
    $new_php = $existingOrder['ol_php'] + $ol_php;
    $new_subtotal = $existingOrder['ol_subtotal'] + $ol_subtotal;

    $stmt = $conn->prepare("UPDATE upti_order_list SET ol_qty = ?, ol_points = ?, ol_php = ?, ol_subtotal = ? WHERE ol_poid = ? AND ol_code = ?");
    $stmt->bind_param("iiiiss", $new_qty, $new_points, $new_php, $new_subtotal, $poid, $ol_code);

    if ($stmt->execute()) {
      echo json_encode(["status" => "update", "message" => "Order updated successfully!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Failed to update order."]);
    }
  } else {
    // Insert new order
    $stmt = $conn->prepare("INSERT INTO upti_order_list (ol_poid, ol_desc, ol_date, ol_seller, ol_reseller, ol_points, ol_code, ol_qty, ol_php, ol_price, ol_country, ol_subtotal, ol_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisiddsds", $poid, $ol_desc, $now, $ol_seller, $ol_reseller, $ol_points, $ol_code, $ol_qty, $ol_php, $ol_price, $ol_country, $ol_subtotal, $ol_status);

    if ($stmt->execute()) {
      echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Failed to place order."]);
    }
  }

  $stmt->close();
}

$conn->close();
?>

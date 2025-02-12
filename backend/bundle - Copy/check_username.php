<?php
  // check_username.php
  include_once '../../database/conn.php';

  // Get the username from the POST request
  $username = $_POST['username'];

  // Query to check if the username exists
  $query = "SELECT * FROM upti_users WHERE users_username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // If a row is found, the username exists
  if($result->num_rows > 0) {
    echo 'exists';
  } else {
    echo 'available';
  }

  $stmt->close();
  $conn->close();
?>

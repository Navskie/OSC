<?php
// Include database connection
include_once '../../database/conn.php';

// Get POST data
$poid = $_POST['poid'];
$remark_content = $_POST['remark_content'];

// Check if remark content is not empty
if (!empty($remark_content)) {
  // Assuming remark_name is coming from the session or as an input
  $remark_name = $users_name;  // Replace with dynamic user data
  
  // Get current date and time
  $remark_date = date('Y-m-d');
  $remark_time = date('H:i:s');

  // Escape the remark content to prevent SQL injection
  $old_remark_content = mysqli_real_escape_string($conn, $remark_content);

  $remark_content = urldecode($old_remark_content);

  // Insert the new remark into the database
  $query = "INSERT INTO upti_remarks (remark_poid, remark_name, remark_content, remark_date, remark_time, remark_csr)
            VALUES ('$poid', '$remark_name', '$remark_content', '$remark_date', '$remark_time', 'Unread')";
  
  if (mysqli_query($conn, $query)) {
    echo "Remark added successfully!";
  } else {
    echo "Error adding remark: " . mysqli_error($conn);
  }
} else {
  echo "Remark content is required!";
}
?>

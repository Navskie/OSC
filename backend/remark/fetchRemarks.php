<?php
// Include database connection
include_once '../../database/conn.php';

// Get POID from the GET request
$poid = $_GET['poid'];

// Query to fetch remarks
$query = "SELECT * FROM upti_remarks WHERE remark_poid = '$poid' ORDER BY remark_date DESC, remark_time DESC";
$result = mysqli_query($conn, $query);

// Check if there are remarks
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $remark_name = $row['remark_name'];
    $remark_content = $row['remark_content'];
    $remark_date = $row['remark_date'];
    $remark_time = $row['remark_time'];

    $date = $remark_date . ' ' . $remark_time;
    $dateObj = new DateTime($date);
    $formattedDate = $dateObj->format('M j, Y g:iA');  // Format date

    echo "
      <div class='d-flex align-items-start text-dark mb-4'>
        <div class='flex-shrink-0 me-3'>
          <img src='assets/img/default.png' class='avatar-sm rounded' alt='...'>
        </div>
        <div class='flex-grow-1'>
          <h5 class='fs-14'>$remark_name</h5>
          $remark_content
          <br>
          <i class='fs-9 text-muted'>$formattedDate</i>
        </div>
      </div>
    ";
  }
} else {
  echo "<p>No remarks available.</p>";
}
?>

<?php 
// Database Configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uptimisedph';

// Establish Secure Database Connection
$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]));
}

// Start Session
session_start();

// Check if session is set
if (!isset($_SESSION['code']) || empty($_SESSION['code'])) {
    die(json_encode(["error" => "User not logged in"]));
}

$users_code = $_SESSION['code'];

// Set Timezone & Format Time Properly
date_default_timezone_set('Asia/Manila');
$now = date('Y-m-d');
$timenow = date('H:i:s'); // Fixed incorrect minutes format (h:m:s ➝ H:i:s)
$timedate = $now . ' - ' . $timenow;

// Secure Query with Prepared Statement
$stmt = $conn->prepare("SELECT users_id, users_name, users_count, users_creator FROM upti_users WHERE users_code = ?");
$stmt->bind_param("s", $users_code);
$stmt->execute();
$result = $stmt->get_result();

// Fetch User Data Securely
if ($result->num_rows > 0) {
    $users_execute = $result->fetch_assoc();
    $users_name = $users_execute['users_name'];
    $users_creator = $users_execute['users_creator'];
    $users_id = $users_execute['users_id'];
    $users_count = $users_execute['users_count'];
} else {
    die(json_encode(["error" => "User not found"]));
}

$poid = 'PD'.$users_id.'-'.$users_count;
$rspoid = 'RS'.$users_id.'-'.$users_count;

// Close Statement
$stmt->close();
?>

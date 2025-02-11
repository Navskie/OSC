<?php
session_start(); // Ensure session is started at the beginning

// Define the upload directory
$uploadDirectory = '../../../UptimisedCorp/images/address/';

// Create the directory if it doesn't exist
if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

// Process the form when it's submitted via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form data and store it in the session
    $_SESSION['rspoid'] = $_POST['rspoid'];
    $_SESSION['rscustomer_name'] = $_POST['rscustomer_name'];
    $_SESSION['rsemail'] = $_POST['rsemail'];
    $_SESSION['rsmobile_number'] = $_POST['rsmobile_number'];
    $_SESSION['rsdelivery_option'] = $_POST['rsdelivery_option'];
    $_SESSION['rscountry'] = $_POST['rscountry'];
    $_SESSION['rsstate'] = $_POST['rsstate'];

    // Check if the address is provided when no image is uploaded
    if (empty($_POST['address']) && (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== 0)) {
        echo json_encode(['success' => false, 'message' => 'Please add an address or upload an image.']);
        exit();
    }


    $_SESSION['rsaddress'] = $_POST['address'];  // Store the address in session

    // Handle image upload if a file is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDirectory . $fileName;

        // If there was an old image, delete it
        if (isset($_SESSION['rsuploaded_image']) && file_exists($_SESSION['rsuploaded_image'])) {
            unlink($_SESSION['rsuploaded_image']);
        }

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Save the file path (or filename) in the session
            $_SESSION['rsuploaded_image'] = $fileName;

            // Respond with success status
            echo json_encode(['success' => true, 'message' => 'Information saved successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload the image.']);
        }
    } else {
        // If no image is uploaded, just respond with success for form data
        echo json_encode(['success' => true, 'message' => 'Information saved successfully without image.']);
    }
    exit();
}
?>

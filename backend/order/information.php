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
    $_SESSION['poid'] = $_POST['poid'];
    $_SESSION['customer_name'] = $_POST['customer_name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['mobile_number'] = $_POST['mobile_number'];
    $_SESSION['delivery_option'] = $_POST['delivery_option'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['state'] = $_POST['state'];

    // Check if the address is provided when no image is uploaded
    if (empty($_POST['address']) && (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== 0)) {
        echo json_encode(['success' => false, 'message' => 'Please add an address or upload an image.']);
        exit();
    }


    $_SESSION['address'] = $_POST['address'];  // Store the address in session

    // Handle image upload if a file is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDirectory . $fileName;

        // If there was an old image, delete it
        if (isset($_SESSION['uploaded_image']) && file_exists($_SESSION['uploaded_image'])) {
            unlink($_SESSION['uploaded_image']);
        }

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Save the file path (or filename) in the session
            $_SESSION['uploaded_image'] = $fileName;

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

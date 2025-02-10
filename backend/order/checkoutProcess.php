<?php
include_once '../../database/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['paymentMethod'];
    $agreeStatement = isset($_POST['agreeStatement']) ? 'Agreed' : '';
    $shippingFee = isset($_POST['shippingFee']);

    // Initialize variables for receipt image and address image
    $receiptImage = null;

    // Handle receipt image upload (same as before)
    if (isset($_FILES['receiptImage']) && $_FILES['receiptImage']['error'] == 0) {
        $uploadDirReceipt = '../../../UptimisedCorp/images/payment/';
        $fileNameReceipt = $_FILES['receiptImage']['name'];
        $fileTmpNameReceipt = $_FILES['receiptImage']['tmp_name'];
        $fileTypeReceipt = $_FILES['receiptImage']['type'];
        $fileSizeReceipt = $_FILES['receiptImage']['size'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileTypeReceipt, $allowedTypes) && $fileSizeReceipt <= 5000000) {
            $newFileNameReceipt = time() . '-' . basename($fileNameReceipt);
            $uploadPathReceipt = $uploadDirReceipt . $newFileNameReceipt;

            if (move_uploaded_file($fileTmpNameReceipt, $uploadPathReceipt)) {
                $receiptImage = $newFileNameReceipt;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload the receipt image.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid receipt file type or file size too large.']);
            exit;
        }
    }

    // Other form fields...
    $subtotal = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;
    $shipping = isset($_POST['shipping']) ? $_POST['shipping'] : 0;
    $ol_seller = $users_code;
    $ol_reseller = $users_creator;
    $fullName = $_SESSION['customer_name'];
    $number = $_SESSION['mobile_number'];
    $email = $_SESSION['email'];
    $address = $_SESSION['address'];
    $country = $_SESSION['country'];
    $office = $_SESSION['delivery_option'];
    $state = $_SESSION['state'];

    // Insert transaction details into the database
    $sql = "INSERT INTO upti_transaction (`trans_date`, `trans_time`, `trans_seller`, `trans_my_reseller`, `trans_admin`, `trans_poid`, `trans_subtotal`, `trans_ship`, `trans_mop`, `trans_img`, `trans_fname`, `trans_contact`, `trans_email`, `trans_address`, `trans_country`, `trans_status`, `trans_office`, `trans_office_status`, `trans_state`, `trans_terms`, `trans_remarks`)
            VALUES ('$now', '$timenow', '$ol_seller', '$ol_reseller', 'UPTIMAIN', '$poid', '$subtotal', '$shippingFee', '$paymentMethod', '$receiptImage', '$fullName', '$number', '$email', '$address', '$country', 'Pending', '$office', '$agreeStatement', '$state', '$agreeStatement', 'REGULAR')";

    if (mysqli_query($conn, $sql)) {
        // Clear session data after successful transaction
        unset(
            $_SESSION['poid'],
            $_SESSION['seller_name'],
            $_SESSION['reseller_name'],
            $_SESSION['customer_name'],
            $_SESSION['mobile_number'],
            $_SESSION['email'],
            $_SESSION['address'],
            $_SESSION['country'],
            $_SESSION['delivery_option'],
            $_SESSION['state'],
        );

        // Update user count
        $newCount = $users_count + 1;
        $updateCount = mysqli_query($conn, "UPDATE upti_users SET users_count = '$newCount' WHERE users_code = '$users_code'");

        echo json_encode(['status' => 'success', 'message' => 'Checkout completed successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to process the checkout.']);
    }
}
?>

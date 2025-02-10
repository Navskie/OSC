<?php
include_once '../../database/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['paymentMethod'];
    $agreeStatement = isset($_POST['agreeStatement']) ? 'Agreed' : '';

    $receiptImage = null;
    if (isset($_FILES['receiptImage']) && $_FILES['receiptImage']['error'] == 0) {
        $uploadDir = '../../../UptimisedCorp/images/payment/';
        $fileName = $_FILES['receiptImage']['name'];
        $fileTmpName = $_FILES['receiptImage']['tmp_name'];
        $fileType = $_FILES['receiptImage']['type'];
        $fileSize = $_FILES['receiptImage']['size'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes) && $fileSize <= 5000000) {
            $newFileName = time() . '-' . basename($fileName);
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                $receiptImage = $newFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload the receipt image.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type or file size too large.']);
            exit;
        }
    }

    $subtotal = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;
    $shipping = isset($_POST['shipping']) ? $_POST['shipping'] : 0;
    $ol_seller = $_SESSION['ol_seller'];
    $ol_reseller = $_SESSION['ol_reseller'];
    $fullName = $_SESSION['customer_name'];
    $number = $_SESSION['mobile_number'];
    $email = $_SESSION['email'];
    $address = $_SESSION['address'];
    $country = $_SESSION['country'];
    $office = $_SESSION['delivery_option'];
    $state = $_SESSION['state'];
    $uploadAddress = $_SESSION['address_two'];

    $sql = "INSERT INTO upti_transaction (`trans_date`, `trans_time`, `trans_seller`, `trans_my_reseller`, `trans_admin`, `trans_poid`, `trans_subtotal`, `trans_ship`, `trans_mop`, `trans_img`, `trans_fname`, `trans_contact`, `trans_email`, `trans_address`, `trans_country`, `trans_status`, `trans_office`, `trans_office_status`, `trans_state`, `trans_terms`, `trans_remarks`, `trans_addresstwo`)
            VALUES ('$now', '$timenow', '$ol_seller', '$ol_reseller', 'UPTIMAIN', '$poid', '$subtotal', '$shipping', '$paymentMethod', '$receiptImage', '$fullName', '$number', '$email', '$address', '$country', 'Pending', '$office', '$agreeStatement', '$state', '$agreeStatement', 'REGULAR', '$uploadAddress')";

    if (mysqli_query($conn, $sql)) {
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
          $_SESSION['address_two']
        );

        $newCount = $users_count + 1;
        $updateCount = mysqli_query($conn, "UPDATE upti_users SET users_count = '$newCount' WHERE users_code = '$users_code'");

        echo json_encode(['status' => 'success', 'message' => 'Checkout completed successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to process the checkout.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>

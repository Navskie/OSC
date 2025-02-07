<?php
include_once '../../database/conn.php';

if (isset($_GET['maincode'])) {
    $maincode = mysqli_real_escape_string($conn, $_GET['maincode']);

    // SQL Query to get the free items based on the maincode
    $query = "
        SELECT freecode, items_desc as freedesc
        FROM upti_free INNER JOIN upti_items ON items_code = freecode
        WHERE maincode = '$maincode'
    ";

    $result = mysqli_query($conn, $query);
    
    $freeItems = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $freeItems[] = [
            'freecode' => $row['freecode'],
            'freedesc' => $row['freedesc']
        ];
    }

    if ($freeItems) {
        echo json_encode([
            'status' => 'success',
            'data' => $freeItems
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No free items found for this main item code.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Maincode is required.'
    ]);
}
?>

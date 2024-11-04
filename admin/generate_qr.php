<?php
// Include the PHP QR Code library
include('../phpqrcode/qrlib.php'); // Adjust the path if needed

// Make sure required parameters are received
if (isset($_POST['event']) && isset($_POST['name'])) {
    $event = $_POST['event'];
    $name = $_POST['name'];

    // Generate QR Code data
    $qrText = "Event: $event\nName: $name";
    
    // Path to store the QR code image
    $fileName = md5(uniqid()) . ".png"; // Unique file name
    $filePath = "../qrcodes/" . $fileName;

    // Check if the directory exists and is writable
    if (!file_exists('../qrcodes/')) {
        mkdir('../qrcodes/', 0777, true); // Create the folder if it doesn't exist
    }

    // Generate the QR Code image with white background and blue QR
    QRcode::png($qrText, $filePath, QR_ECLEVEL_L, 10, 2, true, [255, 255, 255], [0, 0, 255]);

    // Return the path of the generated QR code
    echo json_encode([
        'status' => 'success',
        'filePath' => $filePath
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required data.'
    ]);
}

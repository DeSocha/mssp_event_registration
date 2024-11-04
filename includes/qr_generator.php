<?php
require_once('../phpqrcode/qrlib.php');

// Check if event and name are provided
if (isset($_GET['event']) && isset($_GET['name'])) {
    $data = "Event: " . $_GET['event'] . "\nName: " . $_GET['name'];

    // Path to store the QR code
    $qrCodePath = '../qrcodes/';

    // Ensure the directory exists
    if (!is_dir($qrCodePath)) {
        mkdir($qrCodePath, 0777, true); // Create directory if it doesn't exist
    }

    // Generate a unique filename
    $fileName = uniqid() . '.png';
    $filePath = $qrCodePath . $fileName;

    // Generate QR code with blue code and white background
    $backgroundColor = [255, 255, 255]; // White background
    $foregroundColor = [0, 0, 255]; // Blue code

    QRcode::png($data, $filePath, 'L', 10, 2, false, $backgroundColor, $foregroundColor);

    echo $filePath; // Return the path to the generated QR code
}

?>
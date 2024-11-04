<?php
// Include the QR code library
include('../phpqrcode/qrlib.php');

// Function to generate a QR code with a blue foreground and white background
function generateQRCode($data) {
    // Path to save the generated QR code image
    $qrFile = '../qrcodes/' . uniqid() . '.png';
    
    // QR code parameters
    $qrText = $data;
    $errorCorrectionLevel = 'L'; // Error correction level
    $matrixPointSize = 10; // Size of the QR code
    
    // Generate the default QR code (black and white)
    QRcode::png($qrText, $qrFile, $errorCorrectionLevel, $matrixPointSize, 2);

    // Load the generated QR code image
    $qrImage = imagecreatefrompng($qrFile);

    // Allocate custom colors (white background and blue foreground)
    $white = imagecolorallocate($qrImage, 255, 255, 255);
    $blue = imagecolorallocate($qrImage, 0, 0, 255);

    // Replace black pixels with blue and transparent with white
    for ($y = 0; $y < imagesy($qrImage); $y++) {
        for ($x = 0; $x < imagesx($qrImage); $x++) {
            $pixelColor = imagecolorat($qrImage, $x, $y);
            if ($pixelColor == 0) { // Black pixels (0 is black in PNG)
                imagesetpixel($qrImage, $x, $y, $blue);
            }
        }
    }

    // Save the final image as a new file with blue QR code and white background
    imagepng($qrImage, $qrFile);
    imagedestroy($qrImage);

    // Return the file path of the generated QR code
    return $qrFile;
}

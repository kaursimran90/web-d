<?php
session_start();

// Check if GD library is installed
if (!extension_loaded('gd')) {
    die("GD library is not enabled.");
}

// Generate a random 6-digit number for the CAPTCHA
$captchaNumber = strval(rand(100000, 999999));

// Store the CAPTCHA number in the session to verify later
$_SESSION['captcha'] = $captchaNumber;

// Set the content type header to PNG, so the browser knows it's an image
header('Content-Type: image/png');

// Create the image canvas
$imageWidth = 150;
$imageHeight = 50;
$image = imagecreatetruecolor($imageWidth, $imageHeight);

// Set colors
$backgroundColor = imagecolorallocate($image, 255, 255, 255); // White
$textColor = imagecolorallocate($image, 0, 128, 0); // Green

// Fill the background
imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $backgroundColor);

// Add the CAPTCHA text
$fontSize = 5; // Built-in font size
$xPosition = ($imageWidth / 2) - (strlen($captchaNumber) * imagefontwidth($fontSize) / 2);
$yPosition = ($imageHeight / 2) - (imagefontheight($fontSize) / 2);
imagestring($image, $fontSize, $xPosition, $yPosition, $captchaNumber, $textColor);

// Output the image
imagepng($image);

// Free memory
imagedestroy($image);
?>

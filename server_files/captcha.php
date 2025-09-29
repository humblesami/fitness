<?php
session_start();

// Generate a random CAPTCHA text (6 characters)
$captcha_text = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 6);
$_SESSION['captcha_text'] = $captcha_text;

// Create image
$width = 150;
$height = 50;
$image = imagecreate($width, $height);

// Colors
$bg_color = imagecolorallocate($image, 255, 255, 255); // white background
$text_color = imagecolorallocate($image, rand(0,120), rand(0,120), rand(0,120));
$line_color = imagecolorallocate($image, rand(150,255), rand(150,255), rand(150,255));

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Add noise lines
for($i = 0; $i < 5; $i++){
    imageline($image, rand(0,$width), rand(0,$height), rand(0,$width), rand(0,$height), $line_color);
}

// Add random dots for extra noise
for($i = 0; $i < 100; $i++){
    imagesetpixel($image, rand(0,$width), rand(0,$height), $line_color);
}

// Add CAPTCHA text
$font = __DIR__ . "/arial.ttf"; // Make sure arial.ttf exists in the same folder
imagettftext($image, 22, rand(-10,10), 15, 35, $text_color, $font, $captcha_text);

// Output the image
header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>

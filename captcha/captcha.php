<?php

header('Content-type: image/png');

session_start();

$image = imagecreate(120, 45);

imagecolorallocate($image, 0, 0, 0);

$font = __DIR__ . '/RubikBeastly-Regular.ttf';

$txt_color = imagecolorallocate($image, 173, 255, 47);

$text = bin2hex(random_bytes(3));

$_SESSION['captcha'] = $text;

imagettftext($image, 20, 0, 5, 35, $txt_color, $font, $text);

imagepng($image);

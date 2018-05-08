<?php

use chillerlan\QRCode\QRCode;

$loader = require 'vendor/autoload.php';
// $loader->add('chillerlan\QRCode', __DIR__.'/vendor/chillerlan/php-qrcode/src/');
//use chillerlan\php-qrcode\QRCode;

$data = 'https://www.youtube.com/watch?v=DLzxrzFCyOs&t=43s';
echo '<img src="'.(new QRCode)->render($data).'" />';

<?
$im = imagecreate(190, 50);
$bkg = imagecolorallocate($im, 255, 255, 255);
$txt = imagecolorallocate($im, 0, 0, 0);
$txt2 = imagecolorallocate($im, 246, 0, 0);
$font = "resources/abscii__.ttf";
imagettftext($im, 25, 0, 40, 35, $txt2, $font, $_GET['napis']);
imagettftext($im, 25, 0, 38, 33, $txt, $font, $_GET['napis']);
header("Content-type: image/jpeg");
imagejpeg($im);
imagedestroy($im);
?>
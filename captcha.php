<?php
session_start();    

header("Expires: Tue, 01 Jan 2013 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$randomString = '';

for ($i = 0; $i < 5; $i++)
{
	$randomString .= rand(0,9);
	//$randomString .= $chars[rand(0, strlen($chars)-1)];
}	

$_SESSION['OEPL']['captcha'] = $randomString;
$im = @imagecreatefrompng("image/45-degree-fabric.png");
imagettftext($im , 30, 0, 10, 38, imagecolorallocate ($im, 0, 0, 0),'fonts/times_new_yorker.ttf', $randomString);
header ('Content-type: image/png');
imagepng($im, NULL, 0);
imagedestroy($im);
?>
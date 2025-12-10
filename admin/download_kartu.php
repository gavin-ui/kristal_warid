<?php
$nomor = $_GET['nomor'];
$path = "../uploads/qrcode/" . $nomor . ".png";

header("Content-Type: image/png");
header("Content-Disposition: attachment; filename=kartu_$nomor.png");
readfile($path);
exit;
?>

<?php
ob_start();
include "../koneksi.php";

/* ===============================
   VALIDASI ID
================================ */
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Content-Type: text/plain");
    exit("ID tidak ditemukan");
}

/* ===============================
   AMBIL DATA
================================ */
$q = $conn->prepare("SELECT * FROM karyawan WHERE id_karyawan=? LIMIT 1");
$q->bind_param("i", $id);
$q->execute();
$r = $q->get_result()->fetch_assoc();

if (!$r) {
    header("Content-Type: text/plain");
    exit("Data tidak ditemukan");
}

$nama   = trim($r['nama_karyawan']);
$divisi = trim($r['divisi']);
$alamat = trim($r['alamat']);

$foto = "../uploads/karyawan/" . $r['foto_karyawan'];
$qr   = "../uploads/qrcode/" . $r['barcode'];

/* ===============================
   KANVAS
================================ */
$w = 560;
$h = 320;
$img = imagecreatetruecolor($w, $h);

/* WARNA */
$white = imagecolorallocate($img, 255,255,255);
$blue  = imagecolorallocate($img, 31,78,140);
$black = imagecolorallocate($img, 0,0,0);

imagefilledrectangle($img, 0, 0, $w, $h, $white);
imagefilledrectangle($img, 0, 0, $w, 36, $blue);

/* ===============================
   FONT
================================ */
$font = __DIR__ . "/../font/DejaVuSans.ttf";
$useTTF = file_exists($font) && function_exists('imagettftext');

/* ===============================
   JUDUL
================================ */
if ($useTTF) {
    imagettftext($img, 15, 0, 180, 24, $white, $font, "KARTU KARYAWAN");
} else {
    imagestring($img, 5, 200, 12, "KARTU KARYAWAN", $white);
}

/* ===============================
   FOTO
================================ */
if (file_exists($foto)) {
    $src = imagecreatefromstring(file_get_contents($foto));
    $dst = imagecreatetruecolor(95, 125);

    imagecopyresampled(
        $dst, $src,
        0,0,0,0,
        95,125,
        imagesx($src),
        imagesy($src)
    );

    imagecopy($img, $dst, 14, 60, 0, 0, 95, 125);
}

/* ===============================
   TEKS (AREA KIRI)
================================ */
$x = 120;
$y = 70;
$maxTextWidth = 260;

function drawText($img, $text, $x, &$y, $color, $font, $useTTF, $size = 11) {
    if ($useTTF) {
        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
    } else {
        imagestring($img, 3, $x, $y - 12, $text, $color);
    }
    $y += 18;
}

drawText($img, "Nama   : $nama",   $x, $y, $black, $font, $useTTF);
drawText($img, "Divisi : $divisi", $x, $y, $black, $font, $useTTF);
drawText($img, "Alamat :",         $x, $y, $black, $font, $useTTF);

/* ALAMAT WRAP (ANTI NIMPA QR) */
$alamatWrap = wordwrap($alamat, 30, "\n", true);
$lines = explode("\n", $alamatWrap);

foreach ($lines as $l) {
    drawText($img, $l, $x, $y, $black, $font, $useTTF, 9);
}

/* ===============================
   QR CODE (HD TAPI AMAN)
================================ */
if (file_exists($qr)) {
    $qrSrc = imagecreatefrompng($qr);

    $qrSize = 180; // HD tapi tidak menimpa teks
    $qrDst  = imagecreatetruecolor($qrSize, $qrSize);

    imagecopyresampled(
        $qrDst, $qrSrc,
        0,0,0,0,
        $qrSize, $qrSize,
        imagesx($qrSrc),
        imagesy($qrSrc)
    );

    imagecopy(
        $img,
        $qrDst,
        $w - $qrSize - 20,
        90,
        0,0,
        $qrSize,
        $qrSize
    );
}

/* ===============================
   OUTPUT
================================ */
ob_clean();
header("Content-Type: image/png");
header("Content-Disposition: inline; filename=kartu_" . preg_replace('/[^a-zA-Z0-9]/','_', $nama) . ".png");

imagepng($img);
imagedestroy($img);
exit;

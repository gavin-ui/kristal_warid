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
   AMBIL DATA KARYAWAN
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
$w = 520;
$h = 300;
$img = imagecreatetruecolor($w, $h);

/* WARNA */
$white = imagecolorallocate($img, 255,255,255);
$blue  = imagecolorallocate($img, 31,78,140);
$black = imagecolorallocate($img, 0,0,0);

imagefilledrectangle($img, 0, 0, $w, $h, $white);
imagefilledrectangle($img, 0, 0, $w, 32, $blue);

/* ===============================
   FONT
================================ */
$font = __DIR__ . "/../font/DejaVuSans.ttf";
$useTTF = file_exists($font) && function_exists('imagettftext');

/* ===============================
   JUDUL
================================ */
if ($useTTF) {
    imagettftext($img, 14, 0, 160, 22, $white, $font, "KARTU KARYAWAN");
} else {
    imagestring($img, 5, 180, 10, "KARTU KARYAWAN", $white);
}

/* ===============================
   FOTO KARYAWAN
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

    imagecopy($img, $dst, 10, 50, 0, 0, 95, 125);
}

/* ===============================
   TEKS DATA (PASTI MUNCUL)
================================ */
$x = 120;
$y = 60;

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

/* ALAMAT MULTI BARIS */
$lines = explode("\n", wordwrap($alamat, 22, "\n", true));
foreach ($lines as $l) {
    drawText($img, $l, $x, $y, $black, $font, $useTTF, 9);
}

/* ===============================
   QR CODE (ASLI — NO RESIZE)
================================ */
if (file_exists($qr)) {
    $qrSrc = imagecreatefrompng($qr);
    $qrW = imagesx($qrSrc);
    $qrH = imagesy($qrSrc);

    imagecopy(
        $img,
        $qrSrc,
        $w - $qrW - 20,
        80,
        0, 0,
        $qrW,
        $qrH
    );
}

/* ===============================
   OUTPUT PNG (ANTI WORD)
================================ */
ob_clean();
header("Content-Type: image/png");
header("Content-Disposition: inline; filename=kartu_" . preg_replace('/[^a-zA-Z0-9]/','_', $nama) . ".png");

imagepng($img);
imagedestroy($img);
exit;

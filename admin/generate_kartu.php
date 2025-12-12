<?php
include "../koneksi.php";
require_once __DIR__ . "/../phpqrcode/qrlib.php";

/* ==========================================
   FIX: BERSIHKAN SEMUA OUTPUT BUFFER
========================================== */
if (ob_get_level()) {
    while (ob_get_level()) ob_end_clean();
}

/* ==========================================
   GET ID VALID
========================================== */
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Content-Type: text/plain");
    die("ID tidak ditemukan");
}

/* ==========================================
   AMBIL DATA KARYAWAN
========================================== */
$q = $conn->prepare("SELECT * FROM karyawan WHERE id_karyawan=? LIMIT 1");
$q->bind_param("i", $id);
$q->execute();
$r = $q->get_result()->fetch_assoc();

if (!$r) {
    header("Content-Type: text/plain");
    die("Data tidak ditemukan");
}

/* ==========================================
   SET DATA
========================================== */
$nama   = $r['nama_karyawan'];
$nomor  = $r['nomor_karyawan'];   // FIX — nomor harus ada
$divisi = $r['divisi'];
$alamat = $r['alamat'];
$foto   = "../uploads/karyawan/" . $r['foto_karyawan'];
$qr     = "../uploads/qrcode/" . $r['barcode'];

/* ==========================================
   BUAT KANVAS GAMBAR
========================================== */
$w = 320;
$h = 200;
$img = imagecreatetruecolor($w, $h);

/* ==========================================
   WARNA
========================================== */
$white = imagecolorallocate($img, 255, 255, 255);
$blue  = imagecolorallocate($img, 31, 78, 140);
$black = imagecolorallocate($img, 0, 0, 0);

imagefilledrectangle($img, 0, 0, $w, $h, $white);

/* ==========================================
   HEADER BIRU
========================================== */
imagefilledrectangle($img, 0, 0, $w, 32, $blue);

/* ==========================================
   FONT
========================================== */
$font = __DIR__ . "/../font/arial.ttf";
if (!file_exists($font)) {
    $font = __DIR__ . "/../font/DejaVuSans.ttf"; // fallback
}
if (!file_exists($font)) $font = false;

/* ==========================================
   JUDUL HEADER
========================================== */
if ($font)
    imagettftext($img, 14, 0, 80, 22, $white, $font, "KARTU KARYAWAN");
else
    imagestring($img, 5, 80, 12, "KARTU KARYAWAN", $white);

/* ==========================================
   FOTO KARYAWAN
========================================== */
if (file_exists($foto)) {

    $src = imagecreatefromstring(file_get_contents($foto));

    $fw = 95;
    $fh = 125;

    $dst = imagecreatetruecolor($fw, $fh);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $fw, $fh, imagesx($src), imagesy($src));

    imagecopy($img, $dst, 10, 50, 0, 0, $fw, $fh);
}

/* ==========================================
   TULIS TEKS
========================================== */
$y = 60;

$write = function($t) use (&$y, $img, $black, $font) {
    if ($font)
        imagettftext($img, 11, 0, 120, $y, $black, $font, $t);
    else
        imagestring($img, 3, 120, $y - 12, $t, $black);

    $y += 20;
};

$write($nama);
$write("Nomor : $nomor");
$write("Divisi : $divisi");
$write("Alamat :");

/* ==========================================
   ALAMAT MULTI-LINE
========================================== */
$alamat_lines = explode("\n", wordwrap($alamat, 25, "\n", true));

foreach ($alamat_lines as $line) {
    if ($font)
        imagettftext($img, 9, 0, 120, $y, $black, $font, $line);
    else
        imagestring($img, 2, 120, $y - 10, $line, $black);

    $y += 14;
}

/* ==========================================
   QR CODE
========================================== */
if (file_exists($qr)) {

    $qrSrc = imagecreatefrompng($qr);

    $qrDst = imagecreatetruecolor(75, 75);
    imagecopyresampled(
        $qrDst, $qrSrc,
        0, 0,
        0, 0,
        75, 75,
        imagesx($qrSrc),
        imagesy($qrSrc)
    );

    imagecopy($img, $qrDst, 230, 115, 0, 0, 75, 75);
}

/* ==========================================
   OUTPUT GAMBAR PNG (FIX)
========================================== */
header("Content-Type: image/png");
header("Content-Disposition: inline; filename=kartu_$nomor.png");

imagepng($img);
imagedestroy($img);
exit;
?>

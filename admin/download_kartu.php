<?php
ob_start();
include "../koneksi.php";
require_once "../phpqrcode/qrlib.php";

/* ===============================
   VALIDASI ID
================================ */
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Content-Type: text/plain");
    exit("ID tidak valid");
}

/* ===============================
   AMBIL DATA KARYAWAN
================================ */
$q = $conn->prepare("
    SELECT nama_karyawan, divisi, alamat 
    FROM karyawan 
    WHERE id_karyawan=?
    LIMIT 1
");
$q->bind_param("i", $id);
$q->execute();
$r = $q->get_result()->fetch_assoc();

if (!$r) {
    header("Content-Type: text/plain");
    exit("Data karyawan tidak ditemukan");
}

/* ===============================
   FORMAT QR (WAJIB!)
   SESUAI abs en.php
================================ */
$qrText =
    trim($r['nama_karyawan']) . "|" .
    trim($r['divisi']) . "|" .
    trim($r['alamat']);

/* ===============================
   PATH QR
================================ */
$qrDir = "../uploads/qrcode/";
if (!is_dir($qrDir)) mkdir($qrDir, 0777, true);

$qrFile = $qrDir . "karyawan_" . $id . ".png";

/* ===============================
   GENERATE QR (UKURAN ASLI)
================================ */
QRcode::png(
    $qrText,
    $qrFile,
    QR_ECLEVEL_Q,
    6,   // ukuran pas, stabil scan
    2
);

/* ===============================
   OUTPUT PNG (ANTI WORD)
================================ */
ob_clean();
header("Content-Type: image/png");
header("Content-Disposition: inline; filename=qr_karyawan_$id.png");
readfile($qrFile);
exit;

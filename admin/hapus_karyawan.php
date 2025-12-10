<?php
include "../koneksi.php"; // koneksi database ($conn)

// pastikan ada ID
if (!isset($_GET['id'])) {
    header("Location: data_karyawan.php");
    exit;
}

$id = $_GET['id'];

// Ambil data lama untuk hapus file foto + QR
$q = mysqli_query($conn, "SELECT foto_karyawan, barcode FROM karyawan WHERE id_karyawan='$id'");
$data = mysqli_fetch_assoc($q);

// Hapus foto jika ada
if (!empty($data['foto_karyawan'])) {
    $fotoPath = "../uploads/karyawan/" . $data['foto_karyawan'];
    if (file_exists($fotoPath)) {
        unlink($fotoPath);
    }
}

// Hapus QR Code jika ada
if (!empty($data['barcode'])) {
    $qrPath = "../uploads/qrcode/" . $data['barcode'];
    if (file_exists($qrPath)) {
        unlink($qrPath);
    }
}

// Hapus data dari database
mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan='$id'");

// kembali ke halaman data karyawan
header("Location: data_karyawan.php");
exit;
?>

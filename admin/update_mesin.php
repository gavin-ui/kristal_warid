<?php
include "../koneksi.php";

// Ambil data dari POST
$id         = $_POST['id'];
$mesin      = $_POST['mesin'];
$jam_mulai  = $_POST['jam_mulai'];
$qty        = $_POST['qty'];

// Update database
mysqli_query($conn, "
    UPDATE produksi_mesin 
    SET mesin='$mesin', jam_mulai='$jam_mulai', qty='$qty'
    WHERE id_produksi='$id'
");

// Redirect kembali ke halaman utama
header("Location: produksi_mesin_input.php?success=edit");
exit;
?>

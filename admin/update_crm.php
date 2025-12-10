<?php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_crm'])) {
    header("Location: crm.php");
    exit;
}

$id     = intval($_POST['id_crm']);
$nama   = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$outlet = mysqli_real_escape_string($conn, $_POST['nama_outlet']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
$marketing = mysqli_real_escape_string($conn, $_POST['marketing']);
$no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
$jalur  = mysqli_real_escape_string($conn, $_POST['jalur']);
$ket    = mysqli_real_escape_string($conn, $_POST['keterangan_crm']);

// ambil foto lama
$q = mysqli_query($conn, "SELECT foto FROM crm WHERE id_crm='$id'");
$oldFoto = "";
if ($q && mysqli_num_rows($q) > 0) {
    $d = mysqli_fetch_assoc($q);
    $oldFoto = $d['foto'];
}

$fotoName = $oldFoto;

// jika ada upload foto baru -> hapus lama dan save baru
if (!empty($_FILES['foto']) && !empty($_FILES['foto']['name'])) {
    // hapus file lama
    if ($oldFoto && file_exists("../assets/foto_crm/" . $oldFoto)) {
        @unlink("../assets/foto_crm/" . $oldFoto);
    }
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $fotoName = "crm_" . time() . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/foto_crm/" . $fotoName);
}

// update db
mysqli_query($conn, "
    UPDATE crm SET
        nama_lengkap = '$nama',
        nama_outlet = '$outlet',
        alamat = '$alamat',
        lokasi = '$lokasi',
        marketing = '$marketing',
        no_hp = '$no_hp',
        jalur = '$jalur',
        keterangan_crm = '$ket',
        foto = '$fotoName'
    WHERE id_crm = '$id'
");

header("Location: crm.php?success=edit");
exit;
?>

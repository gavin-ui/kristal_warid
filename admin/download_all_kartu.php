<?php
include "../koneksi.php";

$zip = new ZipArchive();
$zipName = "semua_kartu_" . time() . ".zip";

if ($zip->open($zipName, ZipArchive::CREATE) !== TRUE) {
    die("Gagal membuat ZIP");
}

$q = $conn->query("SELECT id_karyawan FROM karyawan");

while ($r = $q->fetch_assoc()) {
    $id = $r['id_karyawan'];

    // generate kartu dulu
    $imgPath = "temp_kartu_$id.jpg";
    exec("php generate_kartu.php?id=$id > $imgPath");

    $zip->addFile($imgPath, basename($imgPath));
}

$zip->close();

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$zipName");
readfile($zipName);

unlink($zipName);
foreach (glob("temp_kartu_*.jpg") as $f) unlink($f);
exit;

<?php
include "../koneksi.php";

// pastikan request POST dan ada id (kolom id flexible)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: penggunaan_plastik_data_awal.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_POST['id']);

// tentukan nama kolom primary key: ambil field pertama dari tabel
$res = mysqli_query($conn, "SHOW COLUMNS FROM penggunaan_plastik");
$colsArr = [];
while ($col = mysqli_fetch_assoc($res)) {
    $colsArr[] = $col['Field'];
}
$primaryKey = $colsArr[0]; // asumsi field pertama adalah PK

// build set clauses dari POST (kecuali id)
$sets = [];
foreach ($_POST as $k => $v) {
    if ($k === 'id') continue;
    // hanya update kolom yang memang ada di tabel
    if (in_array($k, $colsArr)) {
        $safe = mysqli_real_escape_string($conn, $v);
        $sets[] = "$k = '" . $safe . "'";
    }
}

if (count($sets) > 0) {
    $sql = "UPDATE penggunaan_plastik SET " . implode(",", $sets) . " WHERE $primaryKey = '" . mysqli_real_escape_string($conn,$id) . "'";
    mysqli_query($conn, $sql);
}

header("Location: penggunaan_plastik_data_awal.php?success=edit");
exit;
?>

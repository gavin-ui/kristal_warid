<?php
include "../koneksi.php";

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

/* ===============================
   AMBIL DATA YANG SUDAH ABSEN
================================ */
$q = mysqli_query($conn,
    "SELECT nama_karyawan, divisi, status_kehadiran,
            waktu_masuk, waktu_pulang
     FROM absensi
     WHERE DATE(waktu_masuk) = '$tanggal'
       AND waktu_masuk IS NOT NULL
     ORDER BY nama_karyawan ASC"
);

/* ===============================
   HEADER EXCEL
================================ */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=absensi_$tanggal.xls");

echo "<table border='1'>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Nama</th>
    <th>Divisi</th>
    <th>Status</th>
    <th>Jam Masuk</th>
    <th>Jam Pulang</th>
</tr>";

$no = 1;
while($r = mysqli_fetch_assoc($q)){
    $tgl = date("d-m-Y", strtotime($r['waktu_masuk']));

    echo "<tr>
        <td>{$no}</td>
        <td>{$tgl}</td>
        <td>{$r['nama_karyawan']}</td>
        <td>{$r['divisi']}</td>
        <td>{$r['status_kehadiran']}</td>
        <td>".($r['waktu_masuk'] ? date("H:i", strtotime($r['waktu_masuk'])) : "-")."</td>
        <td>".($r['waktu_pulang'] ? date("H:i", strtotime($r['waktu_pulang'])) : "-")."</td>
    </tr>";
    $no++;
}

echo "</table>";

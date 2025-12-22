<?php
// ======================================
// EXPORT EXCEL CRM
// ======================================

// JANGAN ADA OUTPUT APAPUN SEBELUM HEADER
ob_start();

include "../koneksi.php";

// Query data CRM
$q = mysqli_query($conn, "SELECT * FROM crm ORDER BY id_crm ASC");

// Header Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=CRM_" . date("Y-m-d_H-i-s") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Table Excel
echo "<table border='1'>
<tr style='background:#f59e0b;font-weight:bold;'>
    <th>No</th>
    <th>Tanggal</th>
    <th>Nama Lengkap</th>
    <th>Nama Outlet</th>
    <th>Qty</th>
    <th>Jenis Es</th>
    <th>Kota / Kabupaten</th>
    <th>Alamat Lengkap</th>
    <th>Lokasi</th>
    <th>Marketing</th>
    <th>No HP</th>
    <th>Jalur</th>
    <th>Keterangan</th>
</tr>";

$no = 1;
while ($r = mysqli_fetch_assoc($q)) {

    echo "<tr>
        <td>{$no}</td>
        <td>" . date('d-m-Y', strtotime($r['tanggal_input'])) . "</td>
        <td>{$r['nama_lengkap']}</td>
        <td>{$r['nama_outlet']}</td>
        <td>{$r['qty']}</td>
        <td>{$r['jenis_es']}</td>
        <td>{$r['kota_kabupaten']}</td>
        <td>{$r['alamat_lengkap']}</td>
        <td>{$r['lokasi']}</td>
        <td>{$r['marketing']}</td>
        <td>{$r['no_hp']}</td>
        <td>{$r['jalur']}</td>
        <td>{$r['keterangan_crm']}</td>
    </tr>";

    $no++;
}

echo "</table>";

// Bersihkan buffer
ob_end_flush();
exit;

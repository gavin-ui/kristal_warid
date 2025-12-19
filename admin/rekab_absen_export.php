<?php
include "../koneksi.php";

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$filter  = $_GET['filter'] ?? 'ALL';

$where = "DATE(waktu_masuk)='$tanggal'";

if($filter == "HADIR"){
    $where .= " AND status_kehadiran='HADIR'";
}
elseif($filter == "IZIN"){
    $where .= " AND status_kehadiran='IZIN'";
}
elseif($filter == "BELUM"){
    $where .= " AND status_kehadiran IS NULL";
}

$query = mysqli_query($conn,"SELECT * FROM absensi WHERE $where");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_absensi_$tanggal.xls");
?>

<table border="1">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Divisi</th>
    <th>Status</th>
    <th>Jam Masuk</th>
    <th>Jam Pulang</th>
    <th>Latitude</th>
    <th>Longitude</th>
    <th>Alasan</th>
</tr>

<?php $no=1; while($r=mysqli_fetch_assoc($query)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $r['nama_karyawan'] ?></td>
    <td><?= $r['divisi'] ?></td>
    <td><?= $r['status_kehadiran'] ?? 'BELUM' ?></td>
    <td><?= $r['waktu_masuk'] ?></td>
    <td><?= $r['waktu_pulang'] ?></td>
    <td><?= $r['latitude'] ?></td>
    <td><?= $r['longitude'] ?></td>
    <td><?= $r['alasan'] ?></td>
</tr>
<?php endwhile; ?>
</table>

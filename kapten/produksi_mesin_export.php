<?php
include "../koneksi.php";

$mesin = $_GET['mesin'] ?? '';

$where = "";
if ($mesin) {
    $where = "WHERE mesin='$mesin'";
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=produksi_mesin.xls");

$data = mysqli_query($conn, "SELECT * FROM produksi_mesin $where ORDER BY tanggal_input DESC");
?>

<table border="1">
<tr>
<th>ID</th>
<th>Mesin</th>
<th>Jam</th>
<th>Menit</th>
<th>Defroz</th>
<th>Pack</th>
<th>Qty</th>
<th>Kristal</th>
<th>Serut</th>
<th>Keterangan</th>
<th>Tanggal</th>
</tr>

<?php while($r=mysqli_fetch_assoc($data)): ?>
<tr>
<td><?= $r['id_produksi'] ?></td>
<td><?= $r['mesin'] ?></td>
<td><?= $r['jam_mulai'] ?></td>
<td><?= $r['menit'] ?></td>
<td><?= $r['defroz'] ?></td>
<td><?= $r['pack'] ?></td>
<td><?= $r['qty'] ?></td>
<td><?= $r['kristal'] ?></td>
<td><?= $r['serut'] ?></td>
<td><?= $r['keterangan'] ?></td>
<td><?= $r['tanggal_input'] ?></td>
</tr>
<?php endwhile; ?>
</table>

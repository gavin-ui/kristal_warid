<?php
include "../koneksi.php";

/* ======================
   HEADER EXCEL
====================== */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=penggunaan_plastik_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

/* ======================
   FILTER ID (OPSIONAL)
====================== */
$where = "";
if(isset($_GET['id']) && $_GET['id'] !== ''){
    $id = intval($_GET['id']);
    $where = "WHERE id_plastik = $id";
}

/* ======================
   QUERY SEMUA DATA
====================== */
$q = mysqli_query($conn,"
    SELECT *
    FROM penggunaan_plastik
    $where
    ORDER BY id_plastik DESC
");
?>

<table border="1">
    <thead>
        <tr style="background:#f59e0b;font-weight:bold">
            <?php
            // Ambil semua nama kolom otomatis
            $fields = mysqli_fetch_fields($q);
            foreach($fields as $f){
                echo "<th>{$f->name}</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php while($r = mysqli_fetch_assoc($q)): ?>
        <tr>
            <?php foreach($r as $val): ?>
                <td><?= $val ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
// penggunaan_plastik_get.php
include "../koneksi.php";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) { header('Content-Type: application/json'); echo json_encode([]); exit; }

$res = mysqli_query($conn, "SELECT * FROM penggunaan_plastik WHERE id_plastik = $id LIMIT 1");
$data = mysqli_fetch_assoc($res) ?: [];
header('Content-Type: application/json');
echo json_encode($data);
exit;

<?php
include "../koneksi.php";
$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM produksi_mesin WHERE id_produksi='$id'");
header("Location: produksi_mesin_list.php");

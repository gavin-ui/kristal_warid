<?php
include "../koneksi.php";

$id = $_POST['id'];

mysqli_query($conn, "
UPDATE produksi_mesin SET
mesin='$_POST[mesin]',
jam_mulai='$_POST[jam_mulai]',
menit='$_POST[menit]',
defroz='$_POST[defroz]',
pack='$_POST[pack]',
qty='$_POST[qty]',
kristal='$_POST[kristal]',
serut='$_POST[serut]',
keterangan='$_POST[keterangan]'
WHERE id_produksi='$id'
");

header("Location: produksi_mesin_list.php");

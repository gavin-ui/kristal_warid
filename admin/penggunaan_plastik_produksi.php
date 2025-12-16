<?php
include "../koneksi.php";

/* ================= UPDATE PRODUKSI ================= */
if(isset($_POST['update_produksi'])){
    function v($x){ return ($x==='' ? "NULL" : intval($x)); }

    $id = intval($_POST['id_plastik']);

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            total_kristal=".v($_POST['total_kristal']).",
            total_serut=".v($_POST['total_serut']).",
            total_rusak=".v($_POST['total_rusak']).",
            sisa_total_plastik_terpakai=".v($_POST['sisa_total_plastik_terpakai']).",
            hasil_produksi_hari_ini=".v($_POST['hasil_produksi_hari_ini']).",
            total_hasil_produksi_mesin_a=".v($_POST['total_hasil_produksi_mesin_a']).",
            total_hasil_produksi_mesin_b=".v($_POST['total_hasil_produksi_mesin_b']).",
            jumlah_hasil_produksi_keseluruhan=".v($_POST['jumlah_hasil_produksi_keseluruhan']).",
            total_produksi_hari_ini_final=".v($_POST['total_produksi_hari_ini_final']).",
            retur_total_dari_armada=".v($_POST['retur_total_dari_armada'])."
        WHERE id_plastik=$id
    ");
    exit;
}

/* ================= DELETE ================= */
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM penggunaan_plastik WHERE id_plastik=".$_GET['hapus']);
    header("Location: penggunaan_plastik_produksi.php");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
.page-container{margin-left:290px;padding:32px;background:var(--body-bg);min-height:100vh}
.form-card{background:var(--card-bg);padding:50px;border-radius:14px;max-width:10000px;margin:auto}
.form-grid{display:grid;grid-template-columns:5fr 5fr;gap:50px}
label{font-weight:700;color:var(--title-color)}
input,select{width:100%;padding:10px;border-radius:10px;border:2px solid var(--title-color)}
body.dark input,body.dark select{background:#0f1729;color:#fff}
.btn{padding:12px;border:none;border-radius:10px;font-weight:700;cursor:pointer}
.btn-save{background:var(--hover-bg);width:100%}
.btn-detail{background:linear-gradient(135deg,#0075ff,#00b4ff);color:#fff;width:100%;margin-top:10px}
.form-actions{
    margin-top:25px;
}

/* ===== MODAL ===== */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;justify-content:center;align-items:center;z-index:9999}
.modal-box{background:var(--card-bg);padding:20px;border-radius:16px;width:95%;max-width:1100px;max-height:85vh;overflow:auto}
table{width:100%;border-collapse:collapse;margin-top:10px}
th,td{border:1px solid #ccc;padding:6px;text-align:center;color:var(--title-color)}
th{background:var(--hover-bg)}
.btn-edit{background:#ffc107}
.btn-delete{background:#dc3545;color:#fff}
.modal-box table{
    margin-bottom:20px;
}



/* HILANGKAN PANAH NUMBER */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{-webkit-appearance:none}
input[type=number]{-moz-appearance:textfield}
</style>

<div class="page-container">
<div class="form-card">

<h2 style="text-align:center;color:var(--title-color)">🏭 Input / Update Produksi</h2>

<!-- ===== FORM INPUT UTAMA (TIDAK DIHILANGKAN) ===== -->
<form method="POST">
<label>Pilih ID</label>
<select name="id_plastik" required>
<option value="">-- Pilih ID --</option>
<?php
$q=mysqli_query($conn,"SELECT id_plastik,tanggal_input FROM penggunaan_plastik ORDER BY id_plastik DESC");
while($r=mysqli_fetch_assoc($q)){
    echo "<option value='{$r['id_plastik']}'>ID {$r['id_plastik']} - {$r['tanggal_input']}</option>";
}
?>
</select>

<div class="form-grid" style="margin-top:12px">
<div>
<label>Total Kristal</label><input name="total_kristal" type="number">
<label>Total Serut</label><input name="total_serut" type="number">
<label>Total Rusak</label><input name="total_rusak" type="number">
<label>Sisa Plastik</label><input name="sisa_total_plastik_terpakai" type="number">
</div>
<div>
<label>Hasil Hari Ini</label><input name="hasil_produksi_hari_ini" type="number">
<label>Mesin A</label><input name="total_hasil_produksi_mesin_a" type="number">
<label>Mesin B</label><input name="total_hasil_produksi_mesin_b" type="number">
<label>Jumlah Produksi</label><input name="jumlah_hasil_produksi_keseluruhan" type="number">
</div>
</div>

<div class="form-grid" style="margin-top:12px">
<div><label>Total Final</label><input name="total_produksi_hari_ini_final" type="number"></div>
<div><label>Retur Armada</label><input name="retur_total_dari_armada" type="number"></div>
</div>

<div class="form-actions">
    <button class="btn btn-save" name="update_produksi">
        💾 Update Produksi
    </button>
</div>
</form>

<button class="btn btn-detail" onclick="openDetail()">📋 Lihat Detail</button>
</div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal-bg" id="modalDetail">
<div class="modal-box">
<h3 style="text-align:center">📊 Detail Produksi Lengkap</h3>

<table>
<thead>
<tr>
<th>ID</th><th>Tanggal</th>
<th>Kristal</th><th>Serut</th><th>Rusak</th>
<th>Mesin A</th><th>Mesin B</th>
<th>Total</th><th>Retur</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q=mysqli_query($conn,"SELECT * FROM penggunaan_plastik ORDER BY id_plastik DESC");
while($r=mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= $r['id_plastik'] ?></td>
<td><?= $r['tanggal_input'] ?></td>
<td><?= $r['total_kristal'] ?></td>
<td><?= $r['total_serut'] ?></td>
<td><?= $r['total_rusak'] ?></td>
<td><?= $r['total_hasil_produksi_mesin_a'] ?></td>
<td><?= $r['total_hasil_produksi_mesin_b'] ?></td>
<td><?= $r['total_produksi_hari_ini_final'] ?></td>
<td><?= $r['retur_total_dari_armada'] ?></td>
<td>
<button class="btn btn-edit" onclick='openEdit(<?= json_encode($r) ?>)'>Edit</button>
<a class="btn btn-delete" href="?hapus=<?= $r['id_plastik'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<button class="btn btn-save" onclick="closeDetail()">Tutup</button>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">
<h3>✏ Edit Produksi</h3>

<form id="formEdit">
<input type="hidden" name="id_plastik" id="e_id">

<div class="form-grid">
<div>
<label>Kristal</label><input id="e_kristal" name="total_kristal" type="number">
<label>Serut</label><input id="e_serut" name="total_serut" type="number">
<label>Rusak</label><input id="e_rusak" name="total_rusak" type="number">
<label>Sisa</label><input id="e_sisa" name="sisa_total_plastik_terpakai" type="number">
</div>
<div>
<label>Mesin A</label><input id="e_a" name="total_hasil_produksi_mesin_a" type="number">
<label>Mesin B</label><input id="e_b" name="total_hasil_produksi_mesin_b" type="number">
<label>Total</label><input id="e_total" name="total_produksi_hari_ini_final" type="number">
<label>Retur</label><input id="e_retur" name="retur_total_dari_armada" type="number">
</div>
</div>

<button type="button" class="btn btn-save" onclick="submitEdit()">💾 Simpan</button>
</form>

<button class="btn btn-detail" onclick="backToDetail()">Kembali</button>
</div>
</div>

<script>
const md=document.getElementById('modalDetail');
const me=document.getElementById('modalEdit');

function openDetail(){md.style.display='flex'}
function closeDetail(){md.style.display='none'}
function backToDetail(){me.style.display='none';md.style.display='flex'}

function openEdit(d){
    md.style.display='none';me.style.display='flex';
    e_id.value=d.id_plastik;
    e_kristal.value=d.total_kristal;
    e_serut.value=d.total_serut;
    e_rusak.value=d.total_rusak;
    e_sisa.value=d.sisa_total_plastik_terpakai;
    e_a.value=d.total_hasil_produksi_mesin_a;
    e_b.value=d.total_hasil_produksi_mesin_b;
    e_total.value=d.total_produksi_hari_ini_final;
    e_retur.value=d.retur_total_dari_armada;
}

function submitEdit(){
    const f=new FormData(document.getElementById('formEdit'));
    f.append('update_produksi',1);
    fetch('',{method:'POST',body:f}).then(()=>location.reload());
}
</script>

<?php include "partials/footer.php"; ?>
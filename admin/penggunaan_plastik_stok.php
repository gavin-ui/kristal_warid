<?php
include "../koneksi.php";

/* ================= UPDATE STOK ================= */
if (isset($_POST['submit_stok'])) {

    function v($x){
        return ($x === '' ? "NULL" : intval($x));
    }

    $id = intval($_POST['id_plastik']);

    $stok_cs_kemarin  = v($_POST['stok_cs_kemarin']);
    $repack_stok      = v($_POST['repack_stok']);
    $jumlah_total     = v($_POST['jumlah_total_stok_kemarin_retur_repack']);
    $total_barel      = v($_POST['total_barel']);
    $stok_setelah     = v($_POST['stok_cs_setelah_dikurangi_barel']);
    $total_final      = v($_POST['total_produksi_hari_ini_final']);

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            stok_cs_kemarin = $stok_cs_kemarin,
            repack_stok = $repack_stok,
            jumlah_total_stok_kemarin_retur_repack = $jumlah_total,
            total_barel = $total_barel,
            stok_cs_setelah_dikurangi_barel = $stok_setelah,
            total_produksi_hari_ini_final = $total_final
        WHERE id_plastik = $id
    ");

    exit;
}

/* ================= DELETE ================= */
if (isset($_GET['hapus'])) {
    mysqli_query($conn,"DELETE FROM penggunaan_plastik WHERE id_plastik=".$_GET['hapus']);
    header("Location: penggunaan_plastik_stok.php");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
/* ===== THEME FIX ===== */
body{ color:var(--title-color); }

/* ===== PAGE ===== */
.page-container{
    margin-left:290px;
    padding:32px;
    min-height:100vh;
    background:var(--body-bg);
}

.form-card{
    max-width:960px;
    margin:auto;
    background:var(--card-bg);
    padding:28px;
    border-radius:16px;
}

/* ===== FORM ===== */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

label{
    font-weight:700;
    margin:12px 0 6px;
    display:block;
    color:var(--title-color);
}

input,select{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:2px solid var(--title-color);
    color:var(--title-color);
}

/* ===== REMOVE NUMBER SPINNER ===== */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
    margin:0;
}
input[type=number]{ -moz-appearance:textfield; }

/* ===== BUTTON ===== */
.btn{
    padding:12px;
    border:none;
    border-radius:10px;
    font-weight:700;
    cursor:pointer;
    color:var(--title-color);
}

.btn-save{ background:var(--hover-bg); width:100%; }
.btn-detail{ background:#0075ff; color:#fff; width:100%; margin-top:12px; }
.btn-edit{ background:#ffc107; }
.btn-delete{ background:#dc3545; color:#fff; }

/* ===== MODAL ===== */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.modal-box{
    background:var(--card-bg);
    width:95%;
    max-width:1100px;
    max-height:85vh;
    border-radius:16px;
    display:flex;
    flex-direction:column;
}

.modal-header{
    padding:16px 20px;
    font-weight:700;
    border-bottom:1px solid #ccc;
    color:var(--title-color);
}

.modal-body{
    padding:20px;
    overflow:auto;
    color:var(--title-color);
}

.modal-footer{
    padding:16px;
    border-top:1px solid #ccc;
    display:flex;
    gap:12px;
    justify-content:flex-end;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
    color:var(--title-color);
}

th{ background:var(--hover-bg); }
</style>

<div class="page-container">
<div class="form-card">

<h2 style="text-align:center;color:var(--title-color)">📦 Input / Update — Stok</h2>

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

<div class="form-grid">
<div>
    <label>Stok CS Kemarin</label>
    <input name="stok_cs_kemarin" type="number">

    <label>Repack Stok</label>
    <input name="repack_stok" type="number">

    <label>Total Stok (Kemarin + Retur + Repack)</label>
    <input name="jumlah_total_stok_kemarin_retur_repack" type="number">
</div>

<div>
    <label>Total Barkel</label>
    <input name="total_barel" type="number">

    <label>Stok Setelah Dikurangi Barkel</label>
    <input name="stok_cs_setelah_dikurangi_barel" type="number">

    <label>Total Produksi Hari Ini (Final)</label>
    <input name="total_produksi_hari_ini_final" type="number">
</div>
</div>

<button class="btn btn-save" name="submit_stok">💾 Update Stok</button>
</form>

<button class="btn btn-detail" onclick="openDetail()">📋 Lihat Detail Stok</button>

</div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal-bg" id="modalDetail">
<div class="modal-box">

<div class="modal-header">📋 Detail Stok</div>

<div class="modal-body">
<table>
<thead>
<tr>
<th>ID</th>
<th>Stok Kemarin</th>
<th>Repack</th>
<th>Total</th>
<th>Barkel</th>
<th>Sisa</th>
<th>Final</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q=mysqli_query($conn,"SELECT * FROM penggunaan_plastik ORDER BY id_plastik DESC");
while($r=mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= $r['id_plastik'] ?></td>
<td><?= $r['stok_cs_kemarin'] ?></td>
<td><?= $r['repack_stok'] ?></td>
<td><?= $r['jumlah_total_stok_kemarin_retur_repack'] ?></td>
<td><?= $r['total_barel'] ?></td>
<td><?= $r['stok_cs_setelah_dikurangi_barel'] ?></td>
<td><?= $r['total_produksi_hari_ini_final'] ?></td>
<td>
<button class="btn btn-edit" onclick='openEdit(<?= json_encode($r) ?>)'>Edit</button>
<a class="btn btn-delete" href="?hapus=<?= $r['id_plastik'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<div class="modal-footer">
<button class="btn btn-save" onclick="closeDetail()">Tutup</button>
</div>

</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">

<div class="modal-header">✏️ Edit Stok</div>

<div class="modal-body">
<form id="formEdit">
<input type="hidden" name="id_plastik" id="e_id">

<div class="form-grid">
<div>
    <label>Stok Kemarin</label><input id="e1" name="stok_cs_kemarin" type="number">
    <label>Repack</label><input id="e2" name="repack_stok" type="number">
    <label>Total</label><input id="e3" name="jumlah_total_stok_kemarin_retur_repack" type="number">
</div>
<div>
    <label>Barkel</label><input id="e4" name="total_barel" type="number">
    <label>Sisa</label><input id="e5" name="stok_cs_setelah_dikurangi_barel" type="number">
    <label>Final</label><input id="e6" name="total_produksi_hari_ini_final" type="number">
</div>
</div>
</form>
</div>

<div class="modal-footer">
<button class="btn btn-save" onclick="submitEdit()">💾 Simpan</button>
<button class="btn btn-detail" onclick="backToDetail()">Kembali</button>
</div>

</div>
</div>

<script>
const md=document.getElementById('modalDetail');
const me=document.getElementById('modalEdit');

function openDetail(){ md.style.display='flex'; }
function closeDetail(){ md.style.display='none'; }
function backToDetail(){ me.style.display='none'; md.style.display='flex'; }

function openEdit(d){
    md.style.display='none';
    me.style.display='flex';
    e_id.value=d.id_plastik;
    e1.value=d.stok_cs_kemarin;
    e2.value=d.repack_stok;
    e3.value=d.jumlah_total_stok_kemarin_retur_repack;
    e4.value=d.total_barel;
    e5.value=d.stok_cs_setelah_dikurangi_barel;
    e6.value=d.total_produksi_hari_ini_final;
}

function submitEdit(){
    const f=new FormData(document.getElementById('formEdit'));
    f.append('submit_stok',1);
    fetch('',{method:'POST',body:f}).then(()=>location.reload());
}
</script>

<?php include "partials/footer.php"; ?>

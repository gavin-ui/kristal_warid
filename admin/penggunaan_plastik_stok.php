<?php
include "../koneksi.php";

/* ================= UPDATE STOK ================= */
if (isset($_POST['submit_stok'])) {

    function v($x){
        return ($x === '' ? "NULL" : intval($x));
    }

    $id = intval($_POST['id_plastik']);

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            stok_cs_kemarin = ".v($_POST['stok_cs_kemarin']).",
            repack_stok = ".v($_POST['repack_stok']).",
            jumlah_total_stok_kemarin_retur_repack = ".v($_POST['jumlah_total_stok_kemarin_retur_repack']).",
            total_barel = ".v($_POST['total_barel']).",
            stok_cs_setelah_dikurangi_barel = ".v($_POST['stok_cs_setelah_dikurangi_barel']).",
            total_produksi_hari_ini_final = ".v($_POST['total_produksi_hari_ini_final'])."
        WHERE id_plastik = $id
    ");

    header("Location: penggunaan_plastik_stok.php?success=1");
    exit;
}

/* ================= DELETE ================= */
if (isset($_GET['reset_stok'])) {
    $id = intval($_GET['reset_stok']);

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            stok_cs_kemarin = NULL,
            repack_stok = NULL,
            jumlah_total_stok_kemarin_retur_repack = NULL,
            total_barel = NULL,
            stok_cs_setelah_dikurangi_barel = NULL,
            total_produksi_hari_ini_final = NULL
        WHERE id_plastik = $id
    ");

    header("Location: penggunaan_plastik_stok.php?reset=1");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
/* =====================================================
   BASE
===================================================== */
body{
    color:var(--text-color);
    background:var(--body-bg);
    font-family:Inter, Arial, sans-serif;
}

/* =====================================================
   PAGE
===================================================== */
.page-container{
    margin-left:290px;
    padding:32px;
    min-height:100vh;
    transition:.3s ease;
}

body.collapsed .page-container{
    margin-left:110px;
}

/* =====================================================
   CARD
===================================================== */
.form-card{
    max-width:820px;
    margin:auto;
    padding:26px 28px;
    background:var(--card-bg);
    border-radius:20px;
    border:1.5px solid rgba(245,158,11,.35);
    box-shadow:
        0 25px 45px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.5);
}

/* =====================================================
   TITLE
===================================================== */
.form-card h2{
    text-align:center;
    font-size:24px;
    font-weight:900;
    margin-bottom:22px;
    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   FORM GRID
===================================================== */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:50px;
    margin-top:14px;
}

/* =====================================================
   LABEL
===================================================== */
label{
    display:block;
    margin:10px 0 6px;
    font-size:13px;
    font-weight:800;
    color:var(--title-color);
}

/* =====================================================
   INPUT & SELECT
===================================================== */
input, select{
    width:100%;
    padding:9px 12px;
    font-size:14px;
    border-radius:14px;
    border:1.8px solid rgba(245,158,11,.45);
    background:var(--input-bg, #ffffff);
    color:var(--text-color);
    transition:.25s ease;
}

/* DARK MODE INPUT */
body.dark input,
body.dark select{
    background:#0f1729;
    color:#fff;
    border-color:rgba(59,130,246,.6);
}

input:focus, select:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.25);
}

/* HILANGKAN SPINNER NUMBER */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
    margin:0;
}
input[type=number]{ -moz-appearance:textfield }

/* =====================================================
   BUTTON
===================================================== */
.btn{
    padding:12px;
    border-radius:16px;
    font-weight:900;
    letter-spacing:.4px;
    cursor:pointer;
    border:none;
    transition:.3s ease;
}

.btn-save{
    margin-top:22px;
    width:100%;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    box-shadow:0 18px 35px rgba(37,99,235,.45);
}

.btn-save:hover{
    transform:translateY(-2px);
}

.btn-detail{
    width:100%;
    margin-top:12px;
    background:linear-gradient(135deg,#0ea5e9,#0284c7);
    color:#fff;
}

.btn-edit{
    background:#facc15;
    color:#111;
    padding:6px 10px;
    border-radius:10px;
    font-weight:800;
}

.btn-delete{
    background:#ef4444;
    color:#fff;
    padding:6px 10px;
    border-radius:10px;
    font-weight:800;
}

/* ================= EXPORT BUTTON ================= */
.action-buttons{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
    margin-top:18px;
}

.btn-excel{
    background:linear-gradient(135deg,#16a34a,#15803d);
    color:#fff;
    box-shadow:0 14px 30px rgba(22,163,74,.45);
}

.btn-excel:hover:not(:disabled){
    transform:translateY(-2px);
}

.btn-excel:disabled{
    opacity:.5;
    cursor:not-allowed;
    box-shadow:none;
}

/* =====================================================
   MODAL BASE
===================================================== */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:99999;
}

.modal-box{
    width:95%;
    max-width:1050px;
    max-height:85vh;
    background:var(--card-bg);
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 30px 60px rgba(0,0,0,.45);
    display:flex;
    flex-direction:column;
}

/* =====================================================
   MODAL HEADER
===================================================== */
.modal-header{
    padding:18px 22px;
    font-size:18px;
    font-weight:900;
    text-align:center;
    background:linear-gradient(90deg,#2563eb,#f59e0b);
    color:#fff;
}

/* =====================================================
   MODAL BODY
===================================================== */
.modal-body{
    padding:20px;
    overflow:auto;
    color:var(--text-color);
}

/* =====================================================
   MODAL FOOTER
===================================================== */
.modal-footer{
    padding:16px;
    border-top:1px solid rgba(0,0,0,.15);
    display:flex;
    gap:12px;
    justify-content:flex-end;
}

/* =====================================================
   TABLE
===================================================== */
table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

th, td{
    padding:10px;
    border:1px solid rgba(0,0,0,.12);
    text-align:center;
}

/* LIGHT MODE TABLE */
th{
    background:#fbbf24;
    color:#111;
    font-weight:900;
}

/* DARK MODE TABLE */
body.dark th{
    background:#1e293b;
    color:#fff;
}

body.dark td{
    color:#e5e7eb;
}

/* =====================================================
   EDIT FORM (MODAL)
===================================================== */
#modalEdit .form-grid{
    gap:14px;
}

#modalEdit label{
    font-size:12px;
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media(max-width:768px){
    .page-container{
        margin-left:0;
        padding:22px 14px;
    }

    .form-grid{
        grid-template-columns:1fr;
    }

    .modal-box{
        max-height:90vh;
    }
}
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

<div>
<button
        id="btnExport"
        class="btn btn-excel"
        disabled
        onclick="exportExcel()">
        📊 Export Excel
    </button>
</div>

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
const selectID = document.querySelector('select[name="id_plastik"]');
const btnExport = document.getElementById('btnExport');

selectID.addEventListener('change', () => {
    btnExport.disabled = selectID.value === "";
});

function exportExcel(){
    const id = selectID.value;
    if(!id){
        alert("Silakan pilih ID terlebih dahulu");
        return;
    }
    window.location.href = "produksi_mesin_export_excel.php?id=" + id;
}
</script>

<?php include "partials/footer.php"; ?>

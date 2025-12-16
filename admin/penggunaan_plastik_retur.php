<?php
/* =====================================================
   KONEKSI
===================================================== */
include "../koneksi.php";

/* =====================================================
   UPDATE (AJAX SAFE – TIDAK PUTIH)
===================================================== */
if (isset($_POST['submit_retur'])) {

    function v($x){
        return ($x === '' ? "NULL" : intval($x));
    }

    $id = intval($_POST['id_plastik']);

    $r1 = v($_POST['retur_armada_carry_h8516gk']);
    $r2 = v($_POST['retur_armada_long_hb017ov']);
    $r3 = v($_POST['retur_armada_traga_h9876ag']);
    $r4 = v($_POST['retur_armada_elf_h8023ov']);
    $r5 = v($_POST['retur_armada_elf_h8019ov']);
    $r6 = v($_POST['retur_armada_elf_dobel_h8021ov']);

    $arr=[];
    foreach([$r1,$r2,$r3,$r4,$r5,$r6] as $x){
        if($x!=="NULL") $arr[]=$x;
    }
    $total = count($arr)?array_sum($arr):"NULL";

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            retur_armada_carry_h8516gk     = $r1,
            retur_armada_long_hb017ov      = $r2,
            retur_armada_traga_h9876ag     = $r3,
            retur_armada_elf_h8023ov       = $r4,
            retur_armada_elf_h8019ov       = $r5,
            retur_armada_elf_dobel_h8021ov = $r6,
            retur_total_dari_armada        = $total
        WHERE id_plastik = $id
    ");

    echo "OK";
    exit;
}

/* =====================================================
   DELETE
===================================================== */
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM penggunaan_plastik WHERE id_plastik=".$_GET['hapus']);
    header("Location: penggunaan_plastik_retur.php");
    exit;
}

/* =====================================================
   LAYOUT
===================================================== */
include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
/* ===== PAGE ===== */
.page-container{
    margin-left:290px;
    padding:32px;
    min-height:100vh;
    background:var(--body-bg);
}

/* ===== CARD ===== */
.form-card{
    max-width:900px;
    margin:auto;
    background:var(--card-bg);
    padding:26px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
}

/* ===== FORM ===== */
.form-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:24px;
}

label{
    display:block;
    font-weight:700;
    margin:14px 0 6px;
    color:var(--title-color);
}

input,select{
    width:100%;
    padding:11px;
    border-radius:10px;
    border:2px solid var(--hover-bg);
    background:var(--body-bg);
    color:var(--title-color);
}

input:focus,select:focus{
    outline:none;
    border-color:#0075ff;
}

/* ===== BUTTON ===== */
.btn{
    padding:12px;
    border:none;
    border-radius:10px;
    font-weight:700;
    cursor:pointer;
    transition:.2s;
}

.btn:hover{opacity:.85}

.btn-save{background:var(--hover-bg); width:100%}
.btn-detail{background:#0075ff;color:#fff;width:100%;margin-top:14px}
.btn-edit{background:#ffc107}
.btn-delete{background:#dc3545;color:#fff}

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
    max-width:1000px;
    max-height:85vh;
    border-radius:18px;
    display:flex;
    flex-direction:column;
    box-shadow:0 15px 45px rgba(0,0,0,.35);
}

.modal-header{
    padding:18px 22px;
    font-weight:800;
    border-bottom:1px solid rgba(255,255,255,.15);
    color:var(--title-color);
}

.modal-body{
    padding:22px;
    overflow:auto;
}

.modal-footer{
    padding:16px 20px;
    border-top:1px solid rgba(255,255,255,.15);
    display:flex;
    gap:12px;
    justify-content:flex-end;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
    border-radius:12px;
    overflow:hidden;
}

th{
    background:var(--hover-bg);
    color:#fff;
    padding:10px;
}

td{
    padding:8px;
    text-align:center;
    background:var(--body-bg);
    color:var(--title-color);
    border-bottom:1px solid rgba(255,255,255,.1);
}

tbody tr:hover td{
    background:rgba(0,117,255,.08);
}

/* ===== HILANGKAN PANAH INPUT NUMBER ===== */

/* Chrome, Edge, Safari */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number]{
    -moz-appearance: textfield;
}

/* ===== JUDUL HALAMAN ===== */
.page-title{
    text-align:center;
    font-weight:800;
    color:var(--title-color);
    margin-bottom:24px;
}
</style>

<!-- =====================================================
     FORM INPUT
===================================================== -->
<div class="page-container">
<div class="form-card">

<h2 class="page-title">↩️ Input / Update Retur Armada</h2>

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
<label>Carry</label><input name="retur_armada_carry_h8516gk" type="number">
<label>Long</label><input name="retur_armada_long_hb017ov" type="number">
</div>
<div>
<label>Traga</label><input name="retur_armada_traga_h9876ag" type="number">
<label>Elf 8023</label><input name="retur_armada_elf_h8023ov" type="number">
</div>
<div>
<label>Elf 8019</label><input name="retur_armada_elf_h8019ov" type="number">
<label>Elf Dobel</label><input name="retur_armada_elf_dobel_h8021ov" type="number">
</div>
</div>

<button class="btn btn-save" name="submit_retur">💾 Update Retur</button>
</form>

<button class="btn btn-detail" onclick="openDetail()">📋 Lihat Detail Retur</button>

</div>
</div>

<!-- =====================================================
     MODAL DETAIL
===================================================== -->
<div class="modal-bg" id="modalDetail">
<div class="modal-box">

<div class="modal-header">📋 Detail Retur Armada</div>

<div class="modal-body">
<table>
<thead>
<tr>
<th>ID</th><th>Carry</th><th>Long</th><th>Traga</th>
<th>Elf 8023</th><th>Elf 8019</th><th>Dobel</th><th>Total</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q=mysqli_query($conn,"SELECT * FROM penggunaan_plastik ORDER BY id_plastik DESC");
while($r=mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= $r['id_plastik'] ?></td>
<td><?= $r['retur_armada_carry_h8516gk'] ?></td>
<td><?= $r['retur_armada_long_hb017ov'] ?></td>
<td><?= $r['retur_armada_traga_h9876ag'] ?></td>
<td><?= $r['retur_armada_elf_h8023ov'] ?></td>
<td><?= $r['retur_armada_elf_h8019ov'] ?></td>
<td><?= $r['retur_armada_elf_dobel_h8021ov'] ?></td>
<td><?= $r['retur_total_dari_armada'] ?></td>
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

<!-- =====================================================
     MODAL EDIT
===================================================== -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">

<div class="modal-header">✏️ Edit Retur Armada</div>

<div class="modal-body">
<form id="formEdit">
<input type="hidden" name="id_plastik" id="e_id">

<div class="form-grid">
<div>
<label>Carry</label><input id="e1" name="retur_armada_carry_h8516gk" type="number">
<label>Long</label><input id="e2" name="retur_armada_long_hb017ov" type="number">
</div>
<div>
<label>Traga</label><input id="e3" name="retur_armada_traga_h9876ag" type="number">
<label>Elf 8023</label><input id="e4" name="retur_armada_elf_h8023ov" type="number">
</div>
<div>
<label>Elf 8019</label><input id="e5" name="retur_armada_elf_h8019ov" type="number">
<label>Elf Dobel</label><input id="e6" name="retur_armada_elf_dobel_h8021ov" type="number">
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

function openDetail(){md.style.display='flex'}
function closeDetail(){md.style.display='none'}
function backToDetail(){me.style.display='none';md.style.display='flex'}

function openEdit(d){
    md.style.display='none';
    me.style.display='flex';
    e_id.value=d.id_plastik;
    e1.value=d.retur_armada_carry_h8516gk;
    e2.value=d.retur_armada_long_hb017ov;
    e3.value=d.retur_armada_traga_h9876ag;
    e4.value=d.retur_armada_elf_h8023ov;
    e5.value=d.retur_armada_elf_h8019ov;
    e6.value=d.retur_armada_elf_dobel_h8021ov;
}

function submitEdit(){
    const f=new FormData(document.getElementById('formEdit'));
    f.append('submit_retur',1);
    fetch('',{method:'POST',body:f}).then(()=>location.reload());
}
</script>

<?php include "partials/footer.php"; ?>

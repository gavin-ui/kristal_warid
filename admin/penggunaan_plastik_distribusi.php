<?php
/* =====================================================
   KONEKSI
===================================================== */
include "../koneksi.php";

/* =====================================================
   UPDATE DISTRIBUSI (AJAX)
===================================================== */
if (isset($_POST['submit_distribusi'])) {

    function v($x){
        return ($x === '' ? "NULL" : intval($x));
    }

    $id = intval($_POST['id_plastik']);

    $d1 = v($_POST['distribusi_barkel_carry_h8516gk']);
    $d2 = v($_POST['distribusi_barkel_long_hb017ov']);
    $d3 = v($_POST['distribusi_barkel_traga_h9876ag']);
    $d4 = v($_POST['distribusi_barkel_elf_h8023ov']);
    $d5 = v($_POST['distribusi_barkel_elf_h8019ov']);

    if ($_POST['total_barel'] !== '') {
        $total = intval($_POST['total_barel']);
    } else {
        $arr = [];
        foreach([$d1,$d2,$d3,$d4,$d5] as $x){
            if ($x !== "NULL") $arr[] = $x;
        }
        $total = count($arr) ? array_sum($arr) : "NULL";
    }

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            distribusi_barkel_carry_h8516gk = $d1,
            distribusi_barkel_long_hb017ov  = $d2,
            distribusi_barkel_traga_h9876ag = $d3,
            distribusi_barkel_elf_h8023ov   = $d4,
            distribusi_barkel_elf_h8019ov   = $d5,
            total_barel                    = ".($total==="NULL"?"NULL":$total)."
        WHERE id_plastik = $id
    ");
    exit;
}

/* =====================================================
   DELETE
===================================================== */
if (isset($_GET['hapus'])) {
    mysqli_query($conn,"DELETE FROM penggunaan_plastik WHERE id_plastik=".$_GET['hapus']);
    header("Location: penggunaan_plastik_distribusi.php");
    exit;
}

/* =====================================================
   LAYOUT
===================================================== */
include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
.page-container{margin-left:290px;padding:32px;min-height:100vh;background:var(--body-bg);}
.form-card{max-width:950px;margin:auto;background:var(--card-bg);padding:24px;border-radius:16px;}
.page-title{text-align:center;font-weight:800;color:var(--title-color);margin-bottom:24px;}

.form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;}
label{font-weight:700;margin:12px 0 6px;display:block;}

input,select{
    width:100%;padding:10px;border-radius:10px;
    border:2px solid var(--hover-bg);
    background:var(--body-bg);color:var(--title-color);
}

/* hilangkan spinner */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{ -webkit-appearance:none; }
input[type=number]{ -moz-appearance:textfield; }

.btn{padding:12px;border:none;border-radius:10px;font-weight:700;cursor:pointer;}
.btn-save{background:var(--hover-bg);width:100%;}
.btn-detail{background:#0075ff;color:#fff;width:100%;margin-top:12px;}
.btn-edit{background:#ffc107;}
.btn-delete{background:#dc3545;color:#fff;}

/* MODAL */
.modal-bg{
    position:fixed;inset:0;background:rgba(0,0,0,.55);
    display:none;justify-content:center;align-items:center;z-index:9999;
}
.modal-box{
    background:var(--card-bg);width:95%;max-width:1000px;
    max-height:85vh;border-radius:16px;
    display:flex;flex-direction:column;
}
.modal-header{padding:16px 20px;font-weight:800;border-bottom:1px solid #ccc;}
.modal-body{padding:20px;overflow:auto;}
.modal-footer{padding:16px;border-top:1px solid #ccc;display:flex;gap:12px;justify-content:flex-end;}

table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:8px;text-align:center;}
th{background:var(--hover-bg);color:#fff;}

/* ===== GLOBAL TEXT THEME FIX ===== */
body{
    color: var(--title-color);
}

/* ===== TABLE ===== */
table th,
table td{
    color: var(--title-color);
}

/* ===== MODAL ===== */
.modal-header{
    color: var(--title-color);
}

.modal-body{
    color: var(--title-color);
}

/* ===== BUTTON TEXT ===== */
.btn{
    color: var(--title-color);
}

.btn-save{
    color: var(--title-color);
}

.btn-detail{
    color:#fff; /* biru tetap putih */
}

.btn-delete{
    color:#fff;
}

/* ===== FORM INPUT ===== */
label{
    color: var(--title-color);
}

input,
select{
    color: var(--title-color);
}

/* DARK MODE INPUT BG (opsional tapi cakep) */
body.dark input,
body.dark select{
    background:#0f1729;
}
</style>

<!-- =====================================================
     FORM INPUT
===================================================== -->
<div class="page-container">
<div class="form-card">

<h2 class="page-title">🚚 Input / Update Distribusi Barkel</h2>

<form id="formMain">
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
<label>Carry</label><input type="number" name="distribusi_barkel_carry_h8516gk">
<label>Long</label><input type="number" name="distribusi_barkel_long_hb017ov">
<label>Traga</label><input type="number" name="distribusi_barkel_traga_h9876ag">
</div>
<div>
<label>Elf 8023</label><input type="number" name="distribusi_barkel_elf_h8023ov">
<label>Elf 8019</label><input type="number" name="distribusi_barkel_elf_h8019ov">
<label>Total Barkel</label><input type="number" name="total_barel">
</div>
</div>

<button class="btn btn-save" onclick="submitMain(event)">💾 Update Distribusi</button>
</form>

<button class="btn btn-detail" onclick="openDetail()">📋 Lihat Detail Distribusi</button>

</div>
</div>

<!-- =====================================================
     MODAL DETAIL
===================================================== -->
<div class="modal-bg" id="modalDetail">
<div class="modal-box">

<div class="modal-header">📋 Detail Distribusi Barkel</div>

<div class="modal-body">
<table>
<thead>
<tr>
<th>ID</th><th>Carry</th><th>Long</th><th>Traga</th>
<th>Elf 8023</th><th>Elf 8019</th><th>Total</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q=mysqli_query($conn,"SELECT * FROM penggunaan_plastik ORDER BY id_plastik DESC");
while($r=mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= $r['id_plastik'] ?></td>
<td><?= $r['distribusi_barkel_carry_h8516gk'] ?></td>
<td><?= $r['distribusi_barkel_long_hb017ov'] ?></td>
<td><?= $r['distribusi_barkel_traga_h9876ag'] ?></td>
<td><?= $r['distribusi_barkel_elf_h8023ov'] ?></td>
<td><?= $r['distribusi_barkel_elf_h8019ov'] ?></td>
<td><?= $r['total_barel'] ?></td>
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

<script>
const modalDetail = document.getElementById('modalDetail');

function openDetail(){ modalDetail.style.display='flex'; }
function closeDetail(){ modalDetail.style.display='none'; }

function submitMain(e){
    e.preventDefault();
    const f=new FormData(document.getElementById('formMain'));
    f.append('submit_distribusi',1);
    fetch('',{method:'POST',body:f}).then(()=>location.reload());
}
</script>

<?php include "partials/footer.php"; ?>

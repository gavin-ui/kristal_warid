<?php
include "../koneksi.php";

/* ===============================
   UPDATE DISTRIBUSI (AJAX)
================================ */
if (isset($_POST['submit_distribusi'])) {

    function v($x){
        return ($x === '' || $x === null) ? "NULL" : intval($x);
    }

    $id = intval($_POST['id_plastik']);

    $d1 = v($_POST['distribusi_barkel_carry_h8516gk'] ?? '');
    $d2 = v($_POST['distribusi_barkel_long_hb017ov'] ?? '');
    $d3 = v($_POST['distribusi_barkel_traga_h9876ag'] ?? '');
    $d4 = v($_POST['distribusi_barkel_elf_h8023ov'] ?? '');
    $d5 = v($_POST['distribusi_barkel_elf_h8019ov'] ?? '');

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

include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
.page-container{
    margin-left:290px;
    padding:35px;
    min-height:100vh;
    background:var(--body-bg);
}
body.collapsed .page-container{margin-left:110px}

.form-card{
    background:var(--card-bg);
    padding:25px;
    border-radius:15px;
    max-width:750px;
    margin:auto;
    box-shadow:0 8px 15px rgba(0,0,0,.1);
}

.page-title{
    text-align:center;
    font-weight:800;
    color:var(--title-color);
    margin-bottom:20px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
}

label{color:var(--title-color);font-weight:bold}
input,select{
    width:98%;
    padding:12px;
    border-radius:10px;
    border:2px solid var(--title-color);
    margin-bottom:14px;
    background:#fff;
}
body.dark input,
body.dark select{
    background:#0f1729;
    color:#fff;
}

.btn-save,.btn-detail{
    padding:12px;
    border-radius:10px;
    border:none;
    font-weight:bold;
    cursor:pointer;
}
.btn-save{background:var(--hover-bg);width:100%}
.btn-detail{background:#0075ff;color:#fff;width:100%;margin-top:10px}

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
    background:var(--card-bg);
    width:95%;
    max-width:900px;
    padding:20px;
    border-radius:16px;
    max-height:85vh;
    overflow:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
th,td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
    color:var(--title-color);
}
th{background:var(--hover-bg)}

/* HILANGKAN SPINNER NUMBER */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;margin:0;
}
input[type=number]{-moz-appearance:textfield}
</style>

<div class="page-container">
<div class="form-card">

<h2 class="page-title">🚚 Distribusi Barkel</h2>

<form id="formMain">
<label>Pilih ID Plastik</label>
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
<label>Carry</label>
<input type="number" name="distribusi_barkel_carry_h8516gk">

<label>Long</label>
<input type="number" name="distribusi_barkel_long_hb017ov">

<label>Traga</label>
<input type="number" name="distribusi_barkel_traga_h9876ag">
</div>

<div>
<label>Elf 8023</label>
<input type="number" name="distribusi_barkel_elf_h8023ov">

<label>Elf 8019</label>
<input type="number" name="distribusi_barkel_elf_h8019ov">

<label>Total Barkel</label>
<input type="number" name="total_barel">
</div>
</div>

<button class="btn-save" onclick="submitMain(event)">💾 Simpan Distribusi</button>
</form>

<button class="btn-detail" onclick="openDetail()">📋 Lihat Detail Distribusi</button>

</div>
</div>

<!-- MODAL DETAIL -->
<div class="modal-bg" id="modalDetail">
<div class="modal-box">

<h3 style="margin-bottom:10px;color:var(--title-color)">📋 Detail Distribusi</h3>

<table>
<thead>
<tr>
<th>ID</th>
<th>Carry</th>
<th>Long</th>
<th>Traga</th>
<th>Elf 8023</th>
<th>Elf 8019</th>
<th>Total</th>
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
</tr>
<?php endwhile; ?>
</tbody>
</table>

<button class="btn-save" style="margin-top:15px" onclick="closeDetail()">Tutup</button>

</div>
</div>

<script>
const modalDetail = document.getElementById('modalDetail');

function openDetail(){ modalDetail.style.display = 'flex'; }
function closeDetail(){ modalDetail.style.display = 'none'; }

function submitMain(e){
    e.preventDefault();
    const f = new FormData(document.getElementById('formMain'));
    f.append('submit_distribusi',1);

    fetch('',{method:'POST',body:f})
    .then(()=>{ alert('Data berhasil disimpan'); location.reload(); });
}
</script>

<?php include "partials/footer.php"; ?>

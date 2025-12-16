<?php 
include "../koneksi.php";

/* ================= INSERT ================= */
if(isset($_POST["submit"])){
    mysqli_query($conn,"
        INSERT INTO penggunaan_plastik (
            tanggal_input,
            plastik_awal,
            sisa_plastik_kemarin,
            total_penggunaan_plastik
        ) VALUES (
            '$_POST[tanggal_input]',
            '$_POST[plastik_awal]',
            '$_POST[sisa_plastik_kemarin]',
            '$_POST[total_penggunaan_plastik]'
        )
    ");
    header("Location: penggunaan_plastik_data_awal.php?success=1");
    exit;
}

/* ================= UPDATE ================= */
if(isset($_POST["update"])){
    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            plastik_awal='$_POST[plastik_awal]',
            sisa_plastik_kemarin='$_POST[sisa_plastik_kemarin]',
            total_penggunaan_plastik='$_POST[total_penggunaan_plastik]'
        WHERE id_plastik='$_POST[id_plastik]'
    ");
    header("Location: penggunaan_plastik_data_awal.php");
    exit;
}if(isset($_POST["update"])){

    $tanggal = $_POST['tanggal_input'];

    // kalau kosong, pakai tanggal lama (aman)
    if(empty($tanggal)){
        $q = mysqli_query($conn,"SELECT tanggal_input FROM penggunaan_plastik WHERE id_plastik='$_POST[id_plastik]'");
        $d = mysqli_fetch_assoc($q);
        $tanggal = $d['tanggal_input'];
    } else {
        // ubah jadi DATETIME
        $tanggal = $tanggal . " 00:00:00";
    }

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET
            plastik_awal='$_POST[plastik_awal]',
            sisa_plastik_kemarin='$_POST[sisa_plastik_kemarin]',
            total_penggunaan_plastik='$_POST[total_penggunaan_plastik]'
        WHERE id_plastik='$_POST[id_plastik]'
    ");

    header("Location: penggunaan_plastik_data_awal.php");
    exit;
}

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    mysqli_query($conn,"DELETE FROM penggunaan_plastik WHERE id_plastik='$_GET[delete]'");
    header("Location: penggunaan_plastik_data_awal.php");
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

label{color:var(--title-color);font-weight:bold}
input{
    width:98%;
    padding:12px;
    border-radius:10px;
    border:2px solid var(--title-color);
    margin-bottom:14px;
    background:#fff;
}
body.dark input{
    background:#0f1729;
    color:#fff;
}

.btn-save,.btn-detail,.btn-edit,.btn-delete{
    padding:12px;
    border-radius:10px;
    border:none;
    font-weight:bold;
    cursor:pointer;
}
.btn-save{background:var(--hover-bg);width:100%}
.btn-detail{background:#0075ff;color:#fff;width:100%;margin-top:10px}
.btn-edit{background:#ffc107}
.btn-delete{background:#dc3545;color:#fff}

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

/* ===== HILANGKAN TOMBOL NAIK TURUN INPUT NUMBER ===== */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield; /* Firefox */
}
</style>

<div class="page-container">
<div class="form-card">

<h2 style="text-align:center;color:var(--title-color)">📦 Input Data Awal Plastik</h2>

<?php if(isset($_GET['success'])): ?>
<div style="background:#22bb33;color:white;padding:10px;border-radius:10px;text-align:center">
✔ Data berhasil disimpan
</div>
<?php endif; ?>

<form method="POST">
<label>Tanggal Input</label>
<input type="date" name="tanggal_input" required>

<label>Plastik Awal (Roll)</label>
<input type="number" name="plastik_awal" required>

<label>Sisa Plastik Kemarin (Roll)</label>
<input type="number" name="sisa_plastik_kemarin" required>

<label>Total Penggunaan Plastik (Roll)</label>
<input type="number" name="total_penggunaan_plastik" required>

<button class="btn-save" name="submit">💾 Simpan</button>
</form>

<button class="btn-detail" id="btnDetail">📋 Lihat Detail</button>

</div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal-bg" id="modalDetail">
<div class="modal-box">
<h3 style="text-align:center;color:var(--title-color)">📊 Data Penggunaan Plastik</h3>

<table>
<thead>
<tr>
<th>Tanggal</th>
<th>Plastik Awal</th>
<th>Sisa Kemarin</th>
<th>Total</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q=mysqli_query($conn,"SELECT * FROM penggunaan_plastik ORDER BY id_plastik DESC");
while($r=mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= $r['tanggal_input'] ?></td>
<td><?= $r['plastik_awal'] ?></td>
<td><?= $r['sisa_plastik_kemarin'] ?></td>
<td><?= $r['total_penggunaan_plastik'] ?></td>
<td>
<button class="btn-edit"
onclick="openEdit(
'<?= $r['id_plastik'] ?>',
'<?= $r['tanggal_input'] ?>',
'<?= $r['plastik_awal'] ?>',
'<?= $r['sisa_plastik_kemarin'] ?>',
'<?= $r['total_penggunaan_plastik'] ?>'
)">Edit</button>
<a class="btn-delete" href="?delete=<?= $r['id_plastik'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<button class="btn-save" onclick="closeDetail()" style="margin-top:15px">Tutup</button>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">
<h3 style="text-align:center;color:var(--title-color)">✏ Edit Data Plastik</h3>

<form method="POST">
<input type="hidden" name="id_plastik" id="e_id">

<label>Plastik Awal</label>
<input type="number" name="plastik_awal" id="e_awal">

<label>Sisa Kemarin</label>
<input type="number" name="sisa_plastik_kemarin" id="e_sisa">

<label>Total</label>
<input type="number" name="total_penggunaan_plastik" id="e_total">

<button class="btn-save" name="update">💾 Update</button>
</form>

<button class="btn-detail" onclick="closeEdit()">Kembali</button>
</div>
</div>

<script>
const modalDetail = document.getElementById('modalDetail');
const modalEdit   = document.getElementById('modalEdit');

document.getElementById('btnDetail').onclick = () => {
    modalDetail.style.display = 'flex';
}

function closeDetail(){
    modalDetail.style.display = 'none';
}

function openEdit(id,tgl,awal,sisa,total){
    modalDetail.style.display='none';
    modalEdit.style.display='flex';

    e_id.value    = id;
    e_awal.value  = awal;
    e_sisa.value  = sisa;
    e_total.value = total;
}

function closeEdit(){
    modalEdit.style.display='none';
    modalDetail.style.display='flex';
}
</script>

<?php include "partials/footer.php"; ?>

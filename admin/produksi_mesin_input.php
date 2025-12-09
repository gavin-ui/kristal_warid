<?php 
include "../koneksi.php";

// FILTER MESIN
$filterMesin = isset($_GET["mesin"]) ? $_GET["mesin"] : "all";

// INSERT DATA
if(isset($_POST["submit"])) {

    $mesin      = $_POST["mesin"];
    $jam_mulai  = $_POST["jam_mulai"];
    $menit      = $_POST["menit"];
    $defroz     = $_POST["defroz"];
    $pack       = $_POST["pack"];
    $qty        = $_POST["qty"];
    $kristal    = $_POST["kristal"];
    $serut      = $_POST["serut"];
    $ket        = $_POST["keterangan"];

    mysqli_query($conn,"
        INSERT INTO produksi_mesin (mesin, jam_mulai, menit, defroz, pack, qty, kristal, serut, keterangan)
        VALUES ('$mesin','$jam_mulai','$menit','$defroz','$pack','$qty','$kristal','$serut','$ket')
    ");
    
    header("Location: produksi_mesin_input.php?success=1");
    exit;
}

// DELETE DATA
if(isset($_GET["delete"])){
    $id = $_GET["delete"];
    mysqli_query($conn,"DELETE FROM produksi_mesin WHERE id_produksi='$id'");
    header("Location: produksi_mesin_input.php?success=delete");
    exit;
}

include "partials/sidebar.php";
?>

<style>
.page-container { margin-left:290px; padding:35px; background:var(--body-bg); min-height:100vh; transition:.3s; }
body.collapsed .page-container { margin-left:110px; }

.form-card { background:var(--card-bg); padding:25px; border-radius:15px; box-shadow:0 8px 15px rgba(0,0,0,.1); max-width:780px; margin:auto; }

input, select, textarea { width:100%; padding:12px; border-radius:10px; border:2px solid var(--title-color); background:white; color:black; margin-bottom:10px; }
body.dark input, body.dark textarea, body.dark select { background:#0f1729 !important; color:white !important; }

.btn-save, .export-btn, .detail-btn {
    padding:12px; width:100%; border-radius:10px; cursor:pointer; font-weight:bold; border:none; transition:.3s;
}
.btn-save { background:var(--hover-bg); }
.export-btn { background:#1cbfff; margin-top:10px; }
.detail-btn { background:#0075ff; color:white; margin-top:10px; }
button:hover { transform:scale(1.05); }

.toast {
    position:fixed; top:20px; right:20px; background:#28a745; color:#fff; padding:14px;
    border-radius:10px; font-weight:bold; animation:fadeIn .4s, fadeOut .5s 2s forwards;
}

.table-wrapper {
    margin-top:25px;
    background:var(--card-bg);
    padding:20px;
    border-radius:10px;
    box-shadow: 0px 5px 15px rgba(0,0,0,.1);
}

table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:10px; text-align:center; border:1px solid #ccc; }
th { background:var(--hover-bg); font-weight:bold; }

.edit-btn { background:#ffc107; padding:6px 12px; border-radius:6px; cursor:pointer; }
.delete-btn { background:#dc3545; padding:6px 12px; border-radius:6px; color:white; cursor:pointer; }

/* ======== MODAL FIX (LANDSCAPE & SCROLL) ========= */
.modal-bg { position:fixed; inset:0; background:rgba(0,0,0,.5); display:none; justify-content:center; align-items:center; z-index:99999; }

.modal-box { 
    background:var(--card-bg); 
    padding:25px; 
    border-radius:10px; 
    width:90%; 
    max-width:760px; 
    max-height:90vh;
    overflow-y:auto; 
    box-shadow:0 10px 30px rgba(0,0,0,.25); 
}

/* grid form 2 kolom biar rapih */
.edit-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.edit-grid textarea {
    grid-column:span 2;
}
</style>

<div class="page-container">
<div class="form-card">

<h2 style="text-align:center;color:var(--title-color)">📄 Input Produksi Mesin</h2>

<form method="POST">

<select name="mesin" required>
    <option value="">-- Pilih Mesin --</option>
    <option value="A">Mesin A</option>
    <option value="B">Mesin B</option>
</select>

<input type="time" name="jam_mulai" required placeholder="Jam Mulai">
<input type="number" name="menit" required placeholder="Durasi Produksi Menit">
<input type="number" name="defroz" required placeholder="Defroz">
<input type="number" name="pack" required placeholder="Pack">
<input type="number" name="qty" required placeholder="Qty">
<input type="number" name="kristal" required placeholder="Kristal (kg)">
<input type="number" name="serut" placeholder="Serut">
<textarea name="keterangan" placeholder="Keterangan tambahan (opsional)"></textarea>

<button class="btn-save" name="submit">💾 Simpan Data</button>
</form>

<form method="POST" action="export_excel.php">
    <button class="export-btn">📤 Export Excel</button>
</form>

<button id="btnDetail" class="detail-btn">📋 Lihat Data Produksi</button>

</div>

<!-- ====== TABEL ====== -->
<div id="tableSection" class="table-wrapper">
<h3 style="text-align:center;color:var(--title-color)">📦 Data Produksi Mesin</h3>

<form method="GET" style="margin-bottom:12px;">
<select name="mesin" onchange="this.form.submit()">
    <option value="all" <?= $filterMesin=="all" ? "selected":"" ?>>Semua Mesin</option>
    <option value="A" <?= $filterMesin=="A" ? "selected":"" ?>>Mesin A</option>
    <option value="B" <?= $filterMesin=="B" ? "selected":"" ?>>Mesin B</option>
</select>
</form>

<table>
<thead>
<tr>
<th>ID</th><th>Mesin</th><th>Jam</th><th>Qty</th><th>Aksi</th>
</tr>
</thead>
<tbody>

<?php
$where = ($filterMesin=="all") ? "" : "WHERE mesin='$filterMesin'";
$result = mysqli_query($conn,"SELECT * FROM produksi_mesin $where ORDER BY id_produksi DESC");

while($r=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $r['id_produksi'] ?></td>
<td><?= $r['mesin'] ?></td>
<td><?= $r['jam_mulai'] ?></td>
<td><?= $r['qty'] ?></td>
<td>
<button onclick="openEdit(
'<?= $r['id_produksi'] ?>',
'<?= $r['mesin'] ?>',
'<?= $r['jam_mulai'] ?>',
'<?= $r['menit'] ?>',
'<?= $r['defroz'] ?>',
'<?= $r['pack'] ?>',
'<?= $r['qty'] ?>',
'<?= $r['kristal'] ?>',
'<?= $r['serut'] ?>',
`<?= $r['keterangan'] ?>`
)" class="edit-btn">✏ Edit</button>

<a href="?delete=<?= $r['id_produksi'] ?>" class="delete-btn" onclick="return confirm('Yakin hapus data ini?')">🗑 Hapus</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>
</div>

<!-- ==== EDIT MODAL (UPDATED) ==== -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">

<h3 style="text-align:center;color:var(--title-color)">✏ Edit Data Produksi</h3>

<form method="POST" action="update_mesin.php">
<input type="hidden" id="edit_id" name="id">

<div class="edit-grid">

<div><label>Mesin</label><select id="edit_mesin" name="mesin"><option value="A">Mesin A</option><option value="B">Mesin B</option></select></div>
<div><label>Jam Mulai</label><input type="time" id="edit_jam" name="jam_mulai" required></div>
<div><label>Durasi (menit)</label><input type="number" id="edit_menit" name="menit" required></div>
<div><label>Defroz</label><input type="number" id="edit_defroz" name="defroz" required></div>
<div><label>Pack</label><input type="number" id="edit_pack" name="pack" required></div>
<div><label>Qty</label><input type="number" id="edit_qty" name="qty" required></div>
<div><label>Kristal (kg)</label><input type="number" id="edit_kristal" name="kristal" required></div>
<div><label>Serut</label><input type="number" id="edit_serut" name="serut"></div>

<textarea id="edit_ket" name="keterangan" placeholder="Keterangan..."></textarea>

</div>

<button class="btn-save" style="margin-top:10px;">💾 Simpan Perubahan</button>
</form>

<button onclick="closeEdit()" style="width:100%;padding:10px;border-radius:10px;margin-top:10px;background:#aaa;">Tutup</button>

</div>
</div>

<script>
document.getElementById("btnDetail").addEventListener("click", function(){
    document.getElementById("tableSection").scrollIntoView({behavior:"smooth"});
});

function openEdit(id,mesin,jam,menit,defroz,pack,qty,kristal,serut,ket){
    document.getElementById("edit_id").value=id;
    document.getElementById("edit_mesin").value=mesin;
    document.getElementById("edit_jam").value=jam;
    document.getElementById("edit_menit").value=menit;
    document.getElementById("edit_defroz").value=defroz;
    document.getElementById("edit_pack").value=pack;
    document.getElementById("edit_qty").value=qty;
    document.getElementById("edit_kristal").value=kristal;
    document.getElementById("edit_serut").value=serut;
    document.getElementById("edit_ket").value=ket;
    document.getElementById("modalEdit").style.display="flex";
}

function closeEdit(){ document.getElementById("modalEdit").style.display="none"; }
</script>

<?php include "partials/footer.php"; ?>

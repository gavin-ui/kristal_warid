<?php 
include "../koneksi.php";

// =====================
// FILTER MESIN
// =====================
$filterMesin = $_GET['mesin'] ?? 'all';

// =====================
// INSERT DATA
// =====================
if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("
        INSERT INTO produksi_mesin 
        (mesin, jam_mulai, menit, defroz, pack, qty, kristal, serut, keterangan)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");
    $stmt->bind_param(
        "ssiiiiiss",
        $_POST['mesin'],
        $_POST['jam_mulai'],
        $_POST['menit'],
        $_POST['defroz'],
        $_POST['pack'],
        $_POST['qty'],
        $_POST['kristal'],
        $_POST['serut'],
        $_POST['keterangan']
    );
    $stmt->execute();
    header("Location: produksi_mesin_input.php?success=1");
    exit;
}

// =====================
// DELETE DATA
// =====================
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM produksi_mesin WHERE id_produksi=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: produksi_mesin_input.php?success=delete");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
/* ================= LAYOUT ================= */
.page-container{margin-left:290px;padding:35px;background:var(--body-bg);min-height:100vh;transition:.3s}
body.collapsed .page-container{margin-left:110px}
.form-card{background:var(--card-bg);padding:25px;border-radius:15px;box-shadow:0 8px 15px rgba(0,0,0,.1);max-width:780px;margin:auto}

/* ================= FORM ================= */
input,select,textarea{
    width:100%;
    padding:10px 12px;
    border-radius:10px;
    border:2px solid var(--title-color);
    background:white;
    color:black;
    margin-bottom:10px;
    box-sizing:border-box;
}
body.dark input,body.dark textarea,body.dark select{
    background:#0f1729!important;
    color:white!important;
}

/* HILANGKAN TOMBOL NAIK TURUN NUMBER */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
    margin:0;
}
input[type=number]{ -moz-appearance:textfield }

/* ================= BUTTON ================= */
.btn-save,.export-btn,.detail-btn{
    padding:12px;
    width:100%;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    border:none;
    transition:.3s;
}
.btn-save{background:var(--hover-bg)}
.export-btn{background:#1cbfff;margin-top:10px}
.detail-btn{background:#0075ff;color:white;margin-top:10px}
button:hover{transform:scale(1.05)}

/* ================= MODAL ================= */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:99999;
}
.modal-box{
    background:var(--card-bg);
    padding:20px;
    border-radius:16px;
    width:95%;
    max-width:900px;
    max-height:85vh;
    overflow-y:auto;
    box-shadow:0 15px 40px rgba(0,0,0,.3);
    transform:scale(.9) translateY(20px);
    opacity:0;
    animation:modalIn .3s ease forwards;
}
@keyframes modalIn{
    to{transform:scale(1) translateY(0);opacity:1}
}

/* ================= TABLE ================= */
table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{padding:10px;text-align:center;border:1px solid #ccc}
th{background:var(--hover-bg)}
.edit-btn{background:#ffc107;padding:6px 12px;border-radius:6px;cursor:pointer}
.delete-btn{background:#dc3545;padding:6px 12px;border-radius:6px;color:white;cursor:pointer}

/* ================= EDIT GRID ================= */
.edit-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}
.edit-grid textarea{
    grid-column:span 2;
    min-height:90px;
}
</style>

<div class="page-container">
<div class="form-card">

<h2 style="text-align:center;color:var(--title-color)">📄 Input Produksi Mesin</h2>

<form method="POST">
<select name="mesin" required title="Pilih mesin produksi yang digunakan">
    <option value="">-- Pilih Mesin Produksi --</option>
    <option value="A">Mesin A</option>
    <option value="B">Mesin B</option>
</select>

<input type="time" name="jam_mulai" required title="Jam mulai mesin beroperasi">

<input type="number" name="menit" required 
placeholder="Durasi produksi (menit)" 
title="Lama waktu mesin beroperasi dalam menit">

<input type="number" name="defroz" required 
placeholder="Jumlah defrost" 
title="Berapa kali proses defrost dilakukan">

<input type="number" name="pack" required 
placeholder="Jumlah pack dihasilkan" 
title="Total pack es yang dihasilkan">

<input type="number" name="qty" required 
placeholder="Total quantity produksi" 
title="Total hasil produksi keseluruhan">

<input type="number" name="kristal" required 
placeholder="Berat kristal (kg)" 
title="Total berat es kristal dalam kilogram">

<input type="number" name="serut" 
placeholder="Berat serut (kg)" 
title="Berat es serut (jika ada)">

<textarea name="keterangan" 
placeholder="Catatan tambahan produksi (opsional)"
title="Keterangan tambahan jika ada kendala atau catatan khusus"></textarea>

<button class="btn-save" name="submit">💾 Simpan Data</button>
</form>

<form method="POST" action="export_excel.php">
    <button class="export-btn">📤 Export Excel</button>
</form>

<button id="btnDetail" class="detail-btn">📋 Lihat Data Produksi</button>

</div>
</div>

<!-- ================= MODAL TABEL ================= -->
<div class="modal-bg" id="modalTable">
<div class="modal-box">

<h3 style="text-align:center;color:var(--title-color)">
📦 Data Produksi Mesin (Lengkap)
</h3>
<hr style="opacity:.3">

<div style="overflow-x:auto">
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Mesin</th>
    <th>Jam Mulai</th>
    <th>Menit</th>
    <th>Defroz</th>
    <th>Pack</th>
    <th>Qty</th>
    <th>Kristal (kg)</th>
    <th>Serut (kg)</th>
    <th>Keterangan</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php
$where = ($filterMesin=='all') 
    ? '' 
    : "WHERE mesin='".$conn->real_escape_string($filterMesin)."'";

$q = mysqli_query(
    $conn,
    "SELECT * FROM produksi_mesin $where ORDER BY id_produksi DESC"
);

while($r = mysqli_fetch_assoc($q)):
?>
<tr>
    <td><?= $r['id_produksi'] ?></td>
    <td><?= $r['mesin'] ?></td>
    <td><?= $r['jam_mulai'] ?></td>
    <td><?= $r['menit'] ?></td>
    <td><?= $r['defroz'] ?></td>
    <td><?= $r['pack'] ?></td>
    <td><?= $r['qty'] ?></td>
    <td><?= $r['kristal'] ?></td>
    <td><?= $r['serut'] ?></td>
    <td style="max-width:220px;white-space:normal">
        <?= nl2br(htmlspecialchars($r['keterangan'])) ?>
    </td>
    <td>
        <button class="edit-btn"
        onclick="openEdit(
            '<?= $r['id_produksi'] ?>',
            '<?= $r['mesin'] ?>',
            '<?= $r['jam_mulai'] ?>',
            '<?= $r['menit'] ?>',
            '<?= $r['defroz'] ?>',
            '<?= $r['pack'] ?>',
            '<?= $r['qty'] ?>',
            '<?= $r['kristal'] ?>',
            '<?= $r['serut'] ?>',
            `<?= htmlspecialchars($r['keterangan'], ENT_QUOTES) ?>`
        )">✏ Edit</button>

        <a class="delete-btn"
           href="?delete=<?= $r['id_produksi'] ?>"
           onclick="return confirm('Hapus data ini?')">
           🗑
        </a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<button onclick="closeTable()"
style="width:100%;margin-top:12px;padding:10px;border-radius:10px">
Tutup
</button>

</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">
<h3 style="text-align:center;color:var(--title-color)">✏ Edit Data Produksi</h3>
<hr style="opacity:.3">

<form method="POST" action="update_mesin.php">
<input type="hidden" id="edit_id" name="id">

<div class="edit-grid">
<input type="text" id="edit_mesin" name="mesin" placeholder="Kode mesin (A / B)">
<input type="time" id="edit_jam" name="jam_mulai" title="Jam mulai produksi">
<input type="number" id="edit_menit" name="menit" placeholder="Durasi (menit)">
<input type="number" id="edit_defroz" name="defroz" placeholder="Jumlah defrost">
<input type="number" id="edit_pack" name="pack" placeholder="Jumlah pack">
<input type="number" id="edit_qty" name="qty" placeholder="Total quantity">
<input type="number" id="edit_kristal" name="kristal" placeholder="Kristal (kg)">
<input type="number" id="edit_serut" name="serut" placeholder="Serut (kg)">
<textarea id="edit_ket" name="keterangan" placeholder="Catatan produksi"></textarea>
</div>

<button class="btn-save">💾 Simpan</button>
</form>

<button onclick="closeEdit()" style="width:100%;margin-top:10px;padding:10px;border-radius:10px">Tutup</button>
</div>
</div>

<script>
document.getElementById('btnDetail').onclick=()=>{
    document.getElementById('modalTable').style.display='flex'
}
function closeTable(){
    document.getElementById('modalTable').style.display='none'
}
function openEdit(id,mesin,jam,menit,defroz,pack,qty,kristal,serut,ket){
    edit_id.value=id;
    edit_mesin.value=mesin;
    edit_jam.value=jam;
    edit_menit.value=menit;
    edit_defroz.value=defroz;
    edit_pack.value=pack;
    edit_qty.value=qty;
    edit_kristal.value=kristal;
    edit_serut.value=serut;
    edit_ket.value=ket;
    modalEdit.style.display='flex';
}
function closeEdit(){
    modalEdit.style.display='none'
}
</script>

<?php include "partials/footer.php"; ?>

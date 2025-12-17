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
/* =====================================================
   GLOBAL
===================================================== */
body{
    margin:0;
    font-family:Inter, Arial, sans-serif;
    background:var(--body-bg);
    color:var(--text-color);
}

/* =====================================================
   PAGE LAYOUT
===================================================== */
.page-container{
    margin-left:290px;
    padding:36px 24px 160px;
    min-height:100vh;
    transition:.3s ease;
}
body.collapsed .page-container{
    margin-left:110px;
}

/* =====================================================
   FORM CARD (COMPACT & MEWAH)
===================================================== */
.form-card{
    max-width:640px;
    margin:auto;
    padding:22px 24px;

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.96),
        rgba(255,255,255,.88)
    );

    border-radius:18px;
    border:1.5px solid rgba(255,186,39,.45);

    box-shadow:
        0 20px 40px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.7);
}

/* DARK MODE CARD */
body.dark .form-card{
    background:linear-gradient(
        180deg,
        rgba(15,25,50,.95),
        rgba(10,18,36,.92)
    );
    border:1.5px solid rgba(255,186,39,.35);
}

/* =====================================================
   TITLE
===================================================== */
.form-card h2{
    text-align:center;
    margin-bottom:18px;
    font-weight:900;
    font-size:22px;
    letter-spacing:.5px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   FORM INPUT (COMPACT)
===================================================== */
input,
select,
textarea{
    width:100%;
    padding:9px 12px;
    border-radius:10px;
    border:1.6px solid #cbd5e1;
    font-size:13px;
    margin-bottom:10px;
    box-sizing:border-box;

    background:#fff;
    color:#0f172a;
}

/* DARK MODE INPUT */
body.dark input,
body.dark select,
body.dark textarea{
    background:#0f1729;
    border-color:rgba(90,169,255,.35);
    color:#fff;
}

textarea{
    resize:none;
    min-height:70px;
}

/* REMOVE NUMBER SPINNER */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
}
input[type=number]{ -moz-appearance:textfield }

/* =====================================================
   BUTTONS
===================================================== */
.btn-save,
.export-btn,
.detail-btn{
    padding:11px;
    width:100%;
    border-radius:12px;
    font-weight:800;
    font-size:14px;
    border:none;
    cursor:pointer;
    transition:.3s ease;
}

.btn-save{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    border:2px solid #f59e0b;
}

.export-btn{
    background:#0ea5e9;
    color:white;
    margin-top:10px;
}

.detail-btn{
    background:#334155;
    color:white;
    margin-top:10px;
}

button:hover{
    transform:translateY(-2px);
}

/* =====================================================
   MODAL BACKDROP
===================================================== */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

/* =====================================================
   MODAL BOX (SCROLL AMAN)
===================================================== */
.modal-box{
    background:var(--card-bg);
    padding:22px;
    border-radius:18px;
    width:94%;
    max-width:900px;
    max-height:85vh;
    overflow-y:auto;

    box-shadow:0 25px 50px rgba(0,0,0,.45);
}

/* =====================================================
   TABLE (FIX MODE TERANG)
===================================================== */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:14px;
}

th,td{
    padding:10px;
    font-size:13px;
    text-align:center;
    border:1px solid #d1d5db;
}

/* HEADER TABLE */
th{
    background:#f59e0b;
    color:#111827;
    font-weight:800;
}

/* BODY TABLE */
td{
    color:#111827;
    background:#f8fafc;
}

/* DARK MODE TABLE */
body.dark th{
    background:#1e3a8a;
    color:white;
}
body.dark td{
    background:#0f1729;
    color:#e5e7eb;
    border-color:#1e293b;
}

/* =====================================================
   TABLE ACTION BUTTON
===================================================== */
.edit-btn{
    background:#facc15;
    padding:6px 10px;
    border-radius:8px;
    font-weight:700;
    cursor:pointer;
    border:none;
}

.delete-btn{
    background:#ef4444;
    padding:6px 10px;
    border-radius:8px;
    color:white;
    font-weight:700;
    cursor:pointer;
    text-decoration:none;
}

/* =====================================================
   EDIT MODAL FORM (RAPI & ADA LABEL)
===================================================== */
.edit-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.edit-grid label{
    font-size:12px;
    font-weight:800;
    margin-bottom:4px;
    color:#0f172a;
}

body.dark .edit-grid label{
    color:#e5e7eb;
}

.edit-grid textarea{
    grid-column:span 2;
    min-height:80px;
}

/* =====================================================
   MODAL TITLE
===================================================== */
.modal-box h3{
    text-align:center;
    margin-bottom:12px;
    font-weight:900;
    font-size:18px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   MOBILE
===================================================== */
@media(max-width:768px){
    .page-container{
        margin-left:0;
        padding:28px 14px 140px;
    }

    .form-card{
        max-width:100%;
    }

    .edit-grid{
        grid-template-columns:1fr;
    }
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

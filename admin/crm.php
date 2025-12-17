<?php
include "../koneksi.php";

/* ================= INSERT ================= */
if (isset($_POST["submit"])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nama_outlet  = mysqli_real_escape_string($conn, $_POST['nama_outlet']);
    $alamat       = mysqli_real_escape_string($conn, $_POST['alamat']);
    $lokasi       = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $marketing    = mysqli_real_escape_string($conn, $_POST['marketing']);
    $no_hp        = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $jalur        = mysqli_real_escape_string($conn, $_POST['jalur']);
    $ket          = mysqli_real_escape_string($conn, $_POST['keterangan_crm']);

    $foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = "crm_" . time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/foto_crm/".$foto);
    }

    mysqli_query($conn,"INSERT INTO crm
    (nama_lengkap,nama_outlet,alamat,lokasi,marketing,no_hp,jalur,keterangan_crm,foto)
    VALUES
    ('$nama_lengkap','$nama_outlet','$alamat','$lokasi','$marketing','$no_hp','$jalur','$ket','$foto')");

    header("Location: crm.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST["update"])) {
    $id = intval($_POST['id_crm']);

    mysqli_query($conn,"UPDATE crm SET
        nama_lengkap='$_POST[nama_lengkap]',
        nama_outlet='$_POST[nama_outlet]',
        lokasi='$_POST[lokasi]',
        marketing='$_POST[marketing]',
        no_hp='$_POST[no_hp]',
        jalur='$_POST[jalur]',
        alamat='$_POST[alamat]',
        keterangan_crm='$_POST[keterangan_crm]'
        WHERE id_crm=$id
    ");

    header("Location: crm.php");
    exit;
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM crm WHERE id_crm=$id");
    header("Location: crm.php");
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
    font-family:Inter,Arial,sans-serif;
    background:var(--body-bg);
    color:var(--text-color);
}

/* =====================================================
   PAGE CONTAINER
===================================================== */
.page-container{
    margin-left:290px;
    padding:42px 28px 160px;
    min-height:100vh;
    transition:.35s ease;
}
body.collapsed .page-container{
    margin-left:110px;
}

/* =====================================================
   FORM CARD (PREMIUM COMPACT)
===================================================== */
.form-card{
    max-width:760px;
    margin:auto;
    padding:30px 34px;

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.96),
        rgba(255,255,255,.88)
    );

    border-radius:26px;
    border:1.5px solid rgba(245,158,11,.45);

    box-shadow:
        0 28px 55px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.75);

    position:relative;
}

/* Glow */
.form-card::before{
    content:"";
    position:absolute;
    top:-70px;
    right:-70px;
    width:180px;
    height:180px;
    background:radial-gradient(circle, rgba(245,158,11,.25), transparent 70%);
    border-radius:50%;
}

/* =====================================================
   DARK MODE FORM CARD
===================================================== */
body.dark .form-card{
    background:linear-gradient(
        180deg,
        rgba(12,22,40,.96),
        rgba(8,16,32,.94)
    );
    border:1px solid rgba(90,169,255,.25);
    box-shadow:
        0 24px 45px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.15);
}

/* =====================================================
   TITLE
===================================================== */
.form-card h2{
    text-align:center;
    font-size:26px;
    font-weight:900;
    margin-bottom:28px;
    letter-spacing:.6px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   FORM INPUT
===================================================== */
input,
textarea{
    width:100%;
    padding:13px 16px;
    border-radius:16px;
    border:1.6px solid #cbd5e1;
    margin-bottom:14px;
    font-size:14px;

    background:rgba(255,255,255,.92);
    transition:.3s ease;
}

textarea{
    resize:none;
    min-height:90px;
}

/* Focus */
input:focus,
textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.25);
    outline:none;
}

/* =====================================================
   DARK MODE INPUT
===================================================== */
body.dark input,
body.dark textarea{
    background:rgba(10,18,36,.9);
    border-color:rgba(90,169,255,.35);
    color:#fff;
}

body.dark input::placeholder,
body.dark textarea::placeholder{
    color:#9ca3af;
}

/* =====================================================
   BUTTON
===================================================== */
.btn-save,
.detail-btn{
    width:100%;
    padding:15px;
    border-radius:20px;

    font-size:15px;
    font-weight:900;
    letter-spacing:.5px;

    border:none;
    cursor:pointer;
    transition:.35s ease;
}

.btn-save{
    margin-top:10px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;

    border:3px solid #f59e0b;
    box-shadow:
        0 0 0 4px rgba(245,158,11,.35),
        0 18px 30px rgba(37,99,235,.45);
}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow:
        0 0 0 6px rgba(245,158,11,.55),
        0 28px 45px rgba(37,99,235,.6);
}

.detail-btn{
    margin-top:14px;
    background:#0ea5e9;
    color:#fff;
}

/* =====================================================
   MODAL BACKGROUND
===================================================== */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    display:none;
    justify-content:center;
    align-items:flex-start;
    padding-top:70px;
    z-index:99999;
}

/* =====================================================
   MODAL BOX
===================================================== */
.modal-box{
    width:95%;
    max-width:1100px;
    max-height:85vh;
    overflow-y:auto;

    padding:26px 28px;
    border-radius:22px;

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.97),
        rgba(255,255,255,.9)
    );

    box-shadow:0 28px 55px rgba(0,0,0,.25);
}

/* DARK MODAL */
body.dark .modal-box{
    background:linear-gradient(
        180deg,
        rgba(12,22,40,.98),
        rgba(8,16,32,.96)
    );
}

/* =====================================================
   MODAL TITLE
===================================================== */
.modal-box h3{
    text-align:center;
    font-size:22px;
    font-weight:900;
    margin-bottom:18px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   TABLE
===================================================== */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:16px;
    font-size:13px;
}

th,td{
    padding:10px 12px;
    border-bottom:1px solid rgba(0,0,0,.1);
    text-align:center;
}

th{
    background:#f59e0b;
    color:#000;
    font-weight:800;
}

/* DARK TABLE */
body.dark th{
    background:#1d4ed8;
    color:#fff;
}
body.dark td{
    color:#e5e7eb;
    border-color:rgba(255,255,255,.1);
}

/* =====================================================
   IMAGE CRM
===================================================== */
img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #cbd5e1;
}

/* =====================================================
   ACTION BUTTON
===================================================== */
.edit-btn,
.delete-btn{
    padding:7px 12px;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
    border:none;
}

.edit-btn{background:#facc15;}
.delete-btn{background:#ef4444;color:#fff;}

/* =====================================================
   MOBILE
===================================================== */
@media(max-width:768px){
    .page-container{
        margin-left:0;
        padding:32px 16px 140px;
    }

    .form-card{
        padding:26px 22px;
    }
}
</style>

<div class="page-container">
<div class="form-card">
<h2 style="text-align:center">📇 Input CRM</h2>

<!-- FORM ASLI TIDAK DIUBAH -->
<form method="POST" enctype="multipart/form-data">
    <input name="nama_lengkap" placeholder="Nama Lengkap" required>
    <input name="nama_outlet" placeholder="Nama Outlet">
    <input name="lokasi" placeholder="Lokasi">
    <input name="marketing" placeholder="Marketing">
    <input name="no_hp" placeholder="No HP">
    <input name="jalur" placeholder="Jalur">
    <textarea name="alamat" placeholder="Alamat"></textarea>
    <textarea name="keterangan_crm" placeholder="Keterangan"></textarea>
    <input type="file" name="foto">
    <button class="btn-save" name="submit">💾 Simpan</button>
</form>

<button class="detail-btn" onclick="openModal()">📋 Lihat Data CRM</button>
</div>
</div>

<!-- ========== MODAL DETAIL ========== -->
<div class="modal-bg" id="modalCRM">
<div class="modal-box">
<h3 style="text-align:center">📦 Data CRM</h3>

<table>
<thead>
<tr>
    <th>Nama</th>
    <th>Outlet</th>
    <th>Lokasi</th>
    <th>Marketing</th>
    <th>No HP</th>
    <th>Jalur</th>
    <th>Alamat</th>
    <th>Keterangan</th>
    <th>Foto</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q = mysqli_query($conn,"SELECT * FROM crm ORDER BY id_crm DESC");
while($r = mysqli_fetch_assoc($q)):
?>
<tr>
    <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
    <td><?= htmlspecialchars($r['nama_outlet']) ?></td>
    <td><?= htmlspecialchars($r['lokasi']) ?></td>
    <td><?= htmlspecialchars($r['marketing']) ?></td>
    <td><?= htmlspecialchars($r['no_hp']) ?></td>
    <td><?= htmlspecialchars($r['jalur']) ?></td>
    <td style="text-align:left"><?= nl2br(htmlspecialchars($r['alamat'])) ?></td>
    <td style="text-align:left"><?= nl2br(htmlspecialchars($r['keterangan_crm'])) ?></td>
    <td>
        <?php if(!empty($r['foto'])): ?>
            <img src="../assets/foto_crm/<?= $r['foto'] ?>">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td>
        <button class="edit-btn"
        onclick="openEdit(
            '<?= $r['id_crm'] ?>',
            '<?= htmlspecialchars($r['nama_lengkap']) ?>',
            '<?= htmlspecialchars($r['nama_outlet']) ?>',
            '<?= htmlspecialchars($r['lokasi']) ?>',
            '<?= htmlspecialchars($r['marketing']) ?>',
            '<?= htmlspecialchars($r['no_hp']) ?>',
            '<?= htmlspecialchars($r['jalur']) ?>',
            `<?= htmlspecialchars($r['alamat']) ?>`,
            `<?= htmlspecialchars($r['keterangan_crm']) ?>`
        )">Edit</button>

        <a class="delete-btn"
           href="?delete=<?= $r['id_crm'] ?>"
           onclick="return confirm('Hapus data ini?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<button onclick="closeModal()" class="btn-save" style="margin-top:15px">Tutup</button>
</div>
</div>

<!-- ========== MODAL EDIT ========== -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">
<h3 style="text-align:center">✏ Edit CRM</h3>

<form method="POST">
<input type="hidden" name="id_crm" id="e_id">

<input name="nama_lengkap" id="e_nama">
<input name="nama_outlet" id="e_outlet">
<input name="lokasi" id="e_lokasi">
<input name="marketing" id="e_marketing">
<input name="no_hp" id="e_hp">
<input name="jalur" id="e_jalur">
<textarea name="alamat" id="e_alamat"></textarea>
<textarea name="keterangan_crm" id="e_ket"></textarea>

<button class="btn-save" name="update">💾 Update</button>
</form>

<button onclick="closeEdit()" class="detail-btn">Tutup</button>
</div>
</div>

<script>
function openModal(){
    modalCRM.style.display='flex'
}
function closeModal(){
    modalCRM.style.display='none'
}
function openEdit(id,nama,outlet,lokasi,marketing,hp,jalur,alamat,ket){
    e_id.value=id
    e_nama.value=nama
    e_outlet.value=outlet
    e_lokasi.value=lokasi
    e_marketing.value=marketing
    e_hp.value=hp
    e_jalur.value=jalur
    e_alamat.value=alamat
    e_ket.value=ket
    modalEdit.style.display='flex'
}
function closeEdit(){
    modalEdit.style.display='none'
}
</script>

<?php include "partials/footer.php"; ?>

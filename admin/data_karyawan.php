<?php
session_start();
include "../koneksi.php";
require_once __DIR__ . "/../phpqrcode/qrlib.php";

/* ======================================================
   PROSES UPDATE KARYAWAN (VERSI AMAN)
====================================================== */
if (isset($_POST['update_karyawan'])) {

    $id     = $_POST['id_karyawan'];
    $nama   = trim($_POST['nama_karyawan']);
    $nomor  = trim($_POST['nomor_karyawan']);
    $alamat = trim($_POST['alamat']);
    $divisi = trim($_POST['divisi']);

    /* ===============================
       BATASI ALAMAT KHUSUS QR
       (DATABASE TETAP LENGKAP)
    ================================ */
    $alamatQR = mb_substr($alamat, 0, 120);

    /* ===============================
       FORMAT QR (SESUAI absen.php)
    ================================ */
    $qrText =
        $nama . "|" .
        $divisi . "|" .
        $alamatQR;

    /* ===============================
       PATH & FILE QR (WAJIB)
    ================================ */
    $qrDir = __DIR__ . "/../uploads/qrcode/";
    if (!is_dir($qrDir)) {
        mkdir($qrDir, 0777, true);
    }

    $qrFile = $nomor . ".png";
    $qrPath = $qrDir . $qrFile;

    /* ===============================
       GENERATE QR (STABIL & TAJAM)
    ================================ */
    QRcode::png(
        $qrText,
        $qrPath,
        QR_ECLEVEL_H,
        8,
        2
    );

    /* ===============================
       FOTO
    ================================ */
    $fotoName = $_POST['old_foto'];

    if (!empty($_FILES['foto_karyawan']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto_karyawan']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext, $allowed)) {
            $fotoName = $id . "." . $ext;
            $fotoPath = __DIR__ . "/../uploads/karyawan/" . $fotoName;
            move_uploaded_file($_FILES['foto_karyawan']['tmp_name'], $fotoPath);
        }
    }

    /* ===============================
       UPDATE DATABASE
    ================================ */
    $stmt = $conn->prepare("
        UPDATE karyawan SET 
            nama_karyawan   = ?,
            nomor_karyawan  = ?,
            alamat          = ?,
            divisi          = ?,
            foto_karyawan   = ?,
            barcode         = ?,
            qrcode_file     = ?
        WHERE id_karyawan = ?
    ");

    $stmt->bind_param(
        "sssssssi",
        $nama,
        $nomor,
        $alamat,      // alamat lengkap tetap disimpan
        $divisi,
        $fotoName,
        $qrFile,
        $qrFile,
        $id
    );

    $stmt->execute();
    $stmt->close();

    header("Location: data_karyawan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Data Karyawan</title>

<style>
/* =============================
   GLOBAL
============================= */
body {
    margin:0;
    padding:0;
    font-family: 'Inter', Arial, sans-serif;
    background: var(--body-bg);
}

/* =============================
   COLOR THEME
============================= */
:root {
    --orange:#f59e0b;
    --orange-dark:#d97706;
    --blue:#2563eb;
    --blue-dark:#1e40af;

    --table-text:#0f172a;
    --row-even:#f8fafc;
    --row-hover:#eef4ff;
    --border-soft:rgba(15,23,42,.08);
}

body.dark {
    --table-text:#ffffff;
    --row-even:#1e293b;
    --row-hover:#273449;
    --border-soft:rgba(255,255,255,.12);
}

/* =============================
   PAGE WRAPPER
============================= */
.page-wrapper {
    padding-left:260px;
    padding-top:32px;
    padding-right:24px;
    padding-bottom:120px;
    transition:.3s ease;
}

body.collapsed .page-wrapper {
    padding-left:90px;
}

/* =============================
   TABLE WRAPPER
============================= */
.table-wrapper {
    width:100%;
    display:flex;
    justify-content:center;
}

/* =============================
   TABLE CARD
============================= */
table {
    width:95%;
    max-width:1200px;
    border-collapse:separate;
    border-spacing:0;
    background:var(--card-bg);
    border-radius:18px;
    overflow:hidden;

    box-shadow:
        0 22px 40px rgba(0,0,0,.12),
        inset 0 1px 0 rgba(255,255,255,.7);

    margin-bottom:40px;
}

/* =============================
   HEADER TABLE (ORANGE TOP)
============================= */
thead th {
    background: linear-gradient(
        180deg,
        var(--orange),
        var(--orange-dark)
    );

    color:#fff;
    font-weight:900;
    font-size:13.5px;
    letter-spacing:.6px;
    text-transform:uppercase;

    padding:16px 14px;
    border-bottom:3px solid var(--blue-dark);
}

/* =============================
   BODY ROW
============================= */
tbody tr {
    height:78px;
    transition:.25s ease;
}

tbody tr:nth-child(even) {
    background:var(--row-even);
}

tbody tr:hover {
    background:var(--row-hover);
    transform: scale(1.004);
    box-shadow: inset 0 0 0 1px rgba(37,99,235,.25);
}

/* =============================
   CELL
============================= */
th, td {
    padding:14px 14px;
    text-align:center;
    color:var(--table-text);
    border-bottom:1px solid var(--border-soft);
}

/* =============================
   FOTO KARYAWAN
============================= */
.foto-karyawan {
    width:62px;
    height:74px;
    object-fit:cover;
    border-radius:10px;

    border:2px solid #fff;
    box-shadow:0 6px 14px rgba(0,0,0,.25);
}

/* =============================
   AKSI BUTTON
============================= */
td.aksi {
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
}

/* tombol base */
.btn-edit,
.btn-hapus,
.btn-detail {
    padding:10px 14px;
    border-radius:12px;
    font-size:12.5px;
    font-weight:900;
    color:white;
    cursor:pointer;

    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;

    box-shadow:0 6px 14px rgba(0,0,0,.25);
    transition:.3s ease;
}

/* warna tombol */
.btn-edit {
    background:linear-gradient(135deg,#3b82f6,#2563eb);
}
.btn-hapus {
    background:linear-gradient(135deg,#ef4444,#b91c1c);
}
.btn-detail {
    background:linear-gradient(135deg,#22c55e,#15803d);
}

/* hover tombol */
.btn-edit:hover,
.btn-hapus:hover,
.btn-detail:hover {
    transform:translateY(-2px);
    box-shadow:0 14px 24px rgba(0,0,0,.35);
}

/* =============================
   MODAL
============================= */
.modal {
    position: fixed;
    inset:0;
    background:rgba(15,23,42,.65);
    display:none;
    justify-content:center;
    align-items:flex-start;
    padding-top:80px;
    z-index:1000;
}

.modal-content {
    width:420px;
    background:var(--card-bg);
    padding:26px 28px;
    border-radius:20px;

    border:1px solid var(--border-soft);

    box-shadow:
        0 30px 60px rgba(0,0,0,.45),
        inset 0 1px 0 rgba(255,255,255,.7);
}

.modal-content h3 {
    margin-bottom:18px;
    text-align:center;
    font-size:20px;
    font-weight:900;

    background:linear-gradient(90deg,var(--blue),var(--orange));
    -webkit-background-clip:text;
    color:transparent;
}

/* =============================
   DETAIL FOTO
============================= */
.detail-foto {
    width:120px;
    height:150px;
    object-fit:cover;
    border-radius:14px;
    border:3px solid #fff;
    box-shadow:0 10px 20px rgba(0,0,0,.35);
}

.detail-kartu-preview {
    width:260px;
    border-radius:14px;
    border:2px solid var(--blue);
    background:#fff;
    box-shadow:0 10px 24px rgba(0,0,0,.25);
}

/* =====================================================
   FINAL FIX — MODAL EDIT KARYAWAN (LANDSCAPE & PREMIUM)
===================================================== */

/* paksa modal edit center & landscape */
#modalEdit {
    align-items: center;
    padding-top: 0;
}

/* box modal edit diperlebar */
#modalEdit .modal-content {
    width: 92%;
    max-width: 920px;
    min-height: 460px;

    padding: 28px 34px;
    border-radius: 22px;

    display: flex;
    flex-direction: column;
}

/* judul lebih tegas */
#modalEdit .modal-content h3 {
    font-size: 22px;
    font-weight: 900;
    margin-bottom: 26px;
}

/* =====================================================
   FORM GRID — 2 KOLOM RAPI
===================================================== */
#modalEdit .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px 28px;
    align-items: start;
}

/* alamat full width */
#modalEdit .form-grid .full {
    grid-column: span 2;
}

/* =====================================================
   INPUT, SELECT, TEXTAREA
===================================================== */
#modalEdit label {
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 6px;
    display: block;
    color: var(--table-text);
}

#modalEdit input,
#modalEdit select,
#modalEdit textarea {
    width: 100%;
    padding: 14px 16px;
    border-radius: 14px;
    border: 1.8px solid var(--border-soft);

    font-size: 14px;
    font-weight: 700;
    color: var(--table-text);

    background: rgba(255,255,255,.88);
    box-shadow:
        inset 0 1px 2px rgba(0,0,0,.08),
        0 6px 14px rgba(0,0,0,.10);

    transition: .25s ease;
}

/* textarea biar rapi */
#modalEdit textarea {
    resize: none;
    min-height: 54px;
    line-height: 1.45;
}

/* dark mode */
body.dark #modalEdit input,
body.dark #modalEdit select,
body.dark #modalEdit textarea {
    background: rgba(15,23,42,.88);
    color: #fff;
}

/* focus */
#modalEdit input:focus,
#modalEdit select:focus,
#modalEdit textarea:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow:
        0 0 0 3px rgba(37,99,235,.28),
        0 12px 26px rgba(0,0,0,.2);
}

/* file input dirapikan */
#modalEdit input[type="file"] {
    padding: 10px;
    background: transparent;
    box-shadow: none;
}

/* =============================
   MODAL EDIT — FOOTER BUTTON FIX
============================= */
#modalEdit .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 14px;
    margin-top: 26px;
}

/* RESET + BASE BUTTON */
#modalEdit .modal-footer button {
    all: unset; /* hapus style bawaan browser */
    cursor: pointer;

    padding: 12px 22px;
    border-radius: 14px;

    font-size: 13.5px;
    font-weight: 900;
    letter-spacing: .3px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 110px;

    transition: all .25s ease;
}

/* =============================
   TOMBOL BATAL
============================= */
#modalEdit .modal-footer .btn-detail {
    background: linear-gradient(135deg,#64748b,#334155);
    color: #fff;

    box-shadow:
        0 12px 26px rgba(51,65,85,.45),
        inset 0 1px 0 rgba(255,255,255,.25);
}

#modalEdit .modal-footer .btn-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 34px rgba(51,65,85,.6);
}

#modalEdit .modal-footer .btn-detail:active {
    transform: scale(.96);
}

/* =============================
   TOMBOL SIMPAN
============================= */
#modalEdit .modal-footer .btn-save {
    background: linear-gradient(135deg,#22c55e,#15803d);
    color: #fff;

    box-shadow:
        0 14px 30px rgba(34,197,94,.55),
        inset 0 1px 0 rgba(255,255,255,.3);
}

#modalEdit .modal-footer .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 38px rgba(34,197,94,.65);
}

#modalEdit .modal-footer .btn-save:active {
    transform: scale(.96);
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media(max-width:768px){
    #modalEdit .modal-content {
        max-width: 95%;
        padding: 22px;
    }

    #modalEdit .form-grid {
        grid-template-columns: 1fr;
    }
}

</style>
</head>
<body>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="page-wrapper">

    <div class="table-wrapper">
        <table>
            <tr>
                <th>Nomor</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Divisi</th>
                <th>Aksi</th>
            </tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY id_karyawan DESC");
while ($d = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $d['nomor_karyawan'] ?></td>

    <td>
        <img src="../uploads/karyawan/<?= $d['foto_karyawan'] ?>" class="foto-karyawan">
    </td>

    <td><?= htmlspecialchars($d['nama_karyawan']) ?></td>

    <td><?= nl2br(htmlspecialchars($d['alamat'])) ?></td>

    <td><?= htmlspecialchars($d['divisi']) ?></td>

    <td class="aksi">

        <a class="btn-detail"
            onclick="openDetail(
                '<?= $d['id_karyawan'] ?>',
                '<?= addslashes($d['nama_karyawan']) ?>',
                '<?= addslashes($d['nomor_karyawan']) ?>',
                '<?= addslashes($d['divisi']) ?>',
                '<?= $d['foto_karyawan'] ?>',
                '<?= $d['barcode'] ?>'
            )">Detail</a>

        <a class="btn-edit"
            onclick="openEditModal(
                '<?= $d['id_karyawan'] ?>',
                '<?= addslashes($d['nama_karyawan']) ?>',
                '<?= addslashes($d['nomor_karyawan']) ?>',
                '<?= addslashes($d['alamat']) ?>',
                '<?= addslashes($d['divisi']) ?>',
                '<?= $d['foto_karyawan'] ?>'
            )">Edit</a>

        <a class="btn-hapus" onclick="confirmDelete('<?= $d['id_karyawan'] ?>')">Hapus</a>

    </td>
</tr>
<?php } ?>
        </table>
    </div>

</div>

<?php include "partials/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "hapus_karyawan.php?id=" + id;
        }
    });
}

/* ===================
   MODAL EDIT
=================== */
function openEditModal(id, nama, nomor, alamat, divisi, foto) {
    document.getElementById("modalEdit").style.display = "flex";
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_nomor").value = nomor;   // FIX
    document.getElementById("edit_alamat").value = alamat;
    document.getElementById("edit_divisi").value = divisi;
    document.getElementById("old_foto").value = foto;
}
function closeEditModal() {
    document.getElementById("modalEdit").style.display = "none";
}

/* ===================
   MODAL DETAIL
=================== */
function openDetail(id, nama, nomor, divisi, foto, barcode) {

    document.getElementById("detail_nama").innerText = nama;
    document.getElementById("detail_nomor").innerText = nomor;
    document.getElementById("detail_divisi").innerText = divisi;

    document.getElementById("detail_foto").src =
        "../uploads/karyawan/" + foto;

    document.getElementById("detail_kartu").src =
        "generate_kartu.php?id=" + id;

    document.getElementById("detail_download").href =
        "generate_kartu.php?id=" + id;

    document.getElementById("modalDetail").style.display = "flex";
}
function closeDetail() {
    document.getElementById("modalDetail").style.display = "none";
}
</script>

<!-- ==========================
     MODAL EDIT
========================== -->
<div class="modal" id="modalEdit">
    <div class="modal-content">
        <h3>Edit Karyawan</h3>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_karyawan" id="edit_id">
            <input type="hidden" name="old_foto" id="old_foto">

            <label>Nama</label>
            <input type="text" name="nama_karyawan" id="edit_nama" required>

            <label>Nomor Karyawan</label> <!-- FIX -->
            <input type="text" name="nomor_karyawan" id="edit_nomor" required>

            <label>Alamat</label>
            <textarea name="alamat" id="edit_alamat" required></textarea>

            <label>Divisi</label>
            <select name="divisi" id="edit_divisi" required>
                <option value="Staff">Staff</option>
                <option value="Marketing">Marketing</option>
                <option value="Produksi">Produksi</option>
                <option value="Teknisi">Teknisi</option>
                <option value="Tukang masak & Bersih-bersih">Tukang masak & Bersih-bersih</option>
                <option value="Retail">Retail</option>
                <option value="Driver / Helper">Driver / Helper</option>
            </select>

            <label>Foto Baru (opsional)</label>
            <input type="file" name="foto_karyawan" accept="image/*">

            <div class="modal-footer">
    <button type="button"
            class="btn btn-detail"
            onclick="closeEditModal()">
        ✖ Batal
    </button>

    <button type="submit"
            name="update_karyawan"
            class="btn btn-save">
        💾 Simpan
    </button>
</div>
        </form>
    </div>
</div>

<!-- ==========================
     MODAL DETAIL KARYAWAN
========================== -->
<div class="modal" id="modalDetail">
    <div class="modal-content">
        <h3>Detail Karyawan</h3>

        <center>
            <img id="detail_foto" class="detail-foto">
        </center>

        <p><strong>Nama:</strong> <span id="detail_nama"></span></p>
        <p><strong>Nomor:</strong> <span id="detail_nomor"></span></p>
        <p><strong>Divisi:</strong> <span id="detail_divisi"></span></p>

        <h4 style="margin-top:20px;">Preview Kartu</h4>

        <center>
            <img id="detail_kartu" class="detail-kartu-preview">
        </center>

        <a id="detail_download" 
           class="btn-detail" 
           style="margin-top:10px;display:block;text-align:center;"
           download>
           Download Kartu
        </a>

        <button onclick="closeDetail()" 
                style="width:100%;padding:10px;border:none;border-radius:8px;
                       background:#555;color:white;margin-top:10px;">
            Tutup
        </button>
    </div>
</div>

</body>
</html>

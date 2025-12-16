<?php
include "../koneksi.php";
require_once __DIR__ . "/../phpqrcode/qrlib.php";

/* ======================================================
   PROSES UPDATE KARYAWAN
====================================================== */
if (isset($_POST['update_karyawan'])) {

    $id     = $_POST['id_karyawan'];
    $nama   = $_POST['nama_karyawan'];
    $nomor  = $_POST['nomor_karyawan']; // FIX DITAMBAHKAN
    $alamat = $_POST['alamat'];
    $divisi = $_POST['divisi'];

    /* ===== QR BARU WAJIB ADA NOMOR ===== */
    $qrText = "Nama: $nama\nNomor: $nomor\nID: $id\nDivisi: $divisi";
    $qrFile = "QR_" . $id . "_" . time() . ".png";
    $qrPath = __DIR__ . "/../uploads/qrcode/" . $qrFile;

    QRcode::png($qrText, $qrPath, QR_ECLEVEL_H, 4, 2);

    /* ===== FOTO ===== */
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

    /* ===== UPDATE DATABASE ===== */
    $stmt = $conn->prepare("UPDATE karyawan SET 
        nama_karyawan=?, nomor_karyawan=?, alamat=?, divisi=?, foto_karyawan=?, barcode=?, qrcode_file=? 
        WHERE id_karyawan=?");

    $stmt->bind_param("sssssssi", 
        $nama, $nomor, $alamat, $divisi, $fotoName, $qrFile, $qrFile, $id
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
   GLOBAL (AMAN)
============================= */
body {
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background:var(--body-bg);
}

/* LIGHT MODE */
:root {
    --table-text:#1a1a1a;
    --table-border:#e0e0e0;
    --row-even:#fafafa;
    --row-hover:#eef6ff;
}

/* DARK MODE */
body.dark {
    --table-text:#ffffff;
    --table-border:#ffba27;
    --row-even:#1b2238;
    --row-hover:#2b3454;
}

/* =============================
   WRAPPER KONTEN
============================= */
.page-wrapper {
    padding-left:260px;
    padding-top:30px;
    padding-right:20px;
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
   TABLE (LEBIH ELEGAN, TAPI AMAN)
============================= */
table {
    width:95%;
    max-width:1100px;
    border-collapse:collapse;
    background:var(--card-bg);
    border-radius:14px;
    overflow:hidden;
    margin-bottom:40px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
}

/* =============================
   ROW & CELL
============================= */
tr {
    height:78px;
    transition:background .2s ease;
}

tr:nth-child(even) {
    background:var(--row-even);
}

tr:hover {
    background:var(--row-hover);
}

th, td {
    padding:14px 16px;
    border-bottom:1px solid var(--table-border);
    text-align:center;
    color:var(--table-text);
}

/* =============================
   HEADER TABLE (ORANYE RAPI)
============================= */
th {
    background: linear-gradient(135deg,#ffb347,#ff9800);
    color:#fff !important;
    font-weight:700;
    font-size:13px;
    letter-spacing:.4px;
    border-bottom:2px solid #d69300;
}

/* =============================
   FOTO (HALUS, TANPA OVERFLOW)
============================= */
.foto-karyawan {
    width:60px;
    height:70px;
    object-fit:cover;
    border-radius:8px;
    border:2px solid #ffb347;
}

/* =============================
   ACTION BUTTON (MEWAH TAPI AMAN)
============================= */
td.aksi {
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
}

.btn-edit,
.btn-hapus,
.btn-detail {
    height:34px;
    padding:0 16px;
    border-radius:8px;
    font-size:13px;
    color:white;
    font-weight:700;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    transition:.2s ease;
    box-shadow:0 4px 10px rgba(0,0,0,.25);
}

.btn-edit {
    background:linear-gradient(135deg,#4facfe,#007bff);
}

.btn-hapus {
    background:linear-gradient(135deg,#ff6b6b,#c0392b);
}

.btn-detail {
    background:linear-gradient(135deg,#2ecc71,#1e8449);
}

.btn-edit:hover,
.btn-hapus:hover,
.btn-detail:hover {
    transform:translateY(-2px);
    box-shadow:0 8px 18px rgba(0,0,0,.35);
}

/* =============================
   MODAL (TETAP AMAN)
============================= */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: flex-start;
    padding-top: 80px;
    z-index: 1000;
}

.modal-content {
    width: 380px;
    background: var(--card-bg);
    padding: 24px 26px;
    border-radius: 14px;
    border: 1px solid var(--table-border);
}

.modal-content h3 {
    margin: 0 0 16px;
    text-align:center;
}

.detail-foto {
    width:110px;
    height:135px;
    border-radius: 10px;
    object-fit: cover;
    border:2px solid #ddd;
}

.detail-kartu-preview {
    width: 250px;
    border-radius: 10px;
    border: 2px solid #444;
    background:#fff;
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

            <div style="text-align:right;margin-top:15px;">
                <button type="button" onclick="closeEditModal()">Batal</button>
                <button type="submit" name="update_karyawan">Simpan</button>
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

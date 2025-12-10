<?php
include "../koneksi.php";
require_once __DIR__ . "/../phpqrcode/qrlib.php";

/* ======================================================
   PROSES UPDATE KARYAWAN
====================================================== */
if (isset($_POST['update_karyawan'])) {

    $id     = $_POST['id_karyawan'];
    $nama   = $_POST['nama_karyawan'];
    $alamat = $_POST['alamat'];
    $divisi = $_POST['divisi'];

    // buat QR baru
    $qrText = "Nama: $nama\nID: $id\nDivisi: $divisi\nAlamat: $alamat";
    $qrFile = "QR_" . $id . "_" . time() . ".png";
    $qrPath = __DIR__ . "/../uploads/qrcode/" . $qrFile;

    QRcode::png($qrText, $qrPath, QR_ECLEVEL_H, 4, 2);

    // FOTO
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

    // UPDATE DATABASE
    $stmt = $conn->prepare("UPDATE karyawan SET 
        nama_karyawan=?, alamat=?, divisi=?, foto_karyawan=?, barcode=?, qrcode_file=? 
        WHERE id_karyawan=?");

    $stmt->bind_param("ssssssi", $nama, $alamat, $divisi, $fotoName, $qrFile, $qrFile, $id);
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
    font-family:Arial;
    background:var(--body-bg);
}

/* teks default terang */
:root {
    --table-text:#000000; /* hitam */
    --table-border:#1a1a1a;
    --row-even:#fafafa;
    --row-hover:#f0f7ff;
}

/* teks default gelap */
body.dark {
    --table-text:#ffffff; /* PUTIH */
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
   TABLE
============================= */
table {
    width:95%;
    max-width:1100px;
    border-collapse:collapse;
    background:var(--card-bg);
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 14px rgba(0,0,0,0.12);
    margin-bottom:40px;
}

/* baris agar konsisten */
tr {
    height:78px;
}

/* semua teks tabel mengikuti tema */
th, td {
    padding:12px 14px;
    border-bottom:1px solid var(--table-border);
    text-align:center;
    color:var(--table-text); /* <-- ini bikin putih/hitam otomatis */
}

/* =============================
   HEADER TABLE
============================= */
th {
    background: var(--accent);
    color:white !important;
    font-weight:bold;
    border-bottom:2px solid #d69300;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

/* =============================
   ROW EFFECT
============================= */
tr:nth-child(even) {
    background: var(--row-even);
}

tr:hover {
    background: var(--row-hover);
}

/* =============================
   FOTO
============================= */
.foto-karyawan {
    width:60px;
    height:70px;
    object-fit:cover;
    border-radius:6px;
    border:2px solid #ddd;
}

/* =============================
   ACTION BUTTON (Edit / Hapus)
============================= */
td.aksi {
    display:flex;
    justify-content:center;   /* horizontal center */
    align-items:center;       /* vertical center (FIXED) */
    gap:10px;
    border-bottom: inherit;
}

/* BUTTON STYLE */
.btn-edit,
.btn-hapus {
    padding:7px 13px;
    border-radius:6px;
    font-size:13px;
    color:white;
    font-weight:bold;
    cursor:pointer;
    display:inline-block;
    text-decoration:none;
    height:32px;              /* memastikan seragam */
    display:flex;
    align-items:center;        /* vertikal tengah */
    justify-content:center;
}

.btn-edit { background:#3498db; }
.btn-edit:hover { background:#277fbd; }

.btn-hapus { background:#e74c3c; }
.btn-hapus:hover { background:#c0392b; }

/* ============================
   FONT STYLE GLOBAL
============================ */
body, input, textarea, select, button {
    font-family: "Segoe UI", "Inter", "Poppins", Arial, sans-serif;
    letter-spacing: 0.3px;
}

/* ============================
   MODAL BACKDROP
============================ */
.modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: flex-start;
    padding-top: 80px;
    overflow-y: auto;
    z-index: 1000;
    animation: fadeIn .25s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* ============================
   MODAL CONTENT
============================ */
.modal-content {
    width: 380px;
    background: var(--card-bg);
    color: var(--table-text);
    padding: 24px 26px;
    border-radius: 14px;
    border: 1px solid var(--table-border);
    box-shadow: 0 8px 26px rgba(0,0,0,0.25);
    animation: slideDown .25s ease;
}

@keyframes slideDown {
    from { transform: translateY(-20px); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
}

/* ============================
   TEKS JUDUL
============================ */
.modal-content h3 {
    margin: 0 0 16px 0;
    font-size: 20px;
    font-weight: 600;
    text-align: center;
    color: var(--table-text);
}

/* ============================
   INPUT FIELD
============================ */
.modal-content input,
.modal-content textarea,
.modal-content select {
    width: 95%;
    padding: 12px 14px;
    margin: 8px 0 14px 0;
    border-radius: 10px;
    font-size: 15px;
    background: var(--body-bg);
    color: var(--table-text);
    border: 1px solid var(--table-border);
    transition: .25s ease;
}

/* Fokus lembut */
.modal-content input:focus,
.modal-content textarea:focus,
.modal-content select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 8px rgba(0, 136, 255, 0.35);
}

/* Textarea tetap lembut */
textarea {
    resize: vertical;
    min-height: 90px;
}

/* ============================
   BUTTON AREA
============================ */
.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

/* base button */
.modal-buttons button {
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    font-size: 15px;
    cursor: pointer;
    font-weight: 600;
    letter-spacing: 0.4px;
}

/* tombol abu */
.btn-close,
.btn-no {
    background: #787878;
    color: white;
}
.btn-close:hover,
.btn-no:hover {
    background: #5f5f5f;
}

/* tombol simpan */
.btn-save {
    background: #3498db;
    color: white;
}
.btn-save:hover {
    background: #277fbd;
}

/* tombol hapus */
.btn-yes {
    background: #e74c3c;
    color: white;
}
.btn-yes:hover {
    background: #c0392b;
}
</style>
</head>
<body>

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
                $id = $d['id_karyawan'];
            ?>
            <tr>
                <td><?= htmlspecialchars($d['nomor_karyawan']) ?></td>

                <td>
                    <img src="../uploads/karyawan/<?= $d['foto_karyawan'] ?>" class="foto-karyawan">
                </td>

                <td><?= htmlspecialchars($d['nama_karyawan']) ?></td>

                <td><?= nl2br(htmlspecialchars($d['alamat'])) ?></td>

                <td><?= htmlspecialchars($d['divisi']) ?></td>

                <td class="aksi">
                    <a class="btn-edit"
                        onclick="openEditModal(
                            '<?= $id ?>',
                            '<?= addslashes($d['nama_karyawan']) ?>',
                            '<?= addslashes($d['alamat']) ?>',
                            '<?= addslashes($d['divisi']) ?>',
                            '<?= $d['foto_karyawan'] ?>'
                        ); return false;">
                        Edit
                    </a>

                    <a class="btn-hapus" onclick="confirmDelete('<?= $id ?>'); return false;">
                        Hapus
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

<?php include "partials/footer.php"; ?>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* ==========================
   DELETE CONFIRM — SWEETALERT
=========================== */
function confirmDelete(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Tidak",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "hapus_karyawan.php?id=" + id;
        }
    });
}

/* ==========================
   MODAL EDIT
=========================== */
function openEditModal(id, nama, alamat, divisi, foto) {
    document.body.classList.add("modal-open");

    document.getElementById("modalEdit").style.display = "flex";
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_alamat").value = alamat;
    document.getElementById("edit_divisi").value = divisi;
    document.getElementById("old_foto").value = foto;
}

function closeEditModal() {
    document.body.classList.remove("modal-open");
    document.getElementById("modalEdit").style.display = "none";
}
</script>

<!-- ==========================
     MODAL EDIT FORM
========================== -->
<div class="modal" id="modalEdit">
    <div class="modal-content">
        <h3>Edit Karyawan</h3>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_karyawan" id="edit_id">
            <input type="hidden" name="old_foto" id="old_foto">

            <label>Nama</label>
            <input type="text" name="nama_karyawan" id="edit_nama" required>

            <label>Alamat</label>
            <textarea name="alamat" id="edit_alamat" rows="3" required></textarea>

            <label>Divisi</label>
            <select name="divisi" id="edit_divisi" size="5" style="overflow-y:auto;" required>
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

            <div class="modal-buttons">
                <button type="button" class="btn-close" onclick="closeEditModal()">Batal</button>
                <button type="submit" name="update_karyawan" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

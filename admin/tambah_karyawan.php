<?php
include "partials/header.php";
include "partials/sidebar.php";
include "../koneksi.php";

$fotoDir = "../uploads/karyawan/";
$qrcodeDir = "../uploads/qrcode/";
if (!is_dir($fotoDir)) mkdir($fotoDir, 0777, true);
if (!is_dir($qrcodeDir)) mkdir($qrcodeDir, 0777, true);

require __DIR__ . "/../phpqrcode/qrlib.php";

function generateNomorKaryawan($conn) {
    $q = $conn->query("SELECT nomor_karyawan FROM karyawan ORDER BY id_karyawan DESC LIMIT 1");
    if ($q->num_rows > 0) {
        $last = $q->fetch_assoc()['nomor_karyawan'];
        $num = intval(substr($last, 3)) + 1;
        return "KRW" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }
    return "KRW0001";
}

function buatQRCode($text, $file) {
    QRcode::png($text, $file, QR_ECLEVEL_H, 6, 2);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama   = $_POST['nama_karyawan'];
    $alamat = $_POST['alamat'];
    $divisi = $_POST['divisi'];

    $nomor = generateNomorKaryawan($conn);
    $qrFile = $nomor . ".png";
    $qrPath = $qrcodeDir . $qrFile;

    buatQRCode($nomor, $qrPath);

    $foto = null;
    if (!empty($_FILES['foto_karyawan']['name'])) {
        $ext = pathinfo($_FILES['foto_karyawan']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array(strtolower($ext), $allowed)) {
            $fotoName = $nomor . "." . $ext;
            move_uploaded_file($_FILES['foto_karyawan']['tmp_name'], $fotoDir . $fotoName);
            $foto = $fotoName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO karyawan 
        (nomor_karyawan, nama_karyawan, alamat, divisi, barcode, foto_karyawan)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nomor, $nama, $alamat, $divisi, $qrFile, $foto);
    $stmt->execute();
    $stmt->close();

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Karyawan berhasil ditambahkan',
        confirmButtonColor: '#2563eb'
    }).then(() => {
        window.location.href = 'tambah_karyawan.php';
    });
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Karyawan</title>

<style>
/* =====================================================
   PAGE LOCK (NO SCROLL GLOBAL)
===================================================== */
html, body {
    height: 100%;
    margin: 0;
    overflow: hidden;
}

/* =====================================================
   WRAPPER UTAMA (AUTO CENTER SEMUA DEVICE)
===================================================== */
.page-wrapper {
    position: fixed;
    top: var(--header-height);
    left: 280px;
    right: 0;
    bottom: var(--footer-height);

    display: flex;
    justify-content: center;
    align-items: center; /* 🔥 KUNCI UTAMA */

    padding: 24px;
    box-sizing: border-box;

    transition: left .35s ease;
}

body.collapsed .page-wrapper {
    left: 100px;
}

/* =====================================================
   CARD FORM (PREMIUM COMPACT)
===================================================== */
.card-form,
.form-card {
    width: 100%;
    max-width: 640px;

    padding: 36px 40px;
    border-radius: 24px;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.92),
        rgba(255,255,255,0.82)
    );

    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    border: 1.5px solid rgba(255,255,255,0.6);

    box-shadow:
        0 28px 55px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.7);

    position: relative;
}

/* Accent Glow */
.card-form::before,
.form-card::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 160px;
    height: 160px;
    background: radial-gradient(circle, rgba(37,99,235,.18), transparent 70%);
    border-radius: 50%;
}

/* =====================================================
   DARK MODE
===================================================== */
body.dark .card-form,
body.dark .form-card {
    background: linear-gradient(
        180deg,
        rgba(18,30,60,0.95),
        rgba(10,18,36,0.92)
    );
    border: 1px solid rgba(90,169,255,0.25);
    box-shadow:
        0 24px 45px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.12);
}

/* =====================================================
   TITLE
===================================================== */
.card-form h3,
.form-card h2 {
    text-align: center;
    font-size: 24px;
    font-weight: 900;
    margin-bottom: 28px;
    letter-spacing: .5px;

    background: linear-gradient(90deg,#2563eb,#fbbf24);
    -webkit-background-clip: text;
    color: transparent;
}

/* =====================================================
   FORM GROUP
===================================================== */
.form-group {
    margin-bottom: 14px;
}

/* =====================================================
   LABEL
===================================================== */
label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

body.dark label {
    color: #e5e7eb;
}

/* =====================================================
   INPUT / SELECT / TEXTAREA
===================================================== */
input,
select,
textarea {
    width: 100%;
    padding: 11px 14px;
    border-radius: 11px;
    border: 1.6px solid #cbd5e1;
    font-size: 13.5px;

    background: rgba(255,255,255,0.92);
    transition: .3s ease;
}

textarea {
    resize: none;
    height: 70px;
}

body.dark input,
body.dark select,
body.dark textarea {
    background: rgba(10,18,36,0.85);
    border-color: rgba(90,169,255,.35);
    color: #fff;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.25);
    outline: none;
}

/* =====================================================
   FOTO UPLOAD
===================================================== */
.foto-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
}

.foto-upload {
    padding: 12px 16px;
    border-radius: 14px;
    border: 1.6px dashed #94a3b8;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    transition: .3s ease;
}

.foto-upload:hover {
    background: rgba(37,99,235,.06);
}

.foto-upload input {
    display: none;
}

#previewFoto {
    width: 90px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    display: none;
}

#previewFoto.show {
    display: block;
}

/* =====================================================
   BUTTON (BIRU + RING ORANYE)
===================================================== */
button[type="submit"],
.btn-submit {
    margin-top: 30px;
    width: 100%;
    padding: 14px;
    border-radius: 18px;

    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: #fff;

    font-size: 15px;
    font-weight: 900;
    letter-spacing: .6px;

    border: 2.5px solid #fbbf24;

    box-shadow:
        0 0 0 4px rgba(251,191,36,.35),
        0 18px 30px rgba(37,99,235,.45);

    cursor: pointer;
    transition: .35s ease;
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow:
        0 0 0 6px rgba(251,191,36,.55),
        0 28px 45px rgba(37,99,235,.6);
}

/* =====================================================
   MOBILE (STABIL SEMUA HP)
===================================================== */
@media (max-width: 768px) {
    .page-wrapper {
        left: 0;
        padding: 16px;
    }

    .card-form,
    .form-card {
        padding: 26px 22px;
    }
}

</style>

</head>
<body>

<div class="main-content">
    <div class="page-content">
        <div class="form-card">
            <h2>Tambah Karyawan</h2>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Karyawan</label>
                    <input type="text" name="nama_karyawan" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" required></textarea>
                </div>

                <div class="form-group">
                    <label>Divisi</label>
                    <select name="divisi" required>
                        <option value="">-- Pilih Divisi --</option>
                        <option>Staff</option>
                        <option>Marketing</option>
                        <option>Produksi</option>
                        <option>Teknisi</option>
                        <option>Tukang masak & Bersih-bersih</option>
                        <option>Retail</option>
                        <option>Driver / Helper</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto Karyawan</label>
                    <div class="foto-wrapper">
                        <label class="foto-upload">
                            📷 Pilih Foto
                            <input type="file" name="foto_karyawan" accept="image/*" onchange="previewImage(event)">
                        </label>
                        <img id="previewFoto">
                    </div>
                </div>

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>

<script>
function previewImage(event) {
    const img = document.getElementById('previewFoto');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.classList.add("show");
}
</script>

</body>
</html>

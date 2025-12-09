<?php include "partials/sidebar.php"; ?>
<?php include "../koneksi.php"; ?>

<?php
// FOLDER AUTO
$fotoDir = "../uploads/karyawan/";
$qrcodeDir = "../uploads/qrcode/";

if (!is_dir($fotoDir)) mkdir($fotoDir, 0777, true);
if (!is_dir($qrcodeDir)) mkdir($qrcodeDir, 0777, true);

// Nomor karyawan
function generateNomorKaryawan($conn) {
    $q = $conn->query("SELECT nomor_karyawan FROM karyawan ORDER BY id_karyawan DESC LIMIT 1");
    if ($q->num_rows > 0) {
        $last = $q->fetch_assoc()['nomor_karyawan'];
        $num = intval(substr($last, 3)) + 1;
        return "KRW" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }
    return "KRW0001";
}

// QR
require __DIR__ . "/../phpqrcode/qrlib.php";
function buatQRCode($text, $file) {
    QRcode::png($text, $file, QR_ECLEVEL_H, 6, 2);
}

$message = "";

// ========== PROSES SUBMIT ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama   = $_POST['nama_karyawan'];
    $alamat = $_POST['alamat'];
    $divisi = $_POST['divisi'];

    $nomor = generateNomorKaryawan($conn);
    $qrFile = $nomor . ".png";
    $qrPath = $qrcodeDir . $qrFile;

    buatQRCode($nomor, $qrPath);

    $foto = null;

    $stmt = $conn->prepare("INSERT INTO karyawan 
        (nomor_karyawan, nama_karyawan, alamat, divisi, barcode, foto_karyawan)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nomor, $nama, $alamat, $divisi, $qrFile, $foto);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>
            Karyawan berhasil ditambahkan! Nomor: <b>$nomor</b><br>
            <a href='$qrPath' download>Download QR</a>
        </div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal menambah karyawan</div>";
    }

    $stmt->close();
}
?>

<style>

/* ==========================
   BACKGROUND & TEXT COLOR
========================== */
body {
    background: var(--body-bg) !important;
    color: black !important;
    transition: .3s ease;
}

/* Mode gelap → teks kuning */
body.dark {
    color: #ffcc00 !important;
}

/* Semua tulisan ikut berubah */
body.dark h2,
body.dark label,
body.dark p,
body.dark span,
body.dark a,
body.dark div,
body.dark input,
body.dark select,
body.dark textarea {
    color: #ffcc00 !important;
}

/* Placeholder ikut kuning */
body.dark ::placeholder {
    color: #ffd766 !important;
}

/* ==========================
   DASHBOARD LAYOUT
========================== */
.dashboard {
    margin-left: 260px;
    padding: 40px 50px;
    transition: .3s;
}
body.collapsed .dashboard { margin-left: 90px; }

/* ==========================
   FORM CARD
========================== */
.form-card {
    background: var(--card-bg);
    padding: 40px;
    border-radius: 22px;
    max-width: 750px;
    margin: 40px auto;
    box-shadow: 0 10px 30px rgba(0,0,0,.12);
    transition: .3s ease;
}

/* ==========================
   TITLE
========================== */
.form-card h2 {
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--title-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

body.dark .form-card h2 {
    color: #ffcc00 !important;
}

.form-card h2::before {
    content: "➕";
    font-size: 26px;
    color: var(--title-color);
}

body.dark .form-card h2::before {
    color: #ffcc00 !important;
}

/* ==========================
   INPUT, SELECT, TEXTAREA
========================== */
.form-group {
    margin-bottom: 18px;
}

.form-control,
.form-select {
    border-radius: 12px !important;
    padding: 12px !important;
    width: 100%;
    border: 1px solid #cfcfcf !important;
    color: black;
}

body.dark .form-control,
body.dark .form-select,
body.dark textarea {
    background: #0f1a33;
    color: #ffcc00 !important;
    border: 1px solid #4da3ff !important;
}

/* ==========================
   BUTTON
========================== */
button {
    background: #00AEEF;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: bold;
    transition: .25s;
}
button:hover {
    background: #008FC7;
}

</style>

<div class="dashboard">

    <div class="form-card">
        <h2>Tambah Karyawan</h2>

        <?= $message ?>

        <form method="post">

            <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" name="nama_karyawan" class="form-control" required placeholder="Masukkan nama">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required placeholder="Masukkan alamat"></textarea>
            </div>

            <div class="form-group">
                <label>Divisi</label>
                <select name="divisi" class="form-select" required>
                    <option value="">-- Pilih Divisi --</option>
                    <option value="Staff">Staff</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Produksi">Produksi</option>
                    <option value="Teknisi">Teknisi</option>
                    <option value="Tukang masak & Bersih-bersih">Tukang masak & Bersih-bersih</option>
                    <option value="Retail">Retail</option>
                    <option value="Driver / Helper">Driver / Helper</option>
                </select>
            </div>

            <button type="submit">Simpan</button>

        </form>
    </div>

</div>

<?php include "partials/footer.php"; ?>

<?php include "partials/sidebar.php"; ?>
<?php include "../koneksi.php"; ?>
<link rel="stylesheet" href="tambah_karyawan.css">

<!-- ====== WRAPPER YANG WAJIB ADA ====== -->
<div class="main-content">
    <div class="page-content">

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
$showPreview = false;
$previewData = [];


// ========== PROSES SUBMIT ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama   = $_POST['nama_karyawan'];
    $alamat = $_POST['alamat'];
    $divisi = $_POST['divisi'];

    $nomor = generateNomorKaryawan($conn);
    $qrFile = $nomor . ".png";
    $qrPath = $qrcodeDir . $qrFile;

    buatQRCode($nomor, $qrPath);

    // --- UPLOAD FOTO ---
    $foto = null;

    if (!empty($_FILES['foto_karyawan']['name'])) {

        $ext = pathinfo($_FILES['foto_karyawan']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($ext), $allowed)) {
            $message = "<div class='alert alert-danger'>Format foto tidak valid! Hanya JPG, PNG, WEBP.</div>";
        } else {

            $fotoName = $nomor . "." . $ext;
            $fotoPath = $fotoDir . $fotoName;

            if (move_uploaded_file($_FILES['foto_karyawan']['tmp_name'], $fotoPath)) {
                $foto = $fotoName;
            } else {
                $message = "<div class='alert alert-danger'>Foto gagal diupload!</div>";
            }
        }
    }

    // INSERT DATABASE
    $stmt = $conn->prepare("INSERT INTO karyawan 
        (nomor_karyawan, nama_karyawan, alamat, divisi, barcode, foto_karyawan)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nomor, $nama, $alamat, $divisi, $qrFile, $foto);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>
            Karyawan berhasil ditambahkan! Nomor: <b>$nomor</b>
        </div>";

        // Kirim data ke modal preview
        $showPreview = true;
        $previewData = [
            "nama" => $nama,
            "alamat" => $alamat,
            "divisi" => $divisi,
            "nomor" => $nomor,
            "foto" => $foto,
            "qr" => $qrFile
        ];

    } else {
        $message = "<div class='alert alert-danger'>Gagal menambah karyawan</div>";
    }

    $stmt->close();
}
?>

<div class="container-fluid">
    
    <div class="form-card">
        <h2>Tambah Karyawan</h2>

        <?= $message ?>

        <form method="post" enctype="multipart/form-data">

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

            <!-- INPUT FOTO -->
            <div class="form-group">
                <label>Foto Karyawan</label>

                <div class="foto-wrapper">
                    
                    <label class="foto-upload">
                        📷 Klik untuk memilih foto
                        <input type="file" class="form-control" name="foto_karyawan" accept="image/*"onchange="previewImage(event)">

                    </label>

                    <img id="previewFoto">

                </div>
            </div>

            <button type="submit">Simpan</button>

        </form>
    </div>

</div>


<!-- ============================= MODAL PREVIEW ============================== -->

<div id="modalPreview" class="modalCustom">

    <div class="modalContentCustom">

        <span class="closeModal" onclick="closeModal()">X</span>

        <h2>Preview Kartu Nama</h2>

        <div id="cardPreviewContainer">

            <div class="foto">
                <img src="../uploads/karyawan/<?= $previewData['foto'] ?? '' ?>" id="fotoCard">
            </div>

            <div class="detail">
                <h3><?= $previewData['nama'] ?? '' ?></h3>
                <p><b>Nomor:</b> <?= $previewData['nomor'] ?? '' ?></p>
                <p><b>Alamat:</b> <?= $previewData['alamat'] ?? '' ?></p>
                <p><b>Divisi:</b> <?= $previewData['divisi'] ?? '' ?></p>
            </div>

            <img class="qr-mini" src="../uploads/qrcode/<?= $previewData['qr'] ?? '' ?>" id="qrCard">
        </div>

        <button class="download-btn" onclick="downloadCard()">Download JPG</button>

    </div>

</div>

<script>
function previewFoto(event) {
    const img = document.getElementById('previewFoto');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = 'block';
}

// TAMPILKAN MODAL JIKA SUBMIT BERHASIL
<?php if ($showPreview): ?>
document.getElementById('modalPreview').style.display = 'block';
<?php endif; ?>

function closeModal() {
    document.getElementById('modalPreview').style.display = 'none';
}

// DOWNLOAD KARTU NAMA DALAM FORMAT JPG
function downloadCard() {
    html2canvas(document.querySelector("#cardPreviewContainer"), {
        scale: 3
    }).then(canvas => {
        let link = document.createElement("a");
        link.download = "kartu_karyawan.jpg";
        link.href = canvas.toDataURL("image/jpeg");
        link.click();
    });
}
</script>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('previewFoto');
        preview.src = reader.result;

        // Tambahkan kelas untuk animasi
        preview.classList.add("show");
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>


<!-- CDN html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

        </div> <!-- dashboard -->
    </div> <!-- page-content -->
</div> <!-- main-content -->

<?php include "partials/footer.php"; ?>

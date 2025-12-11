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
            $fotoPath = $fotoDir . $fotoName;
            move_uploaded_file($_FILES['foto_karyawan']['tmp_name'], $fotoPath);
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
        confirmButtonColor: '#3085d6'
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
<link rel="stylesheet" href="tambah_karyawan.css">
</head>
<body>

<div class="main-content">
<div class="page-content">
<div class="container-fluid">

<div class="form-card">
    <h2>Tambah Karyawan</h2>

    <form method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Karyawan</label>
            <input type="text" name="nama_karyawan" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Divisi</label>
            <select name="divisi" class="form-select" required>
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
                    📷 Klik untuk memilih foto
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
</div>

<?php include "partials/footer.php"; ?>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const img = document.getElementById('previewFoto');
        img.src = reader.result;
        img.classList.add("show");
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>

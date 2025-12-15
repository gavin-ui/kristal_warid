<?php
session_start();
include "../koneksi.php";

// Pastikan user login dan role karyawan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: ../login.php"); 
    exit;
}

$id_karyawan = $_SESSION['id_karyawan'];
$nama = $_SESSION['nama_karyawan'];
$nomor = $_SESSION['nomor_karyawan'];

// Ambil data lengkap karyawan
$q = $conn->prepare("SELECT * FROM karyawan WHERE id_karyawan=?");
$q->bind_param("i", $id_karyawan);
$q->execute();
$data = $q->get_result()->fetch_assoc();

$divisi = $data['divisi'] ?? "-";
$foto = $data['foto'] ?? null;

// Jika tidak ada foto, gunakan avatar default
$fotoURL = ($foto && file_exists("../uploads/karyawan/" . $foto))
    ? "../uploads/karyawan/" . $foto
    : "https://ui-avatars.com/api/?name=" . urlencode($nama) . "&background=0b62d6&color=fff";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Karyawan</title>
<link rel="stylesheet" href="../assets/style.css">

<style>
.profile-box{
    display:flex;
    gap:20px;
    align-items:center;
    padding:20px;
}
.profile-box img{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid var(--accent);
}
.profile-info h2{
    margin:0;
    font-size:26px;
    font-weight:800;
    color:var(--title-color);
}
.profile-info p{
    margin:5px 0 0;
    font-size:16px;
    color:#666;
}
</style>

</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<div class="page-wrapper">

<div class="card">

    <div class="profile-box">
        <img src="<?php echo $fotoURL; ?>">
        <div class="profile-info">
            <h2>Selamat Datang, <?php echo $nama; ?> 👋</h2>
            <p>Karyawan Divisi <b><?php echo $divisi; ?></b></p>
        </div>
    </div>

    <hr style="margin:15px 0; opacity:0.4;">

    <p>Gunakan menu di samping untuk melakukan absen atau melihat informasi lainnya.</p>

</div>

</div>

<?php include "../partials/footer.php"; ?>
</body>
</html>

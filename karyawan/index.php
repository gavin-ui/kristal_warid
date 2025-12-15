<?php
session_start();
include "../koneksi.php";

// Pastikan user login dan role karyawan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: ../login/login.php"); 
    exit;
}

$id_karyawan = $_SESSION['id_karyawan'];
$nama = $_SESSION['nama_karyawan'];

// Ambil data lengkap karyawan
$q = $conn->prepare("SELECT * FROM karyawan WHERE id_karyawan=?");
$q->bind_param("i", $id_karyawan);
$q->execute();
$data = $q->get_result()->fetch_assoc();

$divisi = $data['divisi'] ?? "-";
$foto   = $data['foto'] ?? null;

// Avatar fallback
$fotoURL = ($foto && file_exists("../uploads/karyawan/" . $foto))
    ? "../uploads/karyawan/" . $foto
    : "https://ui-avatars.com/api/?name=" . urlencode($nama) . "&background=2563eb&color=fff";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Karyawan</title>
<link rel="stylesheet" href="../assets/style.css">

<style>
/* ===============================
   PAGE WRAPPER (ANTI NABRAK SIDEBAR)
================================ */
.page-wrapper{
    margin-left:260px;
    padding:110px 40px 40px;
    min-height:100vh;
    transition:.3s;
}
body.collapsed .page-wrapper{
    margin-left:85px;
}

/* ===============================
   CARD
================================ */
.card{
    background:var(--card-bg);
    padding:30px 35px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,.15);
    max-width:900px;
    animation:fadeUp .4s ease;
}

@keyframes fadeUp{
    from{opacity:0;transform:translateY(20px)}
    to{opacity:1;transform:translateY(0)}
}

/* ===============================
   PROFILE BOX
================================ */
.profile-box{
    display:flex;
    gap:20px;
    align-items:center;
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
    margin-top:6px;
    font-size:15px;
    color:#64748b;
}

/* DARK MODE */
body.dark .profile-info p{
    color:#cbd5f5;
}

/* ===============================
   CONTENT TEXT
================================ */
.card p{
    font-size:15px;
    color:#475569;
}
body.dark .card p{
    color:#e5e7eb;
}

/* ===============================
   RESPONSIVE
================================ */
@media(max-width:768px){
    .page-wrapper{
        margin-left:0;
        padding:90px 20px;
    }
}
</style>
</head>

<body>

<?php include "../admin/partials/header.php"; ?>
<?php include "../admin/partials/sidebar.php"; ?>

<div class="page-wrapper">

    <div class="card">

        <div class="profile-box">
            <img src="<?= $fotoURL ?>" alt="Foto Profil">
            <div class="profile-info">
                <h2>Selamat Datang, <?= htmlspecialchars($nama) ?> 👋</h2>
                <p>Karyawan Divisi <b><?= htmlspecialchars($divisi) ?></b></p>
            </div>
        </div>

        <hr style="margin:20px 0;opacity:.35">

        <p>
            Gunakan menu di samping untuk melakukan absen,
            melihat jadwal kerja, dan informasi lainnya.
        </p>

    </div>

</div>

<?php include "../admin/partials/footer.php"; ?>
</body>
</html>

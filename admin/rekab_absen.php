<?php
include "../koneksi.php";
$dateToday = date('Y-m-d');

/* ===============================
   STATISTIK
================================ */
$hadir = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM absensi 
     WHERE DATE(waktu_masuk)='$dateToday'
     AND status_kehadiran='HADIR'"
))[0];

$izin = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM absensi 
     WHERE DATE(waktu_masuk)='$dateToday'
     AND status_kehadiran='IZIN'"
))[0];

$total = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(DISTINCT nomor_karyawan) FROM absensi"
))[0];

$belum = max($total - ($hadir + $izin), 0);

/* ===============================
   DATA HARI INI
================================ */
$data = mysqli_query($conn,
    "SELECT * FROM absensi
     WHERE DATE(waktu_masuk)='$dateToday'
     ORDER BY waktu_masuk DESC"
);
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
.dashboard{margin-left:290px;padding:30px}
body.collapsed .dashboard{margin-left:110px}

/* GLASS CARD */
.glass-card{
    background:var(--card-bg);
    border-radius:30px;
    padding:36px;
    box-shadow:
        0 25px 55px rgba(0,0,0,.14),
        inset 0 0 0 1px rgba(255,255,255,.4);
}

/* HEADER */
.page-title{
    font-size:30px;
    font-weight:900;
    background:linear-gradient(90deg,#2563eb,#0ea5e9);
    -webkit-background-clip:text;
    color:transparent;
}

/* STAT */
.stat-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:26px;
    margin:40px 0;
}
.stat-item{
    padding:34px;
    border-radius:26px;
    background:var(--card-bg);
    border-left:6px solid var(--accent);
    box-shadow:0 12px 30px rgba(0,0,0,.10);
}
.stat-item span{
    font-size:40px;
    font-weight:900;
}

/* SEARCH */
.search-box input{
    border-radius:16px;
    padding:12px 16px;
}

/* =============================
   TABLE FULL GLASS & BESAR
============================= */
.table{
    width:100%;
    border-collapse:separate;
    border-spacing:0 22px;
    font-size:15px;
}

.table thead{
    background:linear-gradient(90deg,#0ea5e9,#2563eb);
    color:#fff;
}
.table thead th{
    padding:22px 18px;
    border:none;
    text-transform:uppercase;
    letter-spacing:1px;
    font-size:13px;
}

.table tbody tr{
    background:var(--card-bg);
    height:120px;
    border-radius:26px;
    box-shadow:0 18px 40px rgba(0,0,0,.12);
    transition:.35s ease;
}
.table tbody tr:hover{
    transform:translateY(-6px);
    box-shadow:0 30px 60px rgba(37,99,235,.30);
}

.table tbody td{
    border:none;
    padding:26px 20px;
    vertical-align:middle;
}

/* rounded row */
.table tbody tr td:first-child{
    border-radius:26px 0 0 26px;
    font-weight:900;
    font-size:16px;
}
.table tbody tr td:last-child{
    border-radius:0 26px 26px 0;
}

/* STATUS */
.badge-status{
    padding:10px 22px;
    font-size:13px;
    font-weight:900;
    border-radius:999px;
}
.badge-hadir{background:#22c55e}
.badge-izin{background:#facc15;color:#000}
.badge-sakit{background:#ef4444}

/* =============================
   FOTO SELFIE BESAR & JELAS
============================= */
.img-selfie{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:20px;
    border:3px solid rgba(255,255,255,.7);
    box-shadow:0 12px 28px rgba(0,0,0,.35);
    transition:.3s;
}
.img-selfie:hover{
    transform:scale(1.2);
    z-index:10;
}

/* JAM */
.table td:nth-child(7),
.table td:nth-child(8){
    font-weight:900;
    font-size:15px;
    color:#2563eb;
}

/* =============================
   SEARCH & ACTION BAR MEWAH
============================= */
.action-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    background:var(--card-bg);
    padding:22px 26px;
    border-radius:26px;
    box-shadow:
        0 18px 40px rgba(0,0,0,.12),
        inset 0 0 0 1px rgba(255,255,255,.45);
}

/* SEARCH */
.search-box{
    position:relative;
    max-width:360px;
    width:100%;
}
.search-box input{
    width:100%;
    padding:14px 18px 14px 48px;
    border-radius:999px;
    border:none;
    background:rgba(255,255,255,.65);
    backdrop-filter:blur(10px);
    font-weight:600;
    box-shadow:0 10px 25px rgba(0,0,0,.12);
}
.search-box input:focus{
    outline:none;
    box-shadow:0 0 0 3px rgba(37,99,235,.35);
}
.search-box::before{
    content:"🔍";
    position:absolute;
    left:18px;
    top:50%;
    transform:translateY(-50%);
    font-size:18px;
    opacity:.6;
}

/* EXPORT BUTTON */
.btn-export{
    display:flex;
    align-items:center;
    gap:10px;
    padding:14px 26px;
    border:none;
    border-radius:999px;
    font-weight:900;
    letter-spacing:.6px;
    color:white;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    box-shadow:
        0 14px 35px rgba(34,197,94,.45),
        inset 0 0 0 1px rgba(255,255,255,.4);
    transition:.3s ease;
}
.btn-export:hover{
    transform:translateY(-3px) scale(1.04);
    box-shadow:0 22px 55px rgba(34,197,94,.6);
}
</style>

<div class="dashboard">
<div class="content-wrapper">

<!-- HEADER -->
<div class="glass-card text-center mb-4">
    <h2 class="page-title">📅 Rekap Absensi Hari Ini</h2>
    <p class="opacity-75"><?= date("d F Y") ?></p>
</div>

<!-- STAT CARD (TETAP ADA) -->
<div class="stat-grid">
    <div class="stat-item">
        <h4>✅ Hadir</h4>
        <span><?= $hadir ?></span>
    </div>
    <div class="stat-item">
        <h4>📝 Izin</h4>
        <span><?= $izin ?></span>
    </div>
    <div class="stat-item">
        <h4>❌ Belum Absen</h4>
        <span><?= $belum ?></span>
    </div>
</div>

<div class="action-bar mb-4">
    <div class="search-box">
        <input type="text" id="searchInput" class="form-control"
        placeholder="Cari nama, divisi, status...">
    </div>

   <a href="rekab_absen_export.php?tanggal=<?= $dateToday ?>" class="btn-export">
    📥 Export Excel
</a>
</div>

<!-- TABLE -->
<div class="glass-card">
<div class="table-responsive">
<table class="table text-center align-middle" id="absenTable">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Divisi</th>
    <th>Status</th>
    <th>Selfie Masuk</th>
    <th>Selfie Pulang</th>
    <th>Jam Masuk</th>
    <th>Jam Pulang</th>
</tr>
</thead>
<tbody>

<?php $no=1; while($r=mysqli_fetch_assoc($data)): ?>
<tr>
<td><?= $no++ ?></td>
<td class="fw-bold"><?= $r['nama_karyawan'] ?></td>
<td><?= $r['divisi'] ?></td>
<td>
<?php
$badge = $r['status_kehadiran']=="HADIR"?"badge-hadir":
        ($r['status_kehadiran']=="IZIN"?"badge-izin":"badge-sakit");
?>
<span class="badge badge-status <?= $badge ?>">
<?= $r['status_kehadiran'] ?>
</span>
</td>
<td>
<?php if(!empty($r['selfie_masuk'])): ?>
<img src="../uploads/selfie/<?= basename($r['selfie_masuk']) ?>" class="img-selfie">
<?php else: ?>-<?php endif; ?>
</td>
<td>
<?php if(!empty($r['selfie_pulang'])): ?>
<img src="../uploads/selfie/<?= basename($r['selfie_pulang']) ?>" class="img-selfie">
<?php else: ?>-<?php endif; ?>
</td>
<td><?= $r['waktu_masuk']?date("H:i",strtotime($r['waktu_masuk'])):"-" ?></td>
<td><?= $r['waktu_pulang']?date("H:i",strtotime($r['waktu_pulang'])):"-" ?></td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>
</div>

</div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function(){
let v=this.value.toLowerCase();
document.querySelectorAll("#absenTable tbody tr").forEach(tr=>{
tr.style.display = tr.innerText.toLowerCase().includes(v) ? "" : "none";
});
});
</script>

<?php include "partials/footer.php"; ?>

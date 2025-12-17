<?php
session_start();
include "../koneksi.php";
$pageTitle = "Dashboard Kapten";

/* ======================
   DATA PRODUKSI (AMAN)
====================== */
$q = mysqli_query($conn,"
    SELECT 
        COUNT(*) AS total_input,
        IFNULL(SUM(qty),0) AS total_qty,
        IFNULL(SUM(pack),0) AS total_pack
    FROM produksi_mesin
");
$prod = mysqli_fetch_assoc($q);

include "partials/header.php";
include "partials/navbar.php";
?>

<style>
html,body{
    height:100%;
}
.page-root{
    min-height:100vh;
    display:flex;
    flex-direction:column;
}
.page-content{
    flex:1;
}

/* ================= DASHBOARD ================= */
.dashboard-wrap{
    max-width:1100px;
    margin:auto;
    padding:160px 20px 40px;
}

.dashboard-card{
    background:linear-gradient(180deg,#fff,#f8f9fb);
    border-radius:22px;
    padding:40px;
    box-shadow:0 20px 45px rgba(0,0,0,.12);
}

/* ================= HEADER ================= */
.dashboard-title{
    font-size:2.2rem;
    font-weight:800;
}
.dashboard-title span{
    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}
.dashboard-sub{
    color:#555;
    margin-bottom:35px;
}

/* ================= INFO GRID ================= */
.info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:18px;
    margin-bottom:35px;
}
.info-box{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}
.info-box h4{
    font-weight:800;
    margin-bottom:6px;
}
.info-box span{
    font-size:1.8rem;
    font-weight:900;
    color:#2563eb;
}

/* ================= ACTION ================= */
.action-box{
    background:linear-gradient(90deg,#2563eb,#f59e0b);
    border-radius:18px;
    padding:28px;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}
.action-btn{
    background:white;
    color:#111;
    padding:14px 28px;
    border-radius:14px;
    font-weight:800;
    text-decoration:none;
}

/* ================= TASK ================= */
.task-box{
    margin-top:30px;
    background:white;
    padding:26px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}
.task-box ul{
    padding-left:20px;
}
.task-box li{
    margin-bottom:10px;
}

/* ================= MOTIVATION ================= */
.motivation{
    margin-top:25px;
    padding:22px;
    background:rgba(37,99,235,.08);
    border-left:6px solid #2563eb;
    border-radius:14px;
    font-weight:600;
}
</style>

<div class="page-root">
<div class="page-content">

<div class="dashboard-wrap">
<div class="dashboard-card">

<h1 class="dashboard-title">
    Selamat Datang, <span><?= htmlspecialchars($_SESSION['nama_admin']) ?></span>
</h1>

<p class="dashboard-sub">
    Panel Kapten untuk pengawasan dan pencatatan produksi mesin es kristal.
</p>

<!-- INFO PRODUKSI -->
<div class="info-grid">
    <div class="info-box">
        <h4>📄 Total Input</h4>
        <span><?= $prod['total_input'] ?></span>
    </div>
    <div class="info-box">
        <h4>📦 Total Pack</h4>
        <span><?= $prod['total_pack'] ?></span>
    </div>
    <div class="info-box">
        <h4>🧊 Total Qty</h4>
        <span><?= $prod['total_qty'] ?></span>
    </div>
</div>

<!-- ACTION -->
<div class="action-box">
    <div>
        <h4>➕ Input Produksi Mesin</h4>
        <p>Catat hasil produksi dengan cepat dan akurat</p>
    </div>
    <a href="produksi_mesin_input.php" class="action-btn">Mulai Input</a>
</div>

<!-- TUGAS KAPTEN -->
<div class="task-box">
    <h4>📌 Tugas Kapten Hari Ini</h4>
    <ul>
        <li>Memastikan produksi mesin tercatat dengan benar</li>
        <li>Mengontrol jumlah pack & qty produksi</li>
        <li>Memastikan tidak ada data produksi yang terlewat</li>
        <li>Melaporkan kendala produksi ke admin</li>
    </ul>
</div>

<!-- MOTIVASI -->
<div class="motivation">
    💪 “Disiplin dalam mencatat hari ini, menyelamatkan produksi esok hari.”
    <br>Terus semangat, Kapten!
</div>

</div>
</div>

</div>

<?php include "partials/footer.php"; ?>
</div>

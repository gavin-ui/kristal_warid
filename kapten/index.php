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
html, body {
    height: 100%;
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

/* ================= ROOT BACKGROUND ================= */
.page-root {
    min-height: 100vh;
    display: flex;
    flex-direction: column;

    background:
        linear-gradient(180deg, rgba(3,12,30,.88), rgba(0,8,20,.96)),
        url('../home/assets/ChatGPT Image 18 Des 2025, 09.34.26.png');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;

    position: relative;
    overflow: hidden;
}

/* Snow overlay */
.page-root::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url('../home/assets/ChatGPT Image 18 Des 2025, 09.00.23.png') repeat;
    opacity: .22;
    animation: snowDrift 30s linear infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes snowDrift {
    from { background-position: 0 0; }
    to { background-position: 0 1200px; }
}

.page-content {
    flex: 1;
    position: relative;
    z-index: 2;
}

/* ================= DASHBOARD ================= */
.dashboard-wrap {
    max-width: 1100px;
    margin: auto;
    padding: 150px 20px 40px;
}

/* GLASS CARD */
.dashboard-card {
    background: linear-gradient(
        180deg,
        rgba(20,40,80,.55),
        rgba(10,20,45,.75)
    );
    border-radius: 26px;
    padding: 42px;

    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    border: 1px solid rgba(120,180,255,.25);
    box-shadow:
        0 25px 60px rgba(0,0,0,.6),
        inset 0 0 35px rgba(120,180,255,.08);
}

/* ================= HEADER ================= */
.dashboard-title {
    font-size: 2.3rem;
    font-weight: 900;
    color: #eaf2ff;
}

.dashboard-title span {
    background: linear-gradient(90deg,#60a5fa,#38bdf8);
    -webkit-background-clip: text;
    color: transparent;
}

.dashboard-sub {
    color: #b9d6ff;
    margin-bottom: 36px;
}

/* ================= INFO GRID ================= */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 18px;
    margin-bottom: 34px;
}

.info-box {
    background: linear-gradient(
        180deg,
        rgba(30,60,120,.55),
        rgba(15,30,70,.75)
    );
    padding: 22px;
    border-radius: 18px;
    border: 1px solid rgba(120,180,255,.25);
    box-shadow: inset 0 0 25px rgba(120,180,255,.08);
}

.info-box h4 {
    font-weight: 700;
    color: #dbeafe;
    margin-bottom: 6px;
}

.info-box span {
    font-size: 1.9rem;
    font-weight: 900;
    color: #60a5fa;
}

/* ================= ACTION ================= */
.action-box {
    background: linear-gradient(
        90deg,
        rgba(37,99,235,.85),
        rgba(30,58,138,.95)
    );
    border-radius: 20px;
    padding: 30px;
    color: #eaf2ff;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;

    box-shadow: 0 20px 50px rgba(0,0,0,.5);
}

.action-box h4 {
    font-weight: 800;
}

.action-box p {
    opacity: .9;
}

.action-btn {
    background: linear-gradient(135deg,#60a5fa,#3b82f6);
    color: #021024;
    padding: 14px 32px;
    border-radius: 16px;
    font-weight: 900;
    text-decoration: none;
    box-shadow: 0 10px 30px rgba(96,165,250,.45);
    transition: .3s;
}

.action-btn:hover {
    transform: translateY(-3px);
}

/* ================= TASK ================= */
.task-box {
    margin-top: 32px;
    background: linear-gradient(
        180deg,
        rgba(25,50,100,.55),
        rgba(12,25,55,.75)
    );
    padding: 28px;
    border-radius: 20px;
    border: 1px solid rgba(120,180,255,.25);
}

.task-box h4 {
    color: #eaf2ff;
    margin-bottom: 14px;
}

.task-box li {
    color: #c7ddff;
    margin-bottom: 10px;
}

/* ================= MOTIVATION ================= */
.motivation {
    margin-top: 28px;
    padding: 22px;
    background: linear-gradient(
        90deg,
        rgba(37,99,235,.25),
        rgba(15,30,70,.35)
    );
    border-left: 6px solid #60a5fa;
    border-radius: 16px;
    font-weight: 600;
    color: #eaf2ff;
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
    <a href="produksi_mesin.php" class="action-btn">Mulai Input</a>
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

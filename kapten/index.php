<?php
session_start();
include "../koneksi.php";
$pageTitle = "Dashboard Kapten";
include "partials/header.php";
include "partials/navbar.php";
?>

<style>
.dashboard-wrap {
    max-width: 1000px;
    margin: auto;
    margin-top: 140px;
    padding: 0 20px;
}

.dashboard-card {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.95),
        rgba(255,255,255,0.85)
    );
    border-radius: 22px;
    padding: 45px 40px;
    box-shadow:
        0 20px 40px rgba(0,0,0,0.12),
        inset 0 0 0 1px rgba(255,255,255,0.6);
    position: relative;
    overflow: hidden;
}

/* Decorative circles */
.dashboard-card::before,
.dashboard-card::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    filter: blur(1px);
}

.dashboard-card::before {
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(13,110,253,0.25), transparent 60%);
    top: -90px;
    left: -90px;
}

.dashboard-card::after {
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,145,0,0.3), transparent 60%);
    bottom: -120px;
    right: -120px;
}

.dashboard-title {
    font-size: 2.1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 8px;
}

.dashboard-title span {
    background: linear-gradient(90deg, var(--blue), var(--orange));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.dashboard-sub {
    color: #555;
    font-size: 1rem;
    margin-bottom: 35px;
}

/* ACTION CARD */
.action-box {
    background: linear-gradient(
        90deg,
        rgba(13,110,253,0.95),
        rgba(255,145,0,0.9)
    );
    border-radius: 18px;
    padding: 30px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}

.action-text h4 {
    font-weight: 700;
    margin-bottom: 6px;
}

.action-text p {
    margin: 0;
    opacity: .9;
}

.action-btn {
    background: white;
    color: #222;
    padding: 14px 28px;
    border-radius: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: .3s ease;
    white-space: nowrap;
}

.action-btn:hover {
    background: #fff;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}

/* RESPONSIVE */
@media(max-width:768px) {
    .dashboard-title {
        font-size: 1.7rem;
    }
    .action-box {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<div class="dashboard-wrap">

    <div class="dashboard-card">

        <h1 class="dashboard-title">
            Selamat Datang, <span><?= htmlspecialchars($_SESSION['nama_admin']) ?></span>
        </h1>

        <p class="dashboard-sub">
            Panel Kapten untuk pencatatan dan monitoring produksi mesin es kristal.
        </p>

        <div class="action-box">
            <div class="action-text">
                <h4>📄 Input Produksi Mesin</h4>
                <p>Catat hasil produksi mesin dengan cepat dan akurat.</p>
            </div>

            <a href="produksi_mesin.php" class="action-btn">
                ➕ Mulai Input
            </a>
        </div>

    </div>

</div>

<?php include "partials/footer.php"; ?>

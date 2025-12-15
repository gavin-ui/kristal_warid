<?php
include "../koneksi.php";
?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>

/* THEME SUPPORT */
body { 
    background: var(--body-bg); 
    transition: .3s;
}

/* Layout wrapper */
.dashboard {
    margin-left: 290px;
    margin-right: 40px;
    padding: 30px;
    transition: .35s ease;
}

body.collapsed .dashboard {
    margin-left: 110px;
    margin-right: 40px;
}

/* wrapper agar rapi */
.content-wrapper {
    max-width: 1050px;
    margin: auto;
}

/* ====================================================
   WELCOME BANNER (Match Login Style)
   ==================================================== */
.welcome-banner {
    background: rgba(0, 174, 239, 0.85);
    backdrop-filter: blur(10px);
    border-radius: 22px;
    padding: 40px;
    color: white;
    box-shadow: 0 10px 25px rgba(0,0,0,.18);
    text-align: center;
    animation: fadeDown .7s ease;
}

.welcome-banner h1 {
    font-weight: 800;
    font-size: 30px;
    letter-spacing: .5px;
}

.welcome-banner p {
    font-size: 15px;
    opacity: .9;
}

/* ====================================================
   STATISTIC CARDS
   ==================================================== */

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-top: 35px;
}

.stat-card {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 28px;
    border-left: 6px solid #00AEEF;
    box-shadow: 0px 8px 22px rgba(0,0,0,.08);
    transition: .35s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

/* Hover Animation Glow */
.stat-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0px 14px 30px rgba(0,174,255,.32);
}

/* Soft highlight glow on hover */
.stat-card::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 120px;
    height: 120px;
    background: rgba(0,174,239,.25);
    border-radius: 50%;
    transition: .4s ease;
    opacity: 0;
}

.stat-card:hover::before {
    opacity: 1;
    transform: scale(1.4);
}

.stat-card h3 {
    font-size: 18px;
    font-weight: 700;
    color: #008FC7;
    margin-bottom: 8px;
}

/* Dark mode color sync */
body.dark .stat-card h3 {
    color: #4DAEFF;
}

.stat-value {
    font-size: 34px;
    font-weight: bold;
    color: #00AEEF;
    letter-spacing: 1px;
}

/* Dark correction */
body.dark .stat-value {
    color: #ffcc00;
}

/* Fade animation */
@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-25px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>


<div class="dashboard">
<div class="content-wrapper">

    <div class="welcome-banner">
        <h1>Selamat Datang Admin 👋</h1>
        <p>Kelola data pemesanan dan sistem operasional melalui dashboard ini.</p>
    </div>

    <div class="stats-container">

        <div class="stat-card">
            <h3>⭐ Feedback Masuk</h3>
            <div class="stat-value">
                <?php
                $feedback = mysqli_query($conn, "SELECT COUNT(*) FROM feedback");
                echo mysqli_fetch_array($feedback)[0] ?? 0;
                ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>📊 Rating Rata-rata</h3>
            <div class="stat-value">
                <?php
                $avg = mysqli_query($conn, "SELECT AVG(rating) FROM feedback");
                echo round(mysqli_fetch_array($avg)[0] ?? 0, 1) . " ⭐";
                ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>👤 Admin Aktif</h3>
            <div class="stat-value">1</div>
        </div>

    </div>

</div>
</div>

<?php include "partials/footer.php"; ?>

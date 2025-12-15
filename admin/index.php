<?php
include "../koneksi.php";
?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
/* ====================================================
   GLOBAL THEME SUPPORT
==================================================== */
body { 
    background: var(--body-bg); 
    transition: .3s ease;
}

/* ====================================================
   DASHBOARD LAYOUT
==================================================== */
.dashboard {
    margin-left: 290px;
    margin-right: 40px;
    padding: 30px;
    transition: .35s ease;
}

body.collapsed .dashboard {
    margin-left: 110px;
}

/* Wrapper biar rapi & fokus */
.content-wrapper {
    max-width: 1150px;
    margin: auto;
}

/* ====================================================
   WELCOME BANNER — PREMIUM GLASS
==================================================== */
.welcome-banner {
    background: linear-gradient(
        135deg,
        rgba(0,174,239,.9),
        rgba(0,110,200,.95)
    );
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);

    border-radius: 26px;
    padding: 42px 38px;
    color: #ffffff;

    box-shadow:
        0 18px 45px rgba(0,110,255,.35),
        inset 0 0 0 1px rgba(255,255,255,.25);

    text-align: center;
    animation: fadeDown .9s ease;
    position: relative;
    overflow: hidden;
}

/* Decorative glow */
.welcome-banner::after {
    content: "";
    position: absolute;
    top: -80px;
    right: -80px;
    width: 180px;
    height: 180px;
    background: rgba(255,255,255,.25);
    border-radius: 50%;
    opacity: .35;
}

.welcome-banner h1 {
    font-weight: 900;
    font-size: 32px;
    letter-spacing: .6px;
}

.welcome-banner p {
    font-size: 15.5px;
    opacity: .92;
}

/* ====================================================
   STATISTIC CARDS
==================================================== */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-top: 45px;
}

.stat-card {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 30px;

    border-left: 6px solid var(--accent);
    box-shadow:
        0 10px 26px rgba(0,0,0,.08),
        inset 0 1px 0 rgba(255,255,255,.6);

    transition: .35s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

/* Hover luxury effect */
.stat-card:hover {
    transform: translateY(-7px) scale(1.025);
    box-shadow:
        0 18px 45px rgba(0,174,255,.32),
        inset 0 1px 0 rgba(255,255,255,.6);
}

/* Accent glow bubble */
.stat-card::before {
    content: "";
    position: absolute;
    top: -70px;
    right: -70px;
    width: 160px;
    height: 160px;
    background: rgba(0,174,239,.22);
    border-radius: 50%;
    opacity: 0;
    transition: .45s ease;
}

.stat-card:hover::before {
    opacity: 1;
    transform: scale(1.4);
}

/* Title */
.stat-card h3 {
    font-size: 17px;
    font-weight: 700;
    color: var(--title-color);
    margin-bottom: 10px;
}

/* Value */
.stat-value {
    font-size: 36px;
    font-weight: 900;
    letter-spacing: 1px;
    color: var(--accent);
}

/* Dark mode correction */
body.dark .stat-card {
    box-shadow:
        0 12px 30px rgba(0,0,0,.45),
        inset 0 1px 0 rgba(255,255,255,.08);
}

body.dark .stat-value {
    color: #ffd54f;
}

/* ====================================================
   ANIMATION
==================================================== */
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

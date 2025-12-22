<?php
$role = $_SESSION['role'] ?? '';
?>

<div id="sidebar" class="sidebar">

<style>
html, body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}

/* =============================
   COLOR VARIABLES (LIGHT)
============================= */
:root {
    --sidebar-bg: linear-gradient(180deg, #0a5edc, #0b4fb3);
    --text-color: #ffffff;
    --hover-bg: linear-gradient(90deg, #ffcc00, #ffb300);
    --body-bg: #eef6ff;
    --card-bg: #ffffff;
    --title-color: #0b62d6;
    --accent: #ffb300;
    --glass: rgba(255,255,255,0.15);
}

/* =============================
   DARK MODE VARIABLES
============================= */
body.dark {
    --sidebar-bg: linear-gradient(180deg, #0a1224, #0f1b36);
    --text-color: #eaf2ff;
    --hover-bg: linear-gradient(90deg, #ffb300, #ff9900);
    --body-bg: #0a1224;
    --card-bg: #162447;
    --title-color: #5aa9ff;
    --accent: #ffb300;
    --glass: rgba(255,255,255,0.08);
}

/* =============================
   SIDEBAR CONTAINER
============================= */
.sidebar {
    width: 260px;
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    padding: 22px 18px;
    color: var(--text-color);
    display: flex;
    flex-direction: column;
    z-index: 999;
    overflow-y: auto;
    transition: all .35s ease;
    box-shadow: 0 15px 45px rgba(0,0,0,.35);
}

/* Glass overlay */
.sidebar::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(255,255,255,.12), transparent);
    pointer-events: none;
}

.sidebar.collapsed {
    width: 85px;
    padding: 22px 10px;
}

/* Scrollbar */
.sidebar::-webkit-scrollbar { width: 6px; }
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.35);
    border-radius: 10px;
}

/* =============================
   TOGGLE BUTTON
============================= */
.toggle-btn {
    background: var(--glass);
    border: 2px solid rgba(255,255,255,0.5);
    color: white;
    font-size: 18px;
    font-weight: 700;
    padding: 10px 0;
    border-radius: 14px;
    text-align: center;
    cursor: pointer;
    margin-bottom: 22px;
    backdrop-filter: blur(8px);
    transition: .35s ease;
}

.toggle-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 0 18px rgba(255,255,255,.35);
}

/* =============================
   DARK MODE SWITCH
============================= */
.dark-mode-box {
    display: flex;
    justify-content: center;
    margin-bottom: 22px;
}

.dark-toggle {
    width: 72px;
    height: 34px;
    background: linear-gradient(180deg,#111,#222);
    border-radius: 50px;
    position: relative;
    cursor: pointer;
    transition: .4s;
    box-shadow: inset 0 0 10px rgba(0,0,0,.6);
}

.switch-ball {
    width: 30px;
    height: 30px;
    background: radial-gradient(circle,#fff,#ddd);
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: .4s ease;
    box-shadow: 0 6px 14px rgba(0,0,0,.6);
}

.dark-toggle.active .switch-ball {
    transform: translateX(38px);
    background: radial-gradient(circle,#ffd966,#ffb300);
}

/* =============================
   LOGO
============================= */
.logo {
    margin-bottom: 28px;
}

.logo img {
    width: 72px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,.8);
    box-shadow: 0 0 18px rgba(255,255,255,.45);
    transition: .3s;
}

.logo h4 {
    margin-top: 10px;
    font-weight: 700;
    letter-spacing: .5px;
}

.sidebar.collapsed .logo img { width: 46px; }
.sidebar.collapsed .logo h4 { display: none; }

/* =============================
   MENU ITEM
============================= */
.menu-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 13px 16px;
    margin-top: 10px;
    border-radius: 14px;
    font-size: 15.5px;
    cursor: pointer;
    transition: .3s ease;
    background: transparent;
    position: relative;
}

.menu-item i {
    font-size: 18px;
}

/* Hover luxury effect */
.menu-item:hover {
    background: var(--hover-bg);
    color: #002244;
    transform: translateX(6px);
    box-shadow: 0 10px 25px rgba(255,179,0,.45);
}

/* Text hide on collapse */
.sidebar.collapsed .menu-text { display: none; }

/* =============================
   SUBMENU
============================= */
.submenu {
    margin-left: 20px;
    margin-top: 6px;
    display: none;
    border-left: 2px dashed rgba(255,255,255,.4);
    padding-left: 12px;
}

.submenu.show {
    display: block;
}

.sidebar.collapsed .submenu {
    display: none !important;
}

</style>


<!-- =============================
     SIDEBAR CONTENT
============================= -->

<div class="toggle-btn" onclick="toggleSidebar()">☰</div>

<div class="dark-mode-box">
    <div id="darkSwitch" class="dark-toggle">
        <div class="switch-ball"></div>
    </div>
</div>

<div class="logo text-center">
    <img src="../home/assets/Screenshot_2025-12-02_102409-removebg-preview (1).png">
    <h4>Dashboard</h4>
</div>


<!-- ===============================================================
     MENU ADMIN (FULL ACCESS)
=============================================================== -->
<?php if ($role === "admin"): ?>

<div class="menu-item" onclick="location.href='../admin/index.php'">
    <i>🏠</i> <span class="menu-text">Dashboard</span>
</div>

<div class="menu-item" onclick="location.href='../admin/register.php'">
    <i>👤</i> <span class="menu-text">Register</span>
</div>

<div class="menu-item" onclick="location.href='../admin/tambah_karyawan.php'">
    <i>👤</i> <span class="menu-text">Tambah Karyawan</span>
</div>

<!-- ===== MENU BARU: REGISTER KARYAWAN ===== -->
<div class="menu-item" onclick="location.href='../admin/register_karyawan.php'">
    <i>📝</i> <span class="menu-text">Register Karyawan</span>
</div>
<!-- ======================================== -->

<div class="menu-item" onclick="location.href='../admin/data_karyawan.php'">
    <i>📋</i> <span class="menu-text">Data Karyawan</span>
</div>

<div class="menu-item" onclick="location.href='../admin/absen.php'">
    <i>📡</i> <span class="menu-text">Scan Absen</span>
</div>

<div class="menu-item" onclick="location.href='../admin/rekab_absen.php'">
    <i>📝</i> <span class="menu-text">Rekab Absen</span>
</div>

<div class="menu-item" onclick="location.href='../admin/produksi_mesin_input.php'">
    <i>⭐</i> <span class="menu-text">Produksi Mesin A & B</span>
</div>

<div class="menu-item" onclick="location.href='../admin/crm.php'">
    <i>🚀</i> <span class="menu-text">CRM</span>
</div>

<div class="menu-item" onclick="togglePlastikMenu()">
    <i>🧊</i> <span class="menu-text">Penggunaan Plastik</span>
</div>

<div id="plastikSubMenu" class="submenu">
    <div class="menu-item" onclick="location.href='../admin/penggunaan_plastik_data_awal.php'"><i>📥</i> <span class="menu-text">Data Awal</span></div>
    <div class="menu-item" onclick="location.href='../admin/penggunaan_plastik_produksi.php'"><i>🏭</i> <span class="menu-text">Produksi</span></div>
    <div class="menu-item" onclick="location.href='../admin/penggunaan_plastik_retur.php'"><i>↩️</i> <span class="menu-text">Retur Armada</span></div>
    <div class="menu-item" onclick="location.href='../admin/penggunaan_plastik_distribusi.php'"><i>🚚</i> <span class="menu-text">Distribusi Barkel</span></div>
    <div class="menu-item" onclick="location.href='../admin/penggunaan_plastik_stok.php'"><i>📦</i> <span class="menu-text">Stok (Export)</span></div>
    <div class="menu-item" onclick="location.href='../admin/penggunaan_plastik.php'"><i>👌</i> <span class="menu-text">Edit</span></div>
</div>

<?php endif; ?>



<!-- ===============================================================
     MENU KARYAWAN
=============================================================== -->
<?php if ($role === "karyawan"): ?>

<div class="menu-item" onclick="location.href='../karyawan/index.php'">
    <i>🏠</i> <span class="menu-text">Home</span>
</div>

<div class="menu-item" onclick="location.href='../admin/absen.php'">
    <i>📡</i> <span class="menu-text">Absen</span>
</div>

<?php endif; ?>



<!-- ===============================================================
     MENU KAPTEN
=============================================================== -->
<?php if ($role === "kapten"): ?>

<div class="menu-item" onclick="location.href='../kapten/index.php'">
    <i>🏠</i> <span class="menu-text">Dashboard Kapten</span>
</div>

<div class="menu-item" onclick="location.href='../admin/absen.php'">
    <i>📡</i> <span class="menu-text">Absen</span>
</div>

<?php endif; ?>



<!-- ===============================================================
     LOGOUT
=============================================================== -->
<div class="menu-item" onclick="location.href='../admin/logout.php'">
    <i>🚪</i> <span class="menu-text">Logout</span>
</div>

</div>


<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.body.classList.toggle("collapsed");
}

const darkSwitch = document.getElementById("darkSwitch");
if(localStorage.getItem("theme") === "dark"){
    document.body.classList.add("dark");
    darkSwitch.classList.add("active");
}

darkSwitch.addEventListener("click", () => {
    darkSwitch.classList.toggle("active");
    document.body.classList.toggle("dark");

    localStorage.setItem("theme",
        document.body.classList.contains("dark") ? "dark" : "light"
    );
});

function togglePlastikMenu() {
    const sub = document.getElementById("plastikSubMenu");
    sub.classList.toggle("show");
}
</script>
    
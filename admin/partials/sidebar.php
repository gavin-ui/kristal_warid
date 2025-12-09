<div id="sidebar" class="sidebar">

<style>
html, body {
    margin: 0;
    padding: 0;
}

/* =============================
   COLOR VARIABLES
============================= */
:root {
    --sidebar-bg: linear-gradient(180deg, #0A57C9, #0B62D6);
    --text-color: white;
    --hover-bg: #ffb300;
    --body-bg: #eef6ff;
    --card-bg: white;
    --title-color: #0b62d6;
    --accent: #ffb300;
}

/* =============================
   DARK MODE VARIABLES
============================= */
body.dark {
    --sidebar-bg: #0f1729;
    --text-color: #ffffff;
    --hover-bg: #ff9900;
    --body-bg: #0a1224;
    --card-bg: #162447;
    --title-color: #4da3ff;
    --accent: #ff9900;
}

/* =============================
   SIDEBAR STYLE
============================= */
.sidebar {
    width: 260px;
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    transition: .3s ease;
    overflow: hidden;
    color: var(--text-color);
    box-shadow: 0 0 18px rgba(0,0,0,0.25);
    display: flex;
    flex-direction: column;
    z-index: 999;
}

.sidebar.collapsed {
    width: 85px;
    padding: 20px 10px;
}

/* =============================
   TOGGLE BUTTON
============================= */
.toggle-btn {
    background: var(--accent);
    padding: 10px 0;
    cursor: pointer;
    border-radius: 12px;
    font-weight: bold;
    text-align: center;
    border: 2px solid white;
    margin-bottom: 22px;
    transition: .3s;
}
.toggle-btn:hover { transform: scale(1.05); }

/* =============================
   DARK MODE SWITCH — FIXED
============================= */
.dark-mode-box {
    margin-bottom: 25px;
    display: flex;
    justify-content: center;
}

.dark-toggle {
    width: 70px;
    height: 32px;
    background: #1e1e1e;
    border-radius: 50px;
    position: relative;
    cursor: pointer;
    transition: .35s ease;
    overflow: hidden;
}

/* BALL */
.switch-ball {
    width: 28px;
    height: 28px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .35s ease;
    z-index: 5;
    font-size: 18px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.45);
}

/* ICON DI TENGAH BALL */
.switch-ball .sun,
.switch-ball .moon {
    position: absolute;
    font-size: 16px;
    transition: .25s ease;
}

.switch-ball .sun { opacity: 1; }
.switch-ball .moon { opacity: 0; }

.dark-toggle.active .switch-ball .sun { opacity: 0; }
.dark-toggle.active .switch-ball .moon { opacity: 1; }

/* BALL bergerak */
.dark-toggle.active .switch-ball {
    transform: translateX(38px);
}

/* =============================
   LOGO
============================= */
.logo img {
    width: 70px;
    border-radius: 50%;
    border: 3px solid white;
    margin-bottom: 10px;
    transition: .25s;
}

.sidebar.collapsed .logo img { width: 50px; }
.sidebar.collapsed .logo h4 { display: none; }

/* =============================
   MENU ITEMS
============================= */
.menu-item {
    display: flex;
    align-items: center;
    padding: 13px;
    gap: 15px;
    cursor: pointer;
    border-radius: 12px;
    transition: .25s;
    font-size: 16px;
    margin-top: 8px;
}

.menu-item:hover {
    background: var(--hover-bg);
    transform: translateX(5px);
}

.sidebar.collapsed .menu-text { display: none; }

</style>


<!-- =============================
     SIDEBAR CONTENT
============================= -->

<div class="toggle-btn" onclick="toggleSidebar()">☰</div>

<div class="dark-mode-box">
    <div id="darkSwitch" class="dark-toggle">
        <div class="switch-ball">
            <span class="sun">☀️</span>
            <span class="moon">🌙</span>
        </div>
    </div>
</div>

<div class="logo text-center">
    <img src="../home/assets/logo.png">
    <h4>Admin</h4>
</div>

<div class="menu-item" onclick="location.href='../admin/index.php'">
    <i>🏠</i> <span class="menu-text">Dashboard</span>
</div>

<div class="menu-item" onclick="location.href='../admin/tambah_karyawan.php'">
    <i>👤</i> <span class="menu-text">Tambah Karyawan</span>
</div>

<div class="menu-item" onclick="location.href='../admin/produksi_mesin_input.php'">
    <i>⭐</i> <span class="menu-text">Produksi Mesin A & B</span>
</div>

<div class="menu-item" onclick="location.href='../admin/logout.php'">
    <i>🚪</i> <span class="menu-text">Logout</span>
</div>

</div>


<script>
// =============================
// SIDEBAR COLLAPSE
// =============================
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.body.classList.toggle("collapsed");
}

// =============================
// DARK MODE SYSTEM
// =============================
const darkSwitch = document.getElementById("darkSwitch");

if(localStorage.getItem("theme") === "dark"){
    document.body.classList.add("dark");
    darkSwitch.classList.add("active");
}

darkSwitch.addEventListener("click", () => {
    darkSwitch.classList.toggle("active");
    document.body.classList.toggle("dark");

    if(document.body.classList.contains("dark")){
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
});
</script>

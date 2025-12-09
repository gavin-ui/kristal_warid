<div id="sidebar" class="sidebar">

<style>

/* ===== COLOR VARIABLES ===== */
:root {
    --sidebar-bg: linear-gradient(180deg, #0A57C9, #0b62d6);
    --text-color: white;
    --hover-bg: #ffb300;
    --body-bg: #eef6ff;
    --card-bg: white;
    --title-color: #0b62d6;
    --shadow: rgba(0,89,255,0.35);
    --accent: #ffb300;
}

/* ===== DARK MODE ===== */
body.dark {
    --sidebar-bg: #0f1729;
    --text-color: #ffffff;
    --hover-bg: #ff9900;
    --body-bg: #0a1224;
    --card-bg: #162447;
    --title-color: #4da3ff;
    --shadow: rgba(255,166,0,0.35);
    --accent: #ff9900;
}

/* SIDEBAR STYLE */
.sidebar {
    width: 260px;
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    padding: 22px;
    transition: .35s ease;
    overflow: hidden;
    color: var(--text-color);
    box-shadow: 0 0 18px var(--shadow);
    display: flex;
    flex-direction: column;
}

/* COLLAPSE SIDEBAR */
.sidebar.collapsed { width: 85px; }

/* ===== TOGGLE BUTTON ===== */
.toggle-btn {
    background: var(--accent);
    padding: 10px;
    cursor: pointer;
    border-radius: 12px;
    font-weight: bold;
    text-align: center;
    transition: .3s;
    border: 2px solid white;
    margin-bottom: 15px;
}
.toggle-btn:hover { transform: scale(1.05); }

/* DARK MODE SWITCH (NOW AT TOP) */
.dark-mode-box {
    margin-bottom: 25px;
    padding: 10px;
    border-radius: 14px;
    background: rgba(255,255,255,0.15);
    text-align: center;
}

.dark-toggle {
    width: 70px;
    height: 32px;
    background: #1e1e1e;
    border-radius: 50px;
    position: relative;
    cursor: pointer;
    transition: .35s;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 10px;
}

.dark-toggle .icon {
    color: white;
    font-size: 15px;
    font-weight: bold;
}

.switch-ball {
    width: 24px;
    height: 24px;
    background: white;
    border-radius: 50%;
    position: absolute;
    left: 4px;
    top: 4px;
    transition: .35s;
    box-shadow: 0 3px 8px rgba(0,0,0,0.3);
}

/* ACTIVE STATE */
.dark-toggle.active {
    background: var(--accent);
}
.dark-toggle.active .switch-ball {
    transform: translateX(32px);
}
.dark-toggle.active .icon {
    transform: scale(1.15);
}

/* ===== LOGO ===== */
.logo img {
    width: 70px;
    border-radius: 50%;
    border: 3px solid white;
    margin-bottom: 8px;
    transition: .3s;
}
.sidebar.collapsed .logo img { width: 50px; }

/* ===== MENU ITEMS ===== */
.menu-item {
    display: flex;
    align-items: center;
    padding: 14px;
    gap: 15px;
    cursor: pointer;
    border-radius: 12px;
    transition: .25s;
    margin-top: 10px;
    font-size: 16px;
}
.menu-item:hover {
    background: var(--hover-bg);
    transform: translateX(5px);
}

/* HIDE TEXT WHEN COLLAPSED */
.sidebar.collapsed .menu-text,
.sidebar.collapsed h4 { display: none; }

</style>

<!-- ===== SIDEBAR CONTENT ===== -->

<div class="toggle-btn" onclick="toggleSidebar()">☰</div>

<!-- DARK MODE SWITCH NOW MOVED ABOVE -->
<div class="dark-mode-box">
    <div id="darkSwitch" class="dark-toggle">
        <span class="icon">🌙</span>
        <div class="switch-ball"></div>
    </div>
</div>

<div class="logo text-center">
    <img src="../home/assets/logo.png">
    <h4>Admin</h4>
</div>

<div class="menu-item" onclick="location.href='../admin/index.php'">
    <i>🏠</i> <span class="menu-text">Dashboard</span>
</div>

<div class="menu-item" onclick="location.href='../admin/data_order.php'">
    <i>📦</i> <span class="menu-text">Data Order</span>
</div>

<div class="menu-item" onclick="location.href='../admin/feedback.php'">
    <i>⭐</i> <span class="menu-text">Feedback</span>
</div>

<div class="menu-item" onclick="location.href='../admin/logout.php'">
    <i>🚪</i> <span class="menu-text">Logout</span>
</div>

</div>


<script>
// SIDEBAR COLLAPSE SYSTEM
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.body.classList.toggle("collapsed");
}

/* ===== DARK MODE SYSTEM ===== */
const darkSwitch = document.getElementById("darkSwitch");

// Load theme
if(localStorage.getItem("theme") === "dark"){
    document.body.classList.add("dark");
    darkSwitch.classList.add("active");
    darkSwitch.querySelector(".icon").textContent = "☀️";
}

// Toggle theme
darkSwitch.addEventListener("click", () => {
    darkSwitch.classList.toggle("active");
    document.body.classList.toggle("dark");

    if(document.body.classList.contains("dark")){
        localStorage.setItem("theme", "dark");
        darkSwitch.querySelector(".icon").textContent = "☀️";
    } else {
        localStorage.setItem("theme", "light");
        darkSwitch.querySelector(".icon").textContent = "🌙";
    }
});
</script>

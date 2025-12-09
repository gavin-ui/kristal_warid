<nav class="navbar navbar-expand-lg sticky-top frost-nav">
<style>
/* ===============================
   🔷 PREMIUM ICE NAVBAR DESIGN
   =============================== */

.frost-nav {
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 2px solid rgba(0, 132, 255, 0.20);
    transition: all .35s ease-in-out;
    box-shadow: 0 5px 18px rgba(0,0,0,0.05);
}

/* Navbar saat scroll */
.frost-nav.scrolled {
    background: rgba(255,255,255,0.95);
    border-bottom: 3px solid #007bff;
    box-shadow: 0 6px 22px rgba(0,140,255,0.18);
}

/* Brand Title */
.nav-title {
    font-size: 22px;
    font-weight: 800;
    color: #0079ff !important;
    letter-spacing: .6px;
}

/* Logo */
.navbar-brand img {
    background: white;
    padding: 6px;
    border-radius: 50%;
    transition: .4s;
    border: 2px solid #007bff;
}

.navbar-brand img:hover {
    transform: scale(1.12) rotate(-4deg);
    box-shadow: 0 0 18px rgba(0,162,255,.45);
}

/* Nav Links */
.nav-link {
    font-weight: 600;
    color: #0066d9 !important;
    padding: 10px 14px;
    transition: .3s ease;
    position: relative;
}

/* Hover underline effect (ICE GLOW MOVING) */
.nav-link.cool-link::after {
    content: "";
    position: absolute;
    bottom: 3px;
    left: 50%;
    width: 0;
    height: 3px;
    transform: translateX(-50%);
    background: linear-gradient(90deg,#00d0ff,#0062ff,#00c8ff);
    border-radius: 10px;
    transition: .35s;
    animation: iceFlow 2s linear infinite;
    opacity: .7;
}

.nav-link.cool-link:hover::after {
    width: 68%;
}

/* Link hover text glow */
.nav-link:hover {
    color: #004bcd !important;
    text-shadow: 0 0 10px rgba(0,140,255,.36);
    transform: translateY(-2px);
}

/* Keyframe glowing underline */
@keyframes iceFlow {
  0% { filter: hue-rotate(0deg); }
  100% { filter: hue-rotate(360deg); }
}

/* Login Button */
.btn-warning {
    background: linear-gradient(90deg,#ffcc00,#ffb300);
    border: none;
    color: #003366;
    font-weight: 700;
    transition: .35s;
}

.btn-warning:hover {
    background: linear-gradient(90deg,#ffae00,#ff9c00);
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0px 0px 20px rgba(255,174,0,.4);
}

/* Custom hamburger styling */
.navbar-toggler {
    border: none !important;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 100 80' fill='%23007bff' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='100' height='12'/%3E%3Crect y='30' width='100' height='12'/%3E%3Crect y='60' width='100' height='12'/%3E%3C/svg%3E");
}

/* Responsive */
@media(max-width: 768px) {
    .nav-link { text-align: center; }
}
</style>

<div class="container">

    <a class="navbar-brand d-flex align-items-center" href="../home/index.php">
        <img src="../home/assets/logo.png" width="44" class="me-2">
        <span class="nav-title">Es Kristal Warid</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item">
            <a class="nav-link cool-link" href="../home/index.php">Beranda</a>
        </li>

        <li class="nav-item">
            <a class="nav-link cool-link" href="tentang_order.php">Tentang & Order</a>
        </li>

        <li class="nav-item">
            <a class="nav-link cool-link" href="feedback.php">Kritik & Saran</a>
        </li>

        <li class="nav-item ms-lg-3">
            <a class="btn btn-warning px-3 rounded-pill shadow-sm" href="../login/login.php">Login</a>
        </li>

      </ul>
    </div>

</div>
</nav>

<script>
window.addEventListener("scroll", () => {
    const nav = document.querySelector(".frost-nav");
    window.scrollY > 20 ? nav.classList.add("scrolled") : nav.classList.remove("scrolled");
});
</script>
  
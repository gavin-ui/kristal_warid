<nav class="navbar navbar-expand-lg sticky-top frost-nav">
<style>
/* ===============================
   ❄️ PREMIUM ICE GRADIENT NAVBAR
   =============================== */

.frost-nav {
    background: linear-gradient(
        135deg,
        rgba(255,255,255,0.85) 0%,
        rgba(230,245,255,0.75) 35%,
        rgba(190,225,255,0.65) 100%
    );
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border-bottom: 2px solid rgba(0,132,255,0.25);
    transition: all .4s ease-in-out;
    box-shadow:
        0 6px 22px rgba(0,120,255,0.12),
        inset 0 1px 0 rgba(255,255,255,0.6);
}

/* Navbar saat scroll */
.frost-nav.scrolled {
    background: linear-gradient(
        135deg,
        rgba(240,250,255,0.96) 0%,
        rgba(190,225,255,0.92) 60%,
        rgba(150,205,255,0.88) 100%
    );
    border-bottom: 3px solid #007bff;
    box-shadow:
        0 10px 28px rgba(0,110,255,0.28),
        inset 0 1px 0 rgba(255,255,255,0.7);
}

/* Brand Title */
.nav-title {
    font-size: 22px;
    font-weight: 900;
    letter-spacing: 1px;
    background: linear-gradient(90deg,#007bff,#00bfff,#007bff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Logo */
.navbar-brand img {
    background: white;
    padding: 6px;
    border-radius: 50%;
    border: 2px solid rgba(0,123,255,0.5);
    transition: .45s;
    box-shadow: 0 0 0 rgba(0,0,0,0);
}

.navbar-brand img:hover {
    transform: scale(1.15) rotate(-6deg);
    box-shadow: 0 0 22px rgba(0,162,255,.55);
}

/* Nav Links */
.nav-link {
    font-weight: 700;
    color: #005ecb !important;
    padding: 10px 16px;
    transition: .35s ease;
    position: relative;
}

/* ICE GLOW UNDERLINE */
.nav-link.cool-link::after {
    content: "";
    position: absolute;
    bottom: 2px;
    left: 50%;
    width: 0;
    height: 3px;
    transform: translateX(-50%);
    background: linear-gradient(90deg,#00e0ff,#007bff,#00e0ff);
    border-radius: 12px;
    transition: .4s;
    opacity: .85;
    animation: iceFlow 3s linear infinite;
}

.nav-link.cool-link:hover::after {
    width: 72%;
}

/* Text hover glow */
.nav-link:hover {
    color: #003c9e !important;
    text-shadow: 0 0 12px rgba(0,140,255,.45);
    transform: translateY(-2px);
}

/* Login Button */
.btn-warning {
    background: linear-gradient(90deg,#ffd000,#ffae00);
    border: none;
    color: #003366;
    font-weight: 800;
    transition: .35s;
    box-shadow: 0 6px 18px rgba(255,174,0,.35);
}

.btn-warning:hover {
    background: linear-gradient(90deg,#ffb300,#ff9800);
    transform: scale(1.1) translateY(-2px);
    box-shadow: 0 0 25px rgba(255,174,0,.55);
}

/* Hamburger */
.navbar-toggler {
    border: none !important;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 100 80' fill='%23007bff' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='100' height='12'/%3E%3Crect y='30' width='100' height='12'/%3E%3Crect y='60' width='100' height='12'/%3E%3C/svg%3E");
}

/* Mobile */
@media(max-width: 768px) {
    .nav-link {
        text-align: center;
    }
}

/* Flow animation */
@keyframes iceFlow {
    0% { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(360deg); }
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
  
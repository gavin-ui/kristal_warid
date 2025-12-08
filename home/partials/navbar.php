<nav class="navbar navbar-expand-lg sticky-top frost-nav shadow-sm">
  <style>
    /* ============================
       NAVBAR PREMIUM ICE THEME ❄
       ============================ */

    .frost-nav {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 2px solid rgba(0, 136, 255, 0.18);
        transition: all .3s ease-in-out;
    }

    .frost-nav.scrolled {
        background: #ffffff;
        border-bottom: 3px solid #007bff;
    }

    .nav-title {
        font-size: 21px;
        font-weight: 700;
        letter-spacing: .6px;
        color: #007bff !important;
    }

    .nav-link {
        color: #007bff !important;
        font-weight: 600;
        padding: 10px 14px;
        transition: .25s;
        position: relative;
    }

    .nav-link:hover {
        color: #0056d6 !important;
        text-shadow: 0px 0px 10px rgba(0,123,255,0.4);
        transform: scale(1.05);
    }

    .nav-link.cool-link::after {
        content: "";
        position: absolute;
        bottom: 2px;
        left: 50%;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #00d4ff, #007bff);
        border-radius: 10px;
        transform: translateX(-50%);
        transition: .3s;
    }

    .nav-link.cool-link:hover::after {
        width: 65%;
    }

    .navbar-brand img {
        background: white;
        padding: 4px;
        border-radius: 50%;
        transition: .3s;
    }

    .navbar-brand img:hover {
        transform: scale(1.1) rotate(-3deg);
        box-shadow: 0 0 12px rgba(0,174,255,0.4);
    }

    .btn-warning {
        background: #ffb300 !important;
        border: none;
        transition: .3s;
    }

    .btn-warning:hover {
        background: #ff9900 !important;
        transform: scale(1.06);
        box-shadow: 0 0 12px rgba(255,166,0,0.5);
    }

    @media(max-width: 768px) {
        .nav-link {
            text-align: center;
            padding: 12px;
        }
    }
  </style>

  <div class="container">

    <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
        <img src="../home/assets/logo.png" width="42" class="me-2 rounded-circle border border-2 border-primary">
        <span class="nav-title">Es Kristal Warid</span>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end mt-2 mt-lg-0" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link cool-link" href="../home/index.php">Beranda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cool-link" href="#about">Tentang</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cool-link" href="#layanan">Layanan</a>
        </li>
        <li class="nav-item ms-lg-2">
            <a class="btn btn-warning fw-bold rounded-pill px-3 shadow-sm" href="../login/login.php">Login Admin</a>
        </li>
      </ul>
    </div>

  </div>
</nav>

<script>
window.addEventListener("scroll", () => {
    const nav = document.querySelector(".frost-nav");
    if (window.scrollY > 20) nav.classList.add("scrolled");
    else nav.classList.remove("scrolled");
});
</script>

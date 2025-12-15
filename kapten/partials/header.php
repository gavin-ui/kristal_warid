<?php
// header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Kapten Panel' ?></title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root {
    --blue: #0d6efd;
    --blue-soft: #4da3ff;
    --orange: #ff9100;
    --orange-soft: #ffb347;

    --bg: #f4f7ff;
    --card: #ffffff;
}

/* GLOBAL */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(180deg, #f5f8ff 0%, #ffffff 100%);
    min-height: 100vh;
}

/* ================= NAVBAR PREMIUM ================= */
.navbar-premium {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 999;
    background: linear-gradient(
        90deg,
        rgba(13,110,253,0.95),
        rgba(255,145,0,0.9)
    );
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    overflow: hidden;
}

/* Decorative gradient glow */
.navbar-premium::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at left, rgba(255,255,255,0.25), transparent 40%),
        radial-gradient(circle at right, rgba(255,255,255,0.2), transparent 45%);
    pointer-events: none;
}

.navbar-premium .navbar-brand {
    font-size: 1.3rem;
    font-weight: 700;
    color: white !important;
    letter-spacing: .5px;
    position: relative;
    z-index: 2;
}

/* NAV LINK */
.navbar-premium .nav-link {
    color: white !important;
    font-weight: 600;
    margin-left: 18px;
    padding: 6px 14px;
    border-radius: 10px;
    transition: all .25s ease;
    position: relative;
    z-index: 2;
}

.navbar-premium .nav-link:hover {
    background: rgba(255,255,255,0.85);
    color: #222 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* ACTIVE LINK */
.navbar-premium .nav-link.active {
    background: white;
    color: var(--blue) !important;
}

/* ================= PAGE DECOR ================= */
.page-decor {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: -1;
}

.page-decor::before {
    content: "";
    position: absolute;
    width: 480px;
    height: 480px;
    background: radial-gradient(circle, rgba(13,110,253,0.18), transparent 60%);
    top: -120px;
    left: -120px;
}

.page-decor::after {
    content: "";
    position: absolute;
    width: 520px;
    height: 520px;
    background: radial-gradient(circle, rgba(255,145,0,0.18), transparent 60%);
    bottom: -160px;
    right: -160px;
}

/* ================= FOOTER ================= */
.footer-premium {
    background: linear-gradient(
        90deg,
        rgba(0,123,255,0.95),
        rgba(255,145,0,0.9)
    );
    color: #fff;
    padding: 18px 0;
    box-shadow: 0 -6px 25px rgba(0,0,0,0.15);
    position: relative;
}

/* GARIS GLOW ATAS */
.footer-premium::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: linear-gradient(
        90deg,
        rgba(255,255,255,0.9),
        rgba(255,255,255,0)
    );
}

.footer-brand {
    font-weight: 700;
    letter-spacing: 1px;
    font-size: 0.95rem;
}

.footer-sub {
    font-weight: 500;
    opacity: 0.9;
    margin-left: 6px;
}

.footer-copy {
    font-size: 0.85rem;
    opacity: 0.95;
}

</style>
</head>
<body>

<div class="page-decor"></div>

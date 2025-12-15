<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Es Kristal Warid - PT Dongzan Jaya Utama</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ===============================
   ❄ LUXURY HERO HEADER
   =============================== */

.hero-section {
    height: 78vh;
    width: 100%;
    position: relative;
    background:
        linear-gradient(180deg, rgba(0,123,255,.45), rgba(0,45,110,.75)),
        url('../home/assets/ice-bg.jpg');
    background-size: cover;
    background-position: center;
    overflow: hidden;
}

/* Snow / ice particles */
.hero-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url('../home/assets/snow.png') repeat;
    opacity: .22;
    animation: snowDrift 22s linear infinite;
}

/* Ice light flare */
.hero-section::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at top left,
            rgba(255,255,255,0.35),
            transparent 45%);
    pointer-events: none;
}

/* Glass hero box */
.hero-box {
    position: absolute;
    bottom: 15%;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 980px;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.65),
        rgba(255,255,255,0.45)
    );

    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    padding: 48px;
    border-radius: 26px;

    text-align: center;

    border: 1.8px solid rgba(255,255,255,0.45);
    box-shadow:
        0 12px 38px rgba(0,85,200,0.30),
        inset 0 0 18px rgba(255,255,255,0.6);

    animation: riseUp 1.4s ease forwards;
}

/* Ice divider */
.hero-divider {
    width: 80px;
    height: 4px;
    margin: 18px auto;
    border-radius: 20px;
    background: linear-gradient(90deg,#00c8ff,#007bff,#00c8ff);
    box-shadow: 0 0 15px rgba(0,160,255,.6);
}

/* Title */
.hero-title {
    font-size: 46px;
    font-weight: 900;
    letter-spacing: 1px;
    background: linear-gradient(90deg,#007bff,#00bfff,#007bff);
    -webkit-background-clip: text;
    color: transparent;
}

/* Subtitle */
.hero-sub {
    font-size: 18px;
    color: #003f82;
    max-width: 720px;
    margin: auto;
    opacity: .9;
}

/* Button shimmer */
.hero-btn {
    margin-top: 24px;
    background: linear-gradient(90deg,#ffd000,#ffae00);
    color: #00244f;
    font-weight: 800;
    border-radius: 50px;
    padding: 14px 34px;
    text-decoration: none;
    display: inline-block;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 22px rgba(255,190,0,.45);
    transition: .35s;
}

.hero-btn::after {
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 120%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.6),
        transparent
    );
    transition: .6s;
}

.hero-btn:hover::after {
    left: 120%;
}

.hero-btn:hover {
    background: linear-gradient(90deg,#ffb300,#ff9800);
    transform: scale(1.1) translateY(-3px);
}

/* ===============================
   ❄ ANIMATIONS
   =============================== */

@keyframes riseUp {
    0% { transform: translate(-50%, 40px); opacity: 0; }
    100% { transform: translate(-50%, 0); opacity: 1; }
}

@keyframes snowDrift {
    0% { background-position: 0 0; }
    100% { background-position: 0 1200px; }
}

/* ===============================
   📱 RESPONSIVE
   =============================== */

@media(max-width: 768px) {
    .hero-title { font-size: 32px; }
    .hero-sub { font-size: 15px; }
    .hero-box { padding: 30px; }
}


</style>
</head>

<body>

<!-- HERO SECTION -->
<div class="hero-section">
    <div class="hero-box">
        <div class="hero-divider"></div>
        <h1 class="hero-title">Es Kristal Warid</h1>
        <p class="hero-sub">
            Produsen Es Kristal Higienis Berstandar Modern — Magelang, Jawa Tengah
        </p>
        <a href="#about" class="hero-btn">Pelajari Lebih Lanjut</a>
    </div>
</div>

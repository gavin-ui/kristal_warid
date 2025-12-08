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
   ❄ GLOBAL THEME
   =============================== */

body {
    margin: 0;
    padding: 0;
    background-color: #f4faff;
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
}

/* ===============================
   ❄ HERO SECTION
   =============================== */

.hero-section {
    height: 75vh;
    width: 100%;
    background: linear-gradient(180deg, rgba(0,123,255,.35), rgba(0,67,160,.5)), 
                url('../home/assets/ice-bg.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
    animation: fadeHero 1.2s ease-in-out forwards;
    overflow: hidden;
}

/* Frost floating particles (es kecil melayang) */
.hero-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url('../home/assets/snow.png') repeat;
    opacity: .25;
    animation: snowDrift 18s linear infinite;
}

/* Frost glass content box */
.hero-box {
    position: absolute;
    bottom: 16%;
    left: 50%;
    transform: translateX(-50%);
    width: 85%;
    max-width: 900px;
    background: rgba(255,255,255,0.50);
    backdrop-filter: blur(12px);
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    border: 2px solid rgba(255, 255, 255, 0.35);
    box-shadow: 0 8px 32px rgba(0,102,255,0.20);
    animation: riseUp 1.3s ease forwards;
}

/* Title */
.hero-title {
    font-size: 42px;
    font-weight: 800;
    letter-spacing: .5px;
    background: linear-gradient(90deg, #007bff, #003b9b);
    -webkit-background-clip: text;
    color: transparent;
}

/* Subtitle */
.hero-sub {
    font-size: 18px;
    color: #004f9c;
    margin-bottom: 18px;
    opacity: .85;
}

/* Button */
.hero-btn {
    background: #ffc400;
    color: #001d3d;
    font-weight: 700;
    border-radius: 50px;
    padding: 12px 30px;
    transition: .3s;
    text-decoration: none;
    display: inline-block;
    box-shadow: 0 5px 18px rgba(255,217,0,.28);
}

.hero-btn:hover {
    background: #ffa200;
    transform: scale(1.09) translateY(-3px);
    box-shadow: 0 0 18px rgba(255,165,0,.5);
}

/* ===============================
   ❄ ANIMATIONS
   =============================== */

@keyframes riseUp {
    0% { transform: translate(-50%, 30px); opacity: 0; }
    100% { transform: translate(-50%, 0); opacity: 1; }
}

@keyframes fadeHero {
    0% { filter: brightness(.8); }
    100% { filter: brightness(1); }
}

@keyframes snowDrift {
    0% { background-position: 0 0; }
    100% { background-position: 0 1000px; }
}

/* ===============================
   📱 RESPONSIVE
   =============================== */

@media(max-width: 768px) {
    .hero-title { font-size: 30px; }
    .hero-sub { font-size: 15px; }
    .hero-box { padding: 25px; }
}

</style>
</head>

<body>

<!-- HERO SECTION -->
<div class="hero-section">
    <div class="hero-box">
        <h1 class="hero-title">Es Kristal Warid</h1>
        <p class="hero-sub">
            Produsen Es Kristal Higienis Berstandar Modern — Magelang, Jawa Tengah
        </p>
        <a href="#about" class="hero-btn">Pelajari Lebih Lanjut</a>
    </div>
</div>

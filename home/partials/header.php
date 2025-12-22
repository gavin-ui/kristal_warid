<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Es Kristal Warid - PT Dongzan Jaya Utama</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* =====================================================
   ❄ LUXURY HERO HEADER — CLEAN & BALANCED
===================================================== */

.hero-section {
    height: 100vh;
    min-height: 640px;
    width: 100%;
    position: relative;

    background:
        linear-gradient(180deg, rgba(0,123,255,.45), rgba(0,45,110,.75)),
        url('../home/assets/ChatGPT Image 18 Des 2025, 08.55.14.png');
    background-size: cover;
    background-position: center;

    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 12vh;

    overflow: hidden;
}

/* Snow effect */
.hero-section::before{
    content:"";
    position:absolute;
    inset:0;
    background:url('../home/assets/ChatGPT Image 18 Des 2025, 09.00.23.png') repeat;
    opacity:.18;
    animation:snowDrift 26s linear infinite;
}

/* Light glow */
.hero-section::after{
    content:"";
    position:absolute;
    inset:0;
    background:radial-gradient(circle at top left,
        rgba(255,255,255,.35),
        transparent 48%);
    pointer-events:none;
}

/* =====================================================
   HERO GLASS BOX
===================================================== */
.hero-box {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

    width: 90%;
    max-width: 980px;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.65),
        rgba(255,255,255,0.45)
    );

    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    padding: 52px;

    border-radius: 28px;
    text-align: center;

    border: 1.8px solid rgba(255,255,255,0.45);
    box-shadow:
        0 12px 38px rgba(0,85,200,0.30),
        inset 0 0 18px rgba(255,255,255,0.6);

    animation: riseUp 1.2s ease forwards;
}

/* =====================================================
   DIVIDER
===================================================== */
.hero-divider{
    width:70px;
    height:4px;
    margin:0 auto 18px;
    border-radius:20px;
    background:linear-gradient(90deg,#00c8ff,#007bff,#00c8ff);
    box-shadow:0 0 12px rgba(0,160,255,.6);
}

/* =====================================================
   TEXT
===================================================== */
.hero-title{
    font-size:44px;
    font-weight:900;
    letter-spacing:.6px;
    margin-bottom:12px;

    background:linear-gradient(90deg,#007bff,#00bfff,#007bff);
    -webkit-background-clip:text;
    color:transparent;
}

.hero-sub{
    font-size:17px;
    color:#003f82;
    max-width:680px;
    margin:0 auto 26px;
    line-height:1.6;
    opacity:.92;
}

/* =====================================================
   BUTTON
===================================================== */
.hero-btn{
    background:linear-gradient(90deg,#ffd000,#ffae00);
    color:#00244f;
    font-weight:800;
    border-radius:50px;
    padding:14px 36px;
    text-decoration:none;
    display:inline-block;
    position:relative;
    overflow:hidden;

    box-shadow:0 8px 24px rgba(255,190,0,.45);
    transition:.35s ease;
}

.hero-btn::after{
    content:"";
    position:absolute;
    top:0;
    left:-120%;
    width:120%;
    height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,.6),
        transparent
    );
    transition:.6s;
}

.hero-btn:hover::after{
    left:120%;
}

.hero-btn:hover{
    background:linear-gradient(90deg,#ffb300,#ff9800);
    transform:translateY(-3px) scale(1.06);
}

/* =====================================================
   ANIMATION
===================================================== */
@keyframes riseUp{
    0%{ transform:translate(-50%,-42%); opacity:0 }
    100%{ transform:translate(-50%,-50%); opacity:1 }
}

@keyframes snowDrift{
    0%{ background-position:0 0 }
    100%{ background-position:0 1200px }
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media(max-width:768px){
    .hero-section{ height:70vh }
    .hero-title{ font-size:30px }
    .hero-sub{ font-size:14.5px }
    .hero-box{ padding:28px 24px }
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

        <a href="#about" class="hero-btn">
            Tentang Es Warid
        </a>
    </div>
</div>

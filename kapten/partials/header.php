<?php
// kapten/partials/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Kapten Panel' ?></title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --ice-blue:#1e40af;
    --ice-soft:#3b82f6;
    --ice-glow:#93c5fd;
    --snow:#e0f2fe;
}

/* ================= GLOBAL ================= */
body{
    font-family:'Poppins',sans-serif;
    margin:0;
    min-height:100vh;
    background:#0b1220;
    color:#111;
}

/* ================= POLAR BACKGROUND ================= */
.polar-bg{
    position:fixed;
    inset:0;
    z-index:-2;

    background:
        linear-gradient(180deg, rgba(2,20,50,.85), rgba(2,10,30,.95)),
        url('../home/assets/ChatGPT Image 18 Des 2025, 09.34.26.png');

    background-size:cover;
    background-position:center;
}

/* Snow overlay */
.polar-bg::after{
    content:"";
    position:absolute;
    inset:0;
    background:url('../home/assets/ChatGPT Image 18 Des 2025, 09.00.23.png') repeat;
    opacity:.18;
    animation:snowDrift 30s linear infinite;
}

/* ================= NAVBAR ================= */
.navbar-premium{
    position:fixed;
    top:0;
    width:100%;
    z-index:999;

    background:linear-gradient(
        90deg,
        rgba(30,64,175,.95),
        rgba(59,130,246,.9)
    );

    backdrop-filter:blur(12px);
    box-shadow:0 10px 30px rgba(0,0,0,.35);
}

.navbar-premium .navbar-brand{
    font-weight:800;
    color:#fff !important;
    letter-spacing:.5px;
}

.navbar-premium .nav-link{
    color:#fff !important;
    font-weight:600;
    padding:8px 16px;
    border-radius:12px;
    transition:.25s;
}

.navbar-premium .nav-link:hover{
    background:rgba(255,255,255,.9);
    color:#0b1220 !important;
    transform:translateY(-2px);
}

/* ================= PAGE CONTENT WRAP ================= */
.page-content{
    padding-top:110px;
    padding-bottom:60px;
}

/* ================= FOOTER ================= */
.footer-premium{
    background:linear-gradient(
        90deg,
        rgba(30,64,175,.95),
        rgba(59,130,246,.9)
    );
    color:#fff;
    padding:18px 0;
    box-shadow:0 -6px 20px rgba(0,0,0,.35);
}

/* ================= ANIMATION ================= */
@keyframes snowDrift{
    from{ background-position:0 0; }
    to{ background-position:0 1400px; }
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){
    .page-content{
        padding-top:90px;
    }
}
</style>
</head>

<body>

<!-- POLAR BACKGROUND -->
<div class="polar-bg"></div>

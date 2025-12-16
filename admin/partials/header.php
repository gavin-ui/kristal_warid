<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel — Es Kristal Warid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
    /* =============================
   ROOT VARIABLES (LIGHT MODE)
============================= */
:root {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 100px;

    --body-bg: #eef6ff;
    --card-bg: rgba(255,255,255,0.88);
    --text-color: #0b2545;
    --title-color: #0b62d6;
    --accent: #ffb300;
    --glass-border: rgba(255,255,255,0.5);
}

/* =============================
   DARK MODE (BLUE BLACK PREMIUM)
============================= */
body.dark {
    --body-bg: linear-gradient(180deg, #050b18, #0a1224);
    --card-bg: linear-gradient(
        180deg,
        rgba(18,30,60,0.92),
        rgba(10,18,36,0.92)
    );
    --text-color: #eaf2ff;
    --title-color: #5aa9ff;
    --accent: #ffb300;
    --glass-border: rgba(90,169,255,0.25);
}

/* =============================
   BODY GLOBAL
============================= */
body {
    margin: 0;
    padding: 0;
    background: var(--body-bg);
    color: var(--text-color);
    font-family: 'Poppins', Arial, sans-serif;
    transition: background .35s ease, color .35s ease;
}

/* =============================
   ADMIN HEADER (PREMIUM GLASS)
============================= */
header.admin-header {
    position: fixed;
    top: 0;
    left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
    height: 72px;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.85),
        rgba(255,255,255,0.65)
    );
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    border-bottom: 2px solid var(--glass-border);
    display: flex;
    align-items: center;
    padding: 0 32px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.12),
        inset 0 -1px 0 rgba(255,255,255,0.4);

    transition: .35s ease;
    z-index: 550;
}

/* Accent line bawah (luxury highlight) */
header.admin-header::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(
        90deg,
        var(--title-color),
        var(--accent)
    );
    opacity: .85;
}

/* =============================
   HEADER — SIDEBAR COLLAPSED
============================= */
body.collapsed header.admin-header {
    left: var(--sidebar-collapsed-width);
    width: calc(100% - var(--sidebar-collapsed-width));
}

/* =============================
   HEADER TITLE
============================= */
header.admin-header h1 {
    margin: 0;
    font-size: 20.5px;
    font-weight: 800;
    letter-spacing: .4px;

    background: linear-gradient(
        90deg,
        var(--title-color),
        var(--accent)
    );
    -webkit-background-clip: text;
    color: transparent;

    white-space: nowrap;
}

/* =============================
   DARK MODE HEADER FIX
============================= */
body.dark header.admin-header {
    background: linear-gradient(
        180deg,
        rgba(18,30,60,0.92),
        rgba(10,18,36,0.92)
    );

    box-shadow:
        0 10px 30px rgba(0,0,0,0.65),
        inset 0 -1px 0 rgba(90,169,255,0.15);
}

/* =============================
   MAIN CONTENT
============================= */
.main-content {
    margin-top: 95px;
    padding: 24px;
    transition: .3s ease;
}

    </style>
</head>

<body>

<header class="admin-header">
    <h1>Admin — Es Kristal Warid</h1>
</header>

<div class="main-content">
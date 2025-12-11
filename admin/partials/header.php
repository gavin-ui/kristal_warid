<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel — Es Kristal Warid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
    :root {
        --sidebar-width: 280px; /* mengikuti real width visual */
        --sidebar-collapsed-width: 100px;

        --sidebar-bg: linear-gradient(180deg, #0A57C9, #0B62D6);
        --text-color: #000;
        --body-bg: #eef6ff;
        --card-bg: #ffffff;
        --title-color: #0b62d6;
        --hover-bg: #ffb300;
        --accent: #ffb300;
    }

    /* DARK MODE */
    body.dark {
        --text-color: #ffffff;
        --body-bg: #0a1224;
        --card-bg: #162447;
        --title-color: #4da3ff;
        --hover-bg: #ff9900;
        --accent: #ff9900;
    }

    body {
        margin: 0;
        padding: 0;
        background: var(--body-bg);
        color: var(--text-color);
        font-family: Arial, sans-serif;
    }

    /* =============================
       FIXED HEADER — AUTO RESPONSIVE
    ============================== */
    header.admin-header {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
        height: 68px;

        background: var(--card-bg);
        border-bottom: 3px solid var(--title-color);
        display: flex;
        align-items: center;
        padding: 0 28px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: .28s ease;
        z-index: 550;
    }

    /* Saat sidebar collapse */
    body.collapsed header.admin-header {
        left: var(--sidebar-collapsed-width);
        width: calc(100% - var(--sidebar-collapsed-width));
    }

    header.admin-header h1 {
        margin: 0;
        font-size: 21px;
        font-weight: bold;
        color: var(--title-color);
        white-space: nowrap;
        letter-spacing: .3px;
    }

    /* AREA KONTEN START */
    .main-content {
        margin-top: 85px; 
        padding: 20px;
        transition: .3s ease;
    }
    </style>
</head>

<body>

<header class="admin-header">
    <h1>Admin — Es Kristal Warid</h1>
</header>

<div class="main-content">

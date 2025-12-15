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
            --blue: #007bff;
            --blue-dark: #0056b3;
            --orange: #ff9100;
            --orange-dark: #e07a00;

            --bg: #f5f8ff;
            --card: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
        }

        /* NAVBAR PREMIUM */
        .navbar-premium {
            background: linear-gradient(90deg, rgba(0,123,255,0.9), rgba(255,145,0,0.85));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 12px 0;
            box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        }

        .navbar-premium .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .navbar-premium .nav-link {
            color: #ffffff !important;
            font-weight: 600;
            margin-left: 18px;
            transition: 0.25s ease;
        }

        .navbar-premium .nav-link:hover {
            color: #222 !important;
            background: rgba(255,255,255,0.55);
            padding: 6px 14px;
            border-radius: 8px;
        }

        /* FOOTER */
        footer {
            background: #ffffff;
            padding: 18px;
            border-top: 4px solid var(--orange);
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 50px;
        }
    </style>
</head>
<body>

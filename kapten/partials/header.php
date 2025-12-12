<?php
// header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Kapten Panel' ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --blue: #007bff;
            --orange: #ff8c00;
            --card: #ffffff;
            --bg: #f4f8ff;
        }

        body {
            background: var(--bg);
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(90deg, var(--blue), var(--orange));
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .navbar-custom .nav-link:hover {
            opacity: .8;
        }

        footer {
            background: white;
            border-top: 3px solid var(--orange);
            padding: 15px;
            margin-top: 40px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<?php
session_start();
include "../koneksi.php";
include "partials/header.php";
include "partials/navbar.php";
?>

<style>
.card {
    background: var(--card);
    padding: 25px;
    max-width: 600px;
    margin: auto;
    margin-top: 120px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    text-align: center;
}

.btn {
    background: var(--orange);
    padding: 12px 18px;
    color: white;
    border: none;
    border-radius: 10px;
    text-decoration: none;
    display: inline-block;
    margin-top: 15px;
    font-weight: bold;
}
</style>

<div class="card">
    <h2 style="color:var(--text-dark);">Selamat Datang, <?= $_SESSION['nama_admin'] ?></h2>
    <p>Akses cepat ke form produksi mesin.</p>

    <a class="btn" href="produksi_mesin.php">➕ Input Produksi Mesin</a>
</div>

<?php include "partials/footer.php"; ?>

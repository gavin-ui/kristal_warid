<?php

$host     = "localhost";   // biasanya tetap localhost kalau pakai hosting
$user     = "root";        // username database (kalau di hosting biasanya bukan root)
$password = "";            // isi password MySQL kamu
$database = "eskristal_warid"; // nama database yang kamu buat

// Koneksi ke MySQL
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Optional (untuk memastikan format karakter aman, terutama input form)
mysqli_set_charset($conn, "utf8");
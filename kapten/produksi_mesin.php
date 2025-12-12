<?php
session_start();
include "../koneksi.php";
global $conn;
$pageTitle = "Input Produksi Mesin";
include "partials/header.php";
include "partials/navbar.php";

$message = "";
$success = "";

// PROSES SIMPAN
if (isset($_POST["submit"])) {

    $mesin      = $_POST["mesin"];
    $jam_mulai  = $_POST["jam_mulai"];
    $menit      = $_POST["menit"];
    $defroz     = $_POST["defroz"];
    $pack       = $_POST["pack"];
    $qty        = $_POST["qty"];
    $kristal    = $_POST["kristal"];
    $serut      = $_POST["serut"];
    $ket        = $_POST["keterangan"];
    $kapten_id  = $_SESSION["id_admin"]; // pastikan session ID tersimpan saat login

    $sql = "INSERT INTO produksi_mesin
            (kapten_id, mesin, jam_mulai, menit, defroz, pack, qty, kristal, serut, keterangan)
            VALUES 
            ('$kapten_id', '$mesin', '$jam_mulai', '$menit', '$defroz', '$pack', '$qty', '$kristal', '$serut', '$ket')";

    if (mysqli_query($conn, $sql)) {
        $success = "Data produksi berhasil disimpan!";
    } else {
        $message = "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>

<style>
.form-card {
    background: var(--card);
    padding: 30px;
    max-width: 780px;
    margin: auto;
    margin-top: 130px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
</style>

<div class="form-card">

    <h3 class="text-center text-primary mb-4">📄 Input Produksi Mesin</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <div class="mb-3">
            <label class="form-label">Mesin</label>
            <select name="mesin" class="form-select" required>
                <option value="">-- Pilih Mesin --</option>
                <option value="A">Mesin A</option>
                <option value="B">Mesin B</option>
            </select>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Jam Mulai</label>
                <input type="time" class="form-control" name="jam_mulai" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Durasi (menit)</label>
                <input type="number" class="form-control" name="menit" required>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-4">
                <label class="form-label">Defroz</label>
                <input type="number" class="form-control" name="defroz" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Pack</label>
                <input type="number" class="form-control" name="pack" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Qty</label>
                <input type="number" class="form-control" name="qty" required>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <label class="form-label">Kristal (kg)</label>
                <input type="number" class="form-control" name="kristal" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Serut (opsional)</label>
                <input type="number" class="form-control" name="serut">
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea class="form-control" name="keterangan"></textarea>
        </div>

        <button class="btn btn-warning w-100 mt-4 fw-bold" name="submit">💾 Simpan Data</button>

    </form>
</div>

<?php include "partials/footer.php"; ?>

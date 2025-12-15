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
.form-wrapper {
    max-width: 920px;
    margin: auto;
    margin-top: 140px;
    padding: 0 20px;
}

.form-card {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.96),
        rgba(255,255,255,0.88)
    );
    padding: 45px;
    border-radius: 24px;
    box-shadow:
        0 25px 45px rgba(0,0,0,0.15),
        inset 0 0 0 1px rgba(255,255,255,0.6);
    position: relative;
    overflow: hidden;
}

/* Decorative gradient blob */
.form-card::before {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(0,123,255,.25), transparent 60%);
    top: -100px;
    left: -100px;
}

.form-card::after {
    content: "";
    position: absolute;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(255,145,0,.28), transparent 60%);
    bottom: -120px;
    right: -120px;
}

.form-title {
    font-weight: 700;
    font-size: 1.9rem;
    margin-bottom: 5px;
}

.form-sub {
    color: #555;
    margin-bottom: 30px;
}

.form-label {
    font-weight: 600;
    color: #333;
}

.form-control,
.form-select {
    border-radius: 12px;
    padding: 12px 14px;
    border: 1.8px solid #d6d9e0;
    transition: .25s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(0,123,255,.2);
}

.btn-save {
    background: linear-gradient(90deg, var(--blue), var(--orange));
    border: none;
    border-radius: 16px;
    padding: 14px;
    font-weight: 700;
    color: white;
    transition: .3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,.25);
}

.section-title {
    font-weight: 700;
    margin: 35px 0 15px;
    color: var(--blue);
}
</style>


<div class="form-wrapper">
<div class="form-card">

    <h2 class="form-title text-center">
        📄 Input Produksi Mesin
    </h2>
    <p class="form-sub text-center">
        Catat hasil produksi mesin secara akurat dan profesional.
    </p>

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

        <div class="section-title">⏱ Waktu Produksi</div>

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

        <div class="section-title">📦 Hasil Produksi</div>

        <div class="row g-3">
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

        <div class="section-title">📝 Catatan</div>

        <textarea class="form-control" name="keterangan" rows="3"
            placeholder="Tambahkan keterangan jika diperlukan..."></textarea>

        <button class="btn-save w-100 mt-4" name="submit">
            💾 Simpan Data Produksi
        </button>

    </form>

</div>
</div>


<?php include "partials/footer.php"; ?>

<?php
session_start();
include "../koneksi.php";
global $conn;

$pageTitle = "Input Produksi Mesin";
include "partials/header.php";
include "partials/navbar.php";

$message = "";
$success = "";

/* ======================
   PROSES SIMPAN
====================== */
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
    $kapten_id  = $_SESSION["id_admin"];

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
/* ================= ROOT ================= */
:root{
    --ice-blue:#3b82f6;
    --ice-soft:#93c5fd;
    --navy:#020617;
    --navy-soft:#020617cc;
    --border-ice:rgba(147,197,253,.35);
}

/* ================= FORM WRAP ================= */
.form-wrapper{
    max-width:920px;
    margin:160px auto 60px;
    padding:0 20px;
}

/* ================= CARD ================= */
.form-card{
    background:linear-gradient(
        180deg,
        rgba(2,6,23,.88),
        rgba(2,6,23,.78)
    );
    backdrop-filter:blur(14px);
    -webkit-backdrop-filter:blur(14px);
    padding:44px;
    border-radius:26px;
    color:#e5e7eb;
    position:relative;
    overflow:hidden;

    border:1px solid var(--border-ice);
    box-shadow:
        0 30px 60px rgba(0,0,0,.55),
        inset 0 0 25px rgba(59,130,246,.15);
}

/* ICE GLOW */
.form-card::before{
    content:"";
    position:absolute;
    inset:0;
    background:
        radial-gradient(circle at top left,
            rgba(147,197,253,.18),
            transparent 45%),
        radial-gradient(circle at bottom right,
            rgba(59,130,246,.18),
            transparent 50%);
    pointer-events:none;
}

/* ================= TITLE ================= */
.form-title{
    font-weight:900;
    font-size:1.9rem;
    text-align:center;
    margin-bottom:6px;
}

.form-sub{
    text-align:center;
    color:#c7d2fe;
    margin-bottom:34px;
    opacity:.9;
}

/* ================= LABEL & INPUT ================= */
.form-label{
    font-weight:600;
    color:#cbd5f5;
}

.form-control,
.form-select{
    background:rgba(255,255,255,.05);
    border:1.5px solid rgba(148,163,184,.25);
    color:#e5e7eb;
    border-radius:14px;
    padding:12px 14px;
}

.form-control::placeholder{
    color:#94a3b8;
}

.form-control:focus,
.form-select:focus{
    background:rgba(255,255,255,.08);
    border-color:var(--ice-blue);
    box-shadow:0 0 0 3px rgba(59,130,246,.35);
    color:#fff;
}

/* ================= SECTION TITLE ================= */
.section-title{
    margin:36px 0 14px;
    font-weight:800;
    color:var(--ice-soft);
    letter-spacing:.3px;
}

/* ================= BUTTON ================= */
.btn-save{
    margin-top:26px;
    background:linear-gradient(135deg,#2563eb,#1e40af);
    border:none;
    border-radius:16px;
    padding:14px;
    font-weight:800;
    color:white;
    box-shadow:0 18px 40px rgba(37,99,235,.55);
    transition:.3s ease;
}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow:0 25px 50px rgba(37,99,235,.75);
}

/* ================= ALERT ================= */
.alert{
    border-radius:14px;
    border:none;
}
</style>

<div class="form-wrapper">
<div class="form-card">

    <h2 class="form-title">📄 Input Produksi Mesin</h2>
    <p class="form-sub">
        Catat hasil produksi mesin secara akurat dan profesional
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

        <button class="btn-save w-100" name="submit">
            💾 Simpan Data Produksi
        </button>

    </form>

</div>
</div>

<?php include "partials/footer.php"; ?>

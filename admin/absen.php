<?php
session_start();
include "../koneksi.php";
date_default_timezone_set("Asia/Jakarta");

/* =========================================================
   BUAT FOLDER SELFIE
========================================================= */
$dirSelfie = __DIR__ . "/../uploads/selfie/";
if (!is_dir($dirSelfie)) mkdir($dirSelfie, 0777, true);

/* =========================================================
   CEK MODE ABSEN
========================================================= */
function cekMode($conn, $id_karyawan) {
    $today = date("Y-m-d");

    $q = $conn->prepare("
        SELECT * FROM absensi 
        WHERE id_karyawan=? AND DATE(waktu_masuk)=?
        ORDER BY id_absen DESC LIMIT 1
    ");
    $q->bind_param("is", $id_karyawan, $today);
    $q->execute();
    $r = $q->get_result();

    if ($r->num_rows === 0) return "MASUK";

    $d = $r->fetch_assoc();

    if (!empty($d['waktu_pulang'])) return "DONE";

    if (time() - strtotime($d['waktu_masuk']) > 43200) return "RESET";

    return "PULANG";
}

/* =========================================================
   PROSES ABSEN
========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===== DATA DARI QR ===== */
    $nama   = trim($_POST['nama_karyawan'] ?? '');
    $divisi = trim($_POST['divisi'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    /* ===== DATA TAMBAHAN ===== */
    $status = $_POST['status_kehadiran'] ?? 'HADIR';
    $alasan = $_POST['alasan'] ?? null;
    $lat    = $_POST['latitude'] ?? null;
    $lon    = $_POST['longitude'] ?? null;
    $selfie64 = $_POST['selfie_data'] ?? null;
    $fileSelfie = null;

if ($selfie64) {
    $raw = explode(",", $selfie64)[1];
    $binary = base64_decode($raw);
    $fileSelfie = "selfie_" . $id_karyawan . "_" . time() . ".jpg";
    file_put_contents($dirSelfie . $fileSelfie, $binary);
}


    if ($nama === '' || $divisi === '' || $alamat === '') {
        echo "<script>alert('QR tidak valid');location.href='absen.php';</script>";
        exit;
    }

    /* =====================================================
       AMBIL DATA KARYAWAN (SUMBER KEBENARAN)
    ===================================================== */
    $q = $conn->prepare("
        SELECT id_karyawan, nomor_karyawan 
        FROM karyawan 
        WHERE nama_karyawan=? AND divisi=? AND alamat=? 
        LIMIT 1
    ");
    $q->bind_param("sss", $nama, $divisi, $alamat);
    $q->execute();
    $res = $q->get_result();

    if ($res->num_rows === 0) {
        echo "<script>alert('Karyawan tidak terdaftar');location.href='absen.php';</script>";
        exit;
    }

    $k = $res->fetch_assoc();
    $id_karyawan    = $k['id_karyawan'];
    $nomor_karyawan = $k['nomor_karyawan'];

    /* =====================================================
       CEK IZIN HARI INI
    ===================================================== */
    $today = date("Y-m-d");
    $cekIzin = $conn->prepare("
        SELECT id_absen FROM absensi
        WHERE id_karyawan=? AND status_kehadiran='IZIN'
        AND DATE(waktu_masuk)=?
    ");
    $cekIzin->bind_param("is", $id_karyawan, $today);
    $cekIzin->execute();

    if ($cekIzin->get_result()->num_rows > 0) {
        echo "<script>alert('Anda sudah IZIN hari ini');location.href='absen.php';</script>";
        exit;
    }

    /* =====================================================
       IZIN
    ===================================================== */
    if ($status === "IZIN") {

        if (!$alasan || trim($alasan) === "") {
            echo "<script>alert('Alasan wajib diisi');location.href='absen.php';</script>";
            exit;
        }

        $q = $conn->prepare("
            INSERT INTO absensi
            (id_karyawan,nama_karyawan,nomor_karyawan,divisi,
            status_kehadiran,alasan,selfie_masuk,waktu_masuk)
            VALUES (?,?,?,?,?,?,?,NOW())
        ");
        $q->bind_param(
            "issssss",
            $id_karyawan,
            $nama,
            $nomor_karyawan,
            $divisi,
            $status,
            $alasan,
            $fileSelfie
        );
        $q->execute();


        echo "<script>alert('IZIN berhasil dicatat');location.href='absen.php';</script>";
        exit;
    }

    /* =====================================================
       SELFIE WAJIB
    ===================================================== */
    if (!$selfie64) {
        echo "<script>alert('Selfie tidak terdeteksi');location.href='absen.php';</script>";
        exit;
    }

    $raw = explode(",", $selfie64)[1];
    $binary = base64_decode($raw);
    $fileSelfie = "selfie_" . $id_karyawan . "_" . time() . ".jpg";
    file_put_contents($dirSelfie . $fileSelfie, $binary);

    /* =====================================================
       MODE ABSEN
    ===================================================== */
    $mode = cekMode($conn, $id_karyawan);

    if ($mode === "MASUK" || $mode === "RESET") {

        $q = $conn->prepare("
            INSERT INTO absensi
            (id_karyawan,nama_karyawan,nomor_karyawan,divisi,
             selfie_masuk,status_kehadiran,latitude,longitude,waktu_masuk)
            VALUES (?,?,?,?,?,?,?,?,NOW())
        ");
        $q->bind_param(
            "isssssss",
            $id_karyawan,
            $nama,
            $nomor_karyawan,
            $divisi,
            $fileSelfie,
            $status,
            $lat,
            $lon
        );
        $q->execute();

        echo "<script>alert('ABSEN MASUK berhasil');location.href='absen.php';</script>";
        exit;
    }

    if ($mode === "PULANG") {

        $x = $conn->prepare("
            SELECT id_absen FROM absensi
            WHERE id_karyawan=? AND DATE(waktu_masuk)=?
            LIMIT 1
        ");
        $x->bind_param("is", $id_karyawan, $today);
        $x->execute();
        $d = $x->get_result()->fetch_assoc();

        $q = $conn->prepare("
            UPDATE absensi
            SET selfie_pulang=?, latitude=?, longitude=?, waktu_pulang=NOW()
            WHERE id_absen=?
        ");
        $q->bind_param(
            "sssi",
            $fileSelfie,
            $lat,
            $lon,
            $d['id_absen']
        );
        $q->execute();

        echo "<script>alert('ABSEN PULANG berhasil');location.href='absen.php';</script>";
        exit;
    }

    echo "<script>alert('Absensi hari ini sudah lengkap');location.href='absen.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Absen Karyawan</title>

<style>
/* =====================================================
   GLOBAL
===================================================== */
body{
    margin:0;
    font-family:Inter,Arial,sans-serif;
    background:var(--body-bg);
    color:var(--text-color);
}

/* =====================================================
   PAGE WRAPPER (SCROLL AKTIF)
===================================================== */
.page-wrapper{
    padding-left:260px;
    padding-top:42px;
    padding-right:20px;
    padding-bottom:150px;
    transition:.3s ease;
}

body.collapsed .page-wrapper{
    padding-left:90px;
}

/* =====================================================
   CARD UTAMA (DIPERKECIL)
===================================================== */
.card{
    max-width:720px;            /* 🔥 lebih kecil */
    margin:auto;
    padding:22px 26px;          /* 🔥 lebih rapat */

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.96),
        rgba(255,255,255,.88)
    );

    border-radius:20px;
    border:1.5px solid rgba(255,186,39,.45);

    box-shadow:
        0 24px 45px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.7);

    position:relative;
}

/* Glow Accent */
.card::before{
    content:"";
    position:absolute;
    top:-70px;
    right:-70px;
    width:180px;
    height:180px;
    background:radial-gradient(circle, rgba(255,186,39,.25), transparent 70%);
    border-radius:50%;
}

/* =====================================================
   DARK MODE CARD
===================================================== */
body.dark .card{
    background:linear-gradient(
        180deg,
        rgba(18,30,60,.95),
        rgba(10,18,36,.92)
    );
    border:1px solid rgba(255,186,39,.35);
    box-shadow:
        0 24px 45px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.12);
}

/* =====================================================
   TITLE
===================================================== */
h2{
    text-align:center;
    margin-bottom:22px;
    font-weight:900;
    letter-spacing:.6px;
    font-size:24px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   QR READER (LEBIH COMPACT)
===================================================== */
#reader{
    width:100%;
    min-height:240px;
    border-radius:14px;
    background:#000;
    overflow:hidden;
    border:3px solid var(--accent);
    box-shadow:0 14px 25px rgba(0,0,0,.35);
}

/* =====================================================
   SWITCH CAMERA BUTTON
===================================================== */
#switchCam{
    margin-top:12px;
    width:100%;
    padding:10px;
    border-radius:12px;

    background:linear-gradient(135deg,#374151,#111827);
    color:#fff;

    font-weight:800;
    border:none;
    cursor:pointer;
    transition:.3s ease;
}

#switchCam:hover{
    transform:translateY(-2px);
}

/* =====================================================
   AFTER SCAN SECTION
===================================================== */
#afterScan{
    margin-top:24px;
}

#afterScan p{
    background:rgba(37,99,235,.08);
    padding:12px 16px;
    border-radius:12px;
    border-left:6px solid #2563eb;
    font-weight:600;
    font-size:14px;
}

body.dark #afterScan p{
    background:rgba(90,169,255,.12);
}

/* =====================================================
   GRID CAMERA + FORM (DIPERKECIL)
===================================================== */
.grid{
    display:grid;
    grid-template-columns:1fr 210px;
    gap:16px;
    margin-top:16px;
}

/* VIDEO SELFIE */
video{
    width:100%;
    border-radius:14px;
    background:#111;
    box-shadow:0 10px 22px rgba(0,0,0,.35);
}

/* =====================================================
   FORM ELEMENT
===================================================== */
label{
    display:block;
    margin-top:8px;
    font-weight:700;
    font-size:13px;
}

select, textarea{
    width:100%;
    padding:10px 12px;
    border-radius:12px;
    background:rgba(255,255,255,.92);
    border:1.6px solid var(--accent);
    font-size:13.5px;
    color:#0f172a;
}

textarea{
    resize:none;
    min-height:70px;
}

/* DARK MODE FORM */
body.dark select,
body.dark textarea{
    background:rgba(10,18,36,.85);
    color:#e5e7eb;
    border-color:rgba(90,169,255,.45);
}

/* =====================================================
   SUBMIT BUTTON (COMPACT)
===================================================== */
button#kirimBtn{
    margin-top:22px;
    padding:14px;
    width:100%;

    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;

    font-size:15px;
    font-weight:900;
    letter-spacing:.5px;

    border-radius:18px;
    border:3px solid #f59e0b;

    box-shadow:
        0 0 0 4px rgba(245,158,11,.35),
        0 18px 30px rgba(37,99,235,.5);

    cursor:pointer;
    transition:.35s ease;
}

button#kirimBtn:hover{
    transform:translateY(-2px);
    box-shadow:
        0 0 0 6px rgba(245,158,11,.55),
        0 26px 45px rgba(37,99,235,.65);
}

/* =====================================================
   MOBILE
===================================================== */
@media(max-width:768px){
    .page-wrapper{
        padding-left:0;
        padding:28px 14px 130px;
    }

    .card{
        padding:20px 18px;
    }

    .grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>
<body>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="page-wrapper">

<div class="card">

<h2>Scan QR Untuk Absen</h2>

<div id="reader"></div>

<button id="switchCam"
    style="margin-top:10px;background:#333;color:white;padding:8px;border-radius:6px;">
    Ganti Kamera
</button>

<div id="afterScan" style="display:none;margin-top:20px;">

    <p>
        <b>Nama:</b> <span id="det_nama"></span><br>
        <b>Divisi:</b> <span id="det_divisi"></span>
    </p>

    <form method="POST" id="absenForm">

    <div class="grid">
        <div>
            <label>Selfie Auto</label>
            <video id="camera" autoplay playsinline></video>
            <canvas id="canvas" style="display:none;"></canvas>
        </div>

        <div>

            <label>Status</label>
            <select name="status_kehadiran" id="status">
                <option value="HADIR">Hadir</option>
                <option value="IZIN">Izin</option>
            </select>

            <div id="alasanBox" style="display:none;">
                <label>Alasan</label>
                <textarea name="alasan"></textarea>
            </div>
        </div>
    </div>

    <input type="hidden" name="nama_karyawan" id="nama">
    <input type="hidden" name="divisi" id="divisi">
    <input type="hidden" name="alamat" id="alamat">

    <input type="hidden" name="selfie_data" id="selfie_data">
    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lon">


    <button type="submit" id="kirimBtn">Kirim Absensi</button>
    </form>
</div>

</div>
</div>

<?php include "partials/footer.php"; ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8"></script>
<script>
let qr;
let sudahScan = false;
let streamSelfie = null;

document.addEventListener("DOMContentLoaded", async () => {

    qr = new Html5Qrcode("reader");

    try {
        await qr.start(
            { facingMode: "environment" },
            { fps: 8, qrbox: 250 },
            onScanSuccess
        );
    } catch (err) {
        console.error("QR ERROR:", err);
        alert("Gagal start kamera QR");
    }
});

async function onScanSuccess(decodedText) {

    if (sudahScan) return;
    sudahScan = true;

    const data = decodedText.split("|");
    if (data.length !== 3) {
        alert("QR tidak valid");
        sudahScan = false;
        return;
    }

    // isi hidden input
    document.getElementById("nama").value   = data[0].trim();
    document.getElementById("divisi").value = data[1].trim();
    document.getElementById("alamat").value = data[2].trim();

    document.getElementById("afterScan").style.display = "block";
    document.getElementById("det_nama").innerText   = data[0];
    document.getElementById("det_divisi").innerText = data[1];

    // 🔴 STOP QR TOTAL
    try {
        await qr.stop();
        await qr.clear();
    } catch (e) {}

    // sembunyikan reader
    document.getElementById("reader").style.display = "none";

    // beri waktu kamera dilepas OS
    setTimeout(startSelfieCamera, 800);
}

/* ==========================
   SELFIE CAMERA (BENAR)
========================== */
function startSelfieCamera() {

    const video = document.getElementById("camera");

    navigator.mediaDevices.getUserMedia({
        video: { facingMode: "user" },
        audio: false
    })
    .then(stream => {
        streamSelfie = stream;
        video.srcObject = stream;
        video.play();
    })
    .catch(err => {
        console.error("SELFIE ERROR:", err);
        alert("Kamera selfie tidak bisa dibuka");
    });
}

/* ==========================
   SUBMIT ABSEN
========================== */
document.getElementById("absenForm").addEventListener("submit", function () {

    const video  = document.getElementById("camera");
    const canvas = document.getElementById("canvas");

    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext("2d").drawImage(video, 0, 0);

    document.getElementById("selfie_data").value =
        canvas.toDataURL("image/jpeg");

    // matikan kamera selfie
    if (streamSelfie) {
        streamSelfie.getTracks().forEach(t => t.stop());
        streamSelfie = null;
    }
});
</script>

<script>
const statusSelect = document.getElementById("status");
const alasanBox = document.getElementById("alasanBox");

statusSelect.addEventListener("change", function () {
    if (this.value === "IZIN") {
        alasanBox.style.display = "block";
    } else {
        alasanBox.style.display = "none";
    }
});
</script>

</body>
</html>

<?php
include "../koneksi.php";
date_default_timezone_set("Asia/Jakarta");

/* ---------------------------------------------------------
   CREATE DIR
--------------------------------------------------------- */
function ensureDir($p) {
    if (!is_dir($p)) mkdir($p, 0777, true);
}
ensureDir(__DIR__ . "/../uploads/selfie/");

/* ---------------------------------------------------------
   MODE ABSENSI
--------------------------------------------------------- */
function cekMode($conn, $id) {
    $today = date("Y-m-d");

    $q = $conn->prepare("SELECT * FROM absensi 
        WHERE id_karyawan=? AND DATE(waktu_masuk)=? 
        ORDER BY id_absen DESC LIMIT 1");
    $q->bind_param("is", $id, $today);
    $q->execute();
    $r = $q->get_result();

    if ($r->num_rows == 0) return "MASUK";

    $data = $r->fetch_assoc();

    if ($data['waktu_pulang']) return "DONE";

    if (time() - strtotime($data['waktu_masuk']) > 43200)
        return "RESET";

    return "PULANG";
}

/* ---------------------------------------------------------
   VALIDASI + SIMPAN
--------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id       = intval($_POST['id_karyawan']);
    $nama     = $_POST['nama_karyawan'];
    $nomor    = $_POST['nomor_karyawan'];
    $divisi   = $_POST['divisi'];
    $status   = $_POST['status_kehadiran'];
    $alasan   = $_POST['alasan'] ?? null;
    $lat      = $_POST['latitude'];
    $lon      = $_POST['longitude'];
    $selfie64 = $_POST['selfie_data'];

    if (!$id) {
        echo "<script>alert('Data tidak lengkap'); location.href='absen.php';</script>";
        exit;
    }

    /* CEK KARYAWAN */
    $c = $conn->prepare("SELECT nomor_karyawan FROM karyawan WHERE id_karyawan=?");
    $c->bind_param("i", $id);
    $c->execute();
    $res = $c->get_result();
    if ($res->num_rows == 0) {
        echo "<script>alert('Karyawan tidak ditemukan'); location.href='absen.php';</script>";
        exit;
    }
    $db = $res->fetch_assoc();

    if (trim($db['nomor_karyawan']) !== trim($nomor)) {
        echo "<script>alert('Nomor karyawan tidak cocok'); location.href='absen.php';</script>";
        exit;
    }

    /* Cek apakah sudah izin hari ini — tidak boleh absen lagi */
$today = date("Y-m-d");
$cekIzin = $conn->prepare("SELECT id_absen FROM absensi 
    WHERE id_karyawan=? AND status_kehadiran='IZIN' AND DATE(waktu_masuk)=?");
$cekIzin->bind_param("is", $id, $today);
$cekIzin->execute();
$resIzin = $cekIzin->get_result();

if ($resIzin->num_rows > 0) {
    echo "<script>alert('Anda sudah mengajukan IZIN hari ini dan tidak dapat absen lagi.'); location.href='absen.php';</script>";
    exit;
}

    /* ======================================================================
       IZIN (langsung insert tanpa selfie/pulang)
    ====================================================================== */
    if ($status === "IZIN") {

        if (!$alasan || trim($alasan) == "") {
            echo "<script>alert('Alasan wajib diisi'); location.href='absen.php';</script>";
            exit;
        }

        $q = $conn->prepare("INSERT INTO absensi 
            (id_karyawan, nama_karyawan, nomor_karyawan, divisi, status_kehadiran, alasan, waktu_masuk)
            VALUES (?,?,?,?,?,?,NOW())");

        $q->bind_param("isssss",
            $id, $nama, $nomor, $divisi, $status, $alasan
        );
        $q->execute();

        echo "<script>alert('Izin berhasil dicatat!'); location.href='absen.php';</script>";
        exit;
    }

    /* ====================================================================== */

    /* Selfie wajib untuk HADIR */
    if (!$selfie64) {
        echo "<script>alert('Selfie tidak terdeteksi'); location.href='absen.php';</script>";
        exit;
    }

    /* SIMPAN SELFIE */
    $selfieRaw = explode(",", $selfie64)[1];
    $binary = base64_decode($selfieRaw);
    $fileName = "selfie_" . $id . "_" . time() . ".jpg";
    file_put_contents("../uploads/selfie/" . $fileName, $binary);

    /* ABSEN HADIR MASUK / PULANG */
    $mode = cekMode($conn, $id);
    $today = date("Y-m-d");

    if ($mode == "MASUK" || $mode == "RESET") {
        $q = $conn->prepare("INSERT INTO absensi 
            (id_karyawan,nama_karyawan,nomor_karyawan,divisi,
             selfie_masuk,status_kehadiran,alasan,latitude,longitude,waktu_masuk) 
            VALUES (?,?,?,?,?,?,?,?,?,NOW())");

        $q->bind_param("issssssss",
            $id,$nama,$nomor,$divisi,$fileName,$status,$alasan,$lat,$lon
        );
        $q->execute();

        echo "<script>alert('Absensi MASUK berhasil!'); location.href='absen.php';</script>";
        exit;
    }

    if ($mode == "PULANG") {

        $x = $conn->prepare("SELECT id_absen FROM absensi WHERE id_karyawan=? AND DATE(waktu_masuk)=? LIMIT 1");
        $x->bind_param("is", $id, $today);
        $x->execute();
        $d = $x->get_result()->fetch_assoc();

        $q = $conn->prepare("UPDATE absensi 
            SET selfie_pulang=?, latitude=?, longitude=?, waktu_pulang=NOW()
            WHERE id_absen=?");

        $q->bind_param("sssi", $fileName, $lat, $lon, $d['id_absen']);
        $q->execute();

        echo "<script>alert('Absensi PULANG berhasil!'); location.href='absen.php';</script>";
        exit;
    }

    echo "<script>alert('Anda sudah absen lengkap hari ini'); location.href='absen.php';</script>";
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

/* PREVIEW SELFIE */
#previewSelfie{
    width:100%;
    height:240px;
    border-radius:16px;
    border:3px solid #f59e0b;
    object-fit:cover;
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

    #previewSelfie{
        height:220px;
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
        <b>Nomor:</b> <span id="det_nomor"></span><br>
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
                <label>Preview</label>
                <img id="previewSelfie">

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

        <input type="hidden" name="id_karyawan" id="id">
        <input type="hidden" name="nama_karyawan" id="nama">
        <input type="hidden" name="nomor_karyawan" id="nomor">
        <input type="hidden" name="divisi" id="divisi">

        <input type="hidden" name="selfie_data" id="selfie_data">
        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lon">

        <button type="button" id="kirimBtn">Kirim Absensi</button>

    </form>

</div>

</div>
</div>

<?php include "partials/footer.php"; ?>
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
/* =========================================================
   QR CAMERA - AUTO SELECT
========================================================= */

let qr = new Html5Qrcode("reader");
let currentFacing = "user";

function startQR() {

    Html5Qrcode.getCameras().then(cameras => {

        if (cameras.length === 0) {
            alert("Tidak ada kamera ditemukan!");
            return;
        }

        let camId = cameras[0].id;
        cameras.forEach(c => {
            if (c.label.toLowerCase().includes("back")) {
                camId = c.id;
                currentFacing = "environment";
            }
        });

        qr.start(camId, { fps:10, qrbox:250 }, onScanSuccess);
    });
}

document.getElementById("switchCam").onclick = () => {

    Html5Qrcode.getCameras().then(cameras => {

        if (cameras.length <= 1) {
            alert("Tidak ada kamera lain.");
            return;
        }

        let camId = cameras[0].id;
        currentFacing = (currentFacing==="user") ? "environment" : "user";

        cameras.forEach(c => {
            if (currentFacing==="environment" && c.label.toLowerCase().includes("back")) camId = c.id;
            if (currentFacing==="user" && c.label.toLowerCase().includes("front")) camId = c.id;
        });

        qr.stop().then(() => {
            qr.start(camId, { fps:10, qrbox:250 }, onScanSuccess);
        });

    });
};

startQR();

/* =========================================================
   RESET STATUS SETELAH SCAN QR
========================================================= */
let statusSelect = document.getElementById("status");
let alasanBox = document.getElementById("alasanBox");

function resetStatus() {
    statusSelect.value = "HADIR";
    alasanBox.style.display = "none";
}

/* =========================================================
   QR SUCCESS
========================================================= */
function onScanSuccess(decodedText) {

    qr.stop().then(()=>qr.clear());

    let data = {};
    decodedText.split("\n").forEach(v => {
        let p = v.split(":");
        if (p.length >= 2) {
            let key = p[0].trim();
            let val = p.slice(1).join(":").trim();
            data[key] = val;
        }
    });

    nama.value  = data["Nama"];
    nomor.value = data["Nomor"];
    id.value    = data["ID"];
    divisi.value= data["Divisi"];

    det_nama.innerText  = data["Nama"];
    det_nomor.innerText = data["Nomor"];
    det_divisi.innerText= data["Divisi"];

    resetStatus(); // FIX DI SINI

    afterScan.style.display="block";

    startCamera();
    getLocation();
}

/* =========================================================
   CAMERA SELFIE
========================================================= */
let video = document.getElementById("camera");
let canvas = document.getElementById("canvas");
let preview = document.getElementById("previewSelfie");

function startCamera() {

    navigator.mediaDevices.getUserMedia({ video:{facingMode:"user"} })
    .then(stream => {
        video.srcObject = stream;
        startSelfieCapture();
    })
    .catch(err => {
        alert("Gagal membuka kamera: " + err.message);
    });
}

function startSelfieCapture() {
    setInterval(()=>{
        if (!video.srcObject) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let c = canvas.getContext("2d");
        c.drawImage(video,0,0);

        let data = canvas.toDataURL("image/jpeg",0.9);
        preview.src = data;
        selfie_data.value = data;

    }, 900);
}

/* =========================================================
   LOCATION
========================================================= */
function getLocation(){
    navigator.geolocation.getCurrentPosition(pos=>{
        lat.value = pos.coords.latitude;
        lon.value = pos.coords.longitude;
    });
}

/* =========================================================
   IZIN ALASAN FIELD
========================================================= */
statusSelect.onchange = ()=>{
    if (statusSelect.value === "IZIN") {
        alasanBox.style.display = "block";
    } else {
        alasanBox.style.display = "none";
    }
};

/* =========================================================
   SUBMIT
========================================================= */
kirimBtn.onclick = ()=>{

    if(statusSelect.value === "IZIN"){
        let al = document.querySelector("textarea").value.trim();
        if(!al){ alert("Alasan wajib diisi jika izin"); return; }
    }

    if(!confirm("Kirim absensi?")) return;

    absenForm.submit();
};
</script>

</body>
</html>

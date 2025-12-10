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

    if (!$id || !$selfie64) {
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

    if ($db['nomor_karyawan'] !== $nomor) {
        echo "<script>alert('Nomor karyawan tidak cocok'); location.href='absen.php';</script>";
        exit;
    }

    /* SIMPAN SELFIE */
    $selfieRaw = explode(",", $selfie64)[1];
    $binary = base64_decode($selfieRaw);

    $fileName = "selfie_" . $id . "_" . time() . ".jpg";
    file_put_contents("../uploads/selfie/" . $fileName, $binary);

    $mode = cekMode($conn, $id);
    $today = date("Y-m-d");

    /* ABSEN MASUK */
    if ($mode == "MASUK" || $mode == "RESET") {
        $q = $conn->prepare("INSERT INTO absensi 
            (id_karyawan,nama_karyawan,nomor_karyawan,divisi,
             selfie_masuk,status_kehadiran,alasan,latitude,longitude,waktu_masuk) 
            VALUES (?,?,?,?,?,?,?,?,?,NOW())");

        $q->bind_param("isssssssss",
            $id,$nama,$nomor,$divisi,$fileName,$status,$alasan,$lat,$lon
        );
        $q->execute();

        echo "<script>alert('Absensi MASUK berhasil!'); location.href='absen.php';</script>";
        exit;
    }

    /* ABSEN PULANG */
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

    /* DONE */
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
body {
    margin:0;
    font-family:Inter,Arial;
    background:var(--body-bg);
    color:var(--text-color);
}
.page-wrapper{
    padding-left:260px;
    padding-top:30px;
    padding-right:20px;
    padding-bottom:150px;
}
body.collapsed .page-wrapper{ padding-left:90px; }

.card{
    background:var(--card-bg);
    max-width:800px;
    margin:auto;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.1);
}
h2{
    text-align:center;
    color:var(--title-color);
    margin-bottom:20px;
    font-weight:800;
}

#reader{
    width:100%;
    min-height:270px;
    border-radius:10px;
    background:#000;
    overflow:hidden;
}

.grid{
    display:grid;
    grid-template-columns:1fr 200px;
    gap:15px;
    margin-top:15px;
}

video{
    width:100%;
    border-radius:10px;
    background:#111;
}

#previewSelfie{
    width:200px;
    height:260px;
    border-radius:10px;
    border:3px solid var(--accent);
    object-fit:cover;
}

label{
    font-weight:600;
    margin-top:8px;
}

select, textarea{
    width:100%;
    padding:10px;
    border-radius:8px;
    background:var(--card-bg);
    border:1px solid var(--accent);
    color:var(--text-color);
}

button{
    width:100%;
    padding:14px;
    background:var(--accent);
    border:none;
    color:white;
    border-radius:10px;
    margin-top:15px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
}
</style>
</head>
<body>

<?php include "partials/sidebar.php"; ?>

<div class="page-wrapper">

<div class="card">

<h2>Scan QR Untuk Absen</h2>

<!-- SCAN QR AREA -->
<div id="reader"></div>

<div id="afterScan" style="display:none;">

    <p><b>Nama:</b> <span id="det_nama"></span><br>
       <b>Nomor:</b> <span id="det_nomor"></span><br>
       <b>Divisi:</b> <span id="det_divisi"></span></p>

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
/* ---------------------------------------------------------
   SCAN QR MANUAL (PALING STABIL)
--------------------------------------------------------- */
let qr = new Html5Qrcode("reader");

qr.start(
    { facingMode:"environment" },
    { fps:10, qrbox:250 },
    function(decoded){
        qr.stop();

        let lines = decoded.split("\n");
        document.getElementById("nama").value  = lines[0].replace("Nama: ","");
        document.getElementById("id").value    = lines[1].replace("ID: ","");
        document.getElementById("divisi").value= lines[2].replace("Divisi: ","");
        document.getElementById("nomor").value = lines[3].replace("Nomor: ","");

        document.getElementById("det_nama").innerText = nama.value;
        document.getElementById("det_nomor").innerText = nomor.value;
        document.getElementById("det_divisi").innerText = divisi.value;

        document.getElementById("afterScan").style.display="block";

        startCamera();
        getLocation();
    }
);

/* ---------------------------------------------------------
   CAMERA SELFIE (AUTO CAPTURE)
--------------------------------------------------------- */
let video = document.getElementById("camera");
let canvas = document.getElementById("canvas");
let preview = document.getElementById("previewSelfie");
let interval;

function startCamera() {
    navigator.mediaDevices.getUserMedia({video:true})
    .then(s => {
        video.srcObject = s;
        interval = setInterval(()=>{
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            let c = canvas.getContext("2d");
            c.drawImage(video,0,0);

            let data = canvas.toDataURL("image/jpeg",0.85);
            preview.src = data;
            document.getElementById("selfie_data").value = data;
        }, 1000);
    });
}

/* ---------------------------------------------------------
   LOCATION
--------------------------------------------------------- */
function getLocation(){
    navigator.geolocation.getCurrentPosition(p=>{
        lat.value = p.coords.latitude;
        lon.value = p.coords.longitude;
    });
}

/* ---------------------------------------------------------
   ALASAN IZIN
--------------------------------------------------------- */
document.getElementById("status").onchange = ()=>{
    document.getElementById("alasanBox").style.display =
        (status.value == "IZIN") ? "block" : "none";
};

/* ---------------------------------------------------------
   SUBMIT ABSEN
--------------------------------------------------------- */
document.getElementById("kirimBtn").onclick = ()=>{
    if(status.value=="IZIN"){
        let al = document.querySelector("textarea").value.trim();
        if(!al){ alert("Alasan wajib diisi jika Izin"); return; }
    }

    if(!confirm("Kirim absensi?")) return;

    document.getElementById("absenForm").submit();
};
</script>

</body>
</html>

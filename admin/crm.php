<?php
include "../koneksi.php";

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    $id = (int)$_POST['id_crm'];

    mysqli_query($conn,"UPDATE crm SET
        nama_lengkap='".mysqli_real_escape_string($conn,$_POST['nama_lengkap'])."',
        nama_outlet='".mysqli_real_escape_string($conn,$_POST['nama_outlet'])."',
        qty='".intval($_POST['qty'])."',
        jenis_es='".mysqli_real_escape_string($conn,$_POST['jenis_es'])."',
        kota_kabupaten='".mysqli_real_escape_string($conn,$_POST['kota_kabupaten'])."',
        alamat_lengkap='".mysqli_real_escape_string($conn,$_POST['alamat_lengkap'])."',
        lokasi='".mysqli_real_escape_string($conn,$_POST['lokasi'])."',
        marketing='".mysqli_real_escape_string($conn,$_POST['marketing'])."',
        no_hp='".mysqli_real_escape_string($conn,$_POST['no_hp'])."',
        jalur='".mysqli_real_escape_string($conn,$_POST['jalur'])."',
        keterangan_crm='".mysqli_real_escape_string($conn,$_POST['keterangan_crm'])."'
    WHERE id_crm=$id");

    header("Location: crm.php");
    exit;
}

/* ================= INSERT ================= */
if (isset($_POST["submit"])) {
    $nama_lengkap   = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nama_outlet    = mysqli_real_escape_string($conn, $_POST['nama_outlet']);
    $lokasi         = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $marketing      = mysqli_real_escape_string($conn, $_POST['marketing']);
    $no_hp          = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $jalur          = mysqli_real_escape_string($conn, $_POST['jalur']);
    $ket            = mysqli_real_escape_string($conn, $_POST['keterangan_crm']);

    $qty            = intval($_POST['qty']);
    $jenis_es = mysqli_real_escape_string($conn, $_POST['jenis_es']); // nilai
    $kota           = mysqli_real_escape_string($conn, $_POST['kota_kabupaten']);
    $alamat_lengkap = mysqli_real_escape_string($conn, $_POST['alamat_lengkap']);

    $foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = "crm_" . time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/foto_crm/".$foto);
    }

    mysqli_query($conn,"INSERT INTO crm
    (nama_lengkap,nama_outlet,lokasi,marketing,no_hp,jalur,
     qty,jenis_es,kota_kabupaten,alamat_lengkap,keterangan_crm,foto)
    VALUES
    ('$nama_lengkap','$nama_outlet','$lokasi','$marketing','$no_hp','$jalur',
     '$qty','$jenis_es','$kota','$alamat_lengkap','$ket','$foto')");

    header("Location: crm.php");
    exit;
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    mysqli_query($conn,"DELETE FROM crm WHERE id_crm=".(int)$_GET['delete']);
    header("Location: crm.php");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
   PAGE CONTAINER
===================================================== */
.page-container{
    margin-left:290px;
    padding:42px 28px 160px;
    min-height:100vh;
    transition:.35s ease;
}
body.collapsed .page-container{
    margin-left:110px;
}

/* =====================================================
   FORM CARD (PREMIUM COMPACT)
===================================================== */
.form-card{
    max-width:760px;
    margin:auto;
    padding:30px 34px;

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.96),
        rgba(255,255,255,.88)
    );

    border-radius:26px;
    border:1.5px solid rgba(245,158,11,.45);

    box-shadow:
        0 28px 55px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.75);

    position:relative;
}

/* Glow */
.form-card::before{
    content:"";
    position:absolute;
    top:-70px;
    right:-70px;
    width:180px;
    height:180px;
    background:radial-gradient(circle, rgba(245,158,11,.25), transparent 70%);
    border-radius:50%;
}

/* =====================================================
   DARK MODE FORM CARD
===================================================== */
body.dark .form-card{
    background:linear-gradient(
        180deg,
        rgba(12,22,40,.96),
        rgba(8,16,32,.94)
    );
    border:1px solid rgba(90,169,255,.25);
    box-shadow:
        0 24px 45px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.15);
}

/* =====================================================
   TITLE
===================================================== */
.form-card h2{
    text-align:center;
    font-size:26px;
    font-weight:900;
    margin-bottom:28px;
    letter-spacing:.6px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   FORM INPUT
===================================================== */
input,
textarea{
    width:100%;
    padding:13px 16px;
    border-radius:16px;
    border:1.6px solid #cbd5e1;
    margin-bottom:14px;
    font-size:14px;

    background:rgba(255,255,255,.92);
    transition:.3s ease;
}

textarea{
    resize:none;
    min-height:90px;
}

/* Focus */
input:focus,
textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.25);
    outline:none;
}

/* =====================================================
   DARK MODE INPUT
===================================================== */
body.dark input,
body.dark textarea{
    background:rgba(10,18,36,.9);
    border-color:rgba(90,169,255,.35);
    color:#fff;
}

body.dark input::placeholder,
body.dark textarea::placeholder{
    color:#9ca3af;
}

/* =====================================================
   BUTTON
===================================================== */
.btn-save,
.detail-btn{
    width:100%;
    padding:15px;
    border-radius:20px;

    font-size:15px;
    font-weight:900;
    letter-spacing:.5px;

    border:none;
    cursor:pointer;
    transition:.35s ease;
}

.btn-save{
    margin-top:10px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;

    border:3px solid #f59e0b;
    box-shadow:
        0 0 0 4px rgba(245,158,11,.35),
        0 18px 30px rgba(37,99,235,.45);
}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow:
        0 0 0 6px rgba(245,158,11,.55),
        0 28px 45px rgba(37,99,235,.6);
}

.detail-btn{
    margin-top:14px;
    background:#0ea5e9;
    color:#fff;
}

/* =====================================================
   MODAL BACKGROUND
===================================================== */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    display:none;
    justify-content:center;
    align-items:flex-start;
    padding-top:70px;
    z-index:99999;
}

/* =====================================================
   MODAL BOX
===================================================== */
.modal-box{
    width:95%;
    max-width:1100px;
    max-height:85vh;
    overflow-y:auto;

    padding:26px 28px;
    border-radius:22px;

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.97),
        rgba(255,255,255,.9)
    );

    box-shadow:0 28px 55px rgba(0,0,0,.25);
}

/* DARK MODAL */
body.dark .modal-box{
    background:linear-gradient(
        180deg,
        rgba(12,22,40,.98),
        rgba(8,16,32,.96)
    );
}

/* =====================================================
   MODAL TITLE
===================================================== */
.modal-box h3{
    text-align:center;
    font-size:22px;
    font-weight:900;
    margin-bottom:18px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* ===============================
   FIX TEXT MODAL TERANG / GELAP
================================ */

/* MODE TERANG */
.modal-box,
.modal-box td,
.modal-box th{
    color:#0f172a;
}

/* MODE GELAP */
body.dark .modal-box,
body.dark .modal-box td,
body.dark .modal-box th{
    color:#e5e7eb;
}

/* BORDER TABLE DARK MODE */
body.dark .modal-box td{
    border-bottom:1px solid rgba(255,255,255,.12);
}
/* =====================================================
   TABLE
===================================================== */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:16px;
    font-size:13px;
}

th,td{
    padding:10px 12px;
    border-bottom:1px solid rgba(0,0,0,.1);
    text-align:center;
}

th{
    background:#f59e0b;
    color:#000;
    font-weight:800;
}

/* DARK TABLE */
body.dark th{
    background:#1d4ed8;
    color:#fff;
}
body.dark td{
    color:#e5e7eb;
    border-color:rgba(255,255,255,.1);
}

/* =====================================================
   IMAGE CRM
===================================================== */
img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #cbd5e1;
}

/* =====================================================
   ACTION BUTTON
===================================================== */
.edit-btn,
.delete-btn{
    padding:7px 12px;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
    border:none;
}

.edit-btn{background:#facc15;}
.delete-btn{background:#ef4444;color:#fff;}

/* =====================================================
   MOBILE
===================================================== */
@media(max-width:768px){
    .page-container{
        margin-left:0;
        padding:32px 16px 140px;
    }

    .form-card{
        padding:26px 22px;
    }
}

/* ===============================
   MODAL DETAIL LEBIH BESAR & LEGA
   (OVERRIDE AMAN - TIDAK GANTI CSS LAMA)
================================ */

#modalCRM .modal-box{
    max-width:1400px;
    width:96%;
    max-height:90vh;
    padding:32px 36px;
}

#modalCRM table{
    font-size:14px;
}

#modalCRM th,
#modalCRM td{
    padding:14px 16px;
    vertical-align:top;
}

/* =====================================================
   CENTER SEMUA TEKS DI TABEL MODAL DETAIL
===================================================== */
#modalCRM table th,
#modalCRM table td{
    text-align: center !important;
    vertical-align: middle;
}

/* Scroll horizontal jika kolom makin banyak */
#modalCRM .modal-box{
    overflow-x:auto;
}

select{
    width:100%;
    padding:13px 16px;
    border-radius:16px;
    border:1.6px solid #cbd5e1;
    margin-bottom:14px;
    font-size:14px;
    background:rgba(255,255,255,.92);
    transition:.3s ease;
}

select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.25);
    outline:none;
}

/* DARK MODE SELECT */
body.dark select{
    background:rgba(10,18,36,.9);
    border-color:rgba(90,169,255,.35);
    color:#fff;
}

body.dark select option{
    background:#0a1224;
    color:#fff;
}
/* =====================================================
   SAMAKAN WARNA TEKS INPUT (LIGHT & DARK)
   AMAN - HANYA WARNA TEKS
===================================================== */

/* MODE TERANG */
input,
textarea,
select{
    color:#0f172a; /* sama seperti text utama */
}

input::placeholder,
textarea::placeholder{
    color:#64748b; /* abu konsisten */
}

/* MODE GELAP */
body.dark input,
body.dark textarea,
body.dark select{
    color:#e5e7eb; /* putih lembut */
}

body.dark input::placeholder,
body.dark textarea::placeholder{
    color:#9ca3af;
}

/* OPTION SELECT MODE GELAP */
body.dark select option{
    color:#e5e7eb;
    background:#0a1224;
}

/* =====================================================
   AKSI BUTTON RAPIN & SEJAJAR
===================================================== */
.aksi-btn{
    display:flex;
    gap:10px;              /* jarak antar tombol */
    justify-content:center;
    align-items:center;
}

.aksi-btn .edit-btn,
.aksi-btn .delete-btn{
    min-width:70px;
    text-align:center;
}

/* =====================================================
   MAP PREVIEW DI MODAL DETAIL
===================================================== */
.map-preview-link{
    display:inline-block;
    border-radius:12px;
    overflow:hidden;
    border:1px solid #cbd5e1;
    transition:.3s ease;
}

.map-preview-link iframe{
    width:180px;
    height:120px;
    border:0;
    pointer-events:none; /* supaya klik masuk link */
}

/* Hover efek */
.map-preview-link:hover{
    transform:scale(1.03);
    box-shadow:0 10px 25px rgba(0,0,0,.25);
}

/* Dark mode */
body.dark .map-preview-link{
    border-color:rgba(255,255,255,.2);
}

/* =========================================
   HILANGKAN SPINNER INPUT NUMBER
========================================= */

/* Chrome, Edge, Safari */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number]{
    -moz-appearance: textfield;
}

/* ===============================
   FORM INNER SPACING (AMAN)
================================ */
.form-card form{
    padding: 4px; /* jarak ekstra agar input tidak mepet */
}

.form-card input,
.form-card textarea,
.form-card select{
    box-sizing: border-box;
}

/* ===============================
   MODAL CONTENT SPACING
================================ */
.modal-box{
    padding: 32px 36px; /* kiri kanan lebih lega */
}

/* Tambahan khusus tabel */
.modal-box table{
    margin: 0 auto;
    width: calc(100% - 10px);
}

/* ===============================
   MODAL EDIT FORM SPACING
================================ */
#modalEditCRM form{
    padding: 6px;
}

#modalEditCRM input,
#modalEditCRM textarea,
#modalEditCRM select{
    box-sizing: border-box;
}

.form-card input,
.form-card textarea,
.form-card select,
#modalEditCRM input,
#modalEditCRM textarea,
#modalEditCRM select{
    margin-bottom: 16px;

/* =========================================
   CEGAH MAP NEMBRUS FOOTER SAAT SCROLL
========================================= */
.page-container{
    padding-bottom: 260px; /* sesuaikan tinggi footer */
}

#map,
#mapEdit{
    margin-bottom: 40px; /* jarak aman bawah */
}

}

/* ===============================
   FIX Z-INDEX LEAFLET MAP
   AGAR TIDAK NEMBUS HEADER & FOOTER
================================ */

/* container map */
#map,
#mapEdit {
    position: relative;
    z-index: 1;
}

/* semua layer leaflet */
.leaflet-pane,
.leaflet-map-pane,
.leaflet-control-container {
    z-index: 2 !important;
}

/* tombol zoom + - */
.leaflet-control-zoom {
    z-index: 3 !important;
}

/* popup / tooltip */
.leaflet-popup {
    z-index: 4 !important;
}
</style>

<div class="page-container">
<div class="form-card">
<h2>📇 Input CRM</h2>

<form method="POST" enctype="multipart/form-data">

    <input name="nama_lengkap" placeholder="Nama Lengkap" required>
    <input name="nama_outlet" placeholder="Nama Outlet">

    <input type="number" name="qty" placeholder="Qty (Jumlah)" required>

    <select name="jenis_es" required>
        <option value="">Pilih Jenis Es</option>
        <option value="Kristal">Kristal</option>
        <option value="Serut">Serut</option>
    </select>

    <select name="kota_kabupaten" id="kota" required>
        <option value="">Pilih Kota / Kabupaten</option>
        <option value="Kota Magelang">Kota Magelang</option>
        <option value="Kabupaten Magelang">Kabupaten Magelang</option>
    </select>

    <textarea name="alamat_lengkap" id="alamat_lengkap"
        placeholder="Alamat Lengkap"
        style="display:none"></textarea>

    <input name="lokasi" id="lokasi" readonly placeholder="Klik peta untuk memilih lokasi">
    <div id="map" style="height:300px;margin-bottom:14px;border-radius:14px"></div>

    <input name="marketing" placeholder="Marketing">
    <input name="no_hp" placeholder="No HP">
    <input name="jalur" placeholder="Jalur">
    <textarea name="keterangan_crm" placeholder="Keterangan"></textarea>

    <input type="file" name="foto">

    <button class="btn-save" name="submit">💾 Simpan</button>
</form>

<button class="detail-btn" onclick="openModal()">📋 Lihat Data CRM</button>
</div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal-bg" id="modalCRM">
<div class="modal-box">
<h3>📦 Data CRM</h3>

<table>
<thead>
<tr>
    <th>Tanggal</th>
    <th>Nama</th>
    <th>Outlet</th>
    <th>Qty</th>
    <th>Jenis</th>
    <th>Kota</th>
    <th>Alamat</th>
    <th>Lokasi</th>
    <th>Marketing</th>
    <th>No HP</th>
    <th>Jalur</th>
    <th>Foto</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q=mysqli_query($conn,"SELECT * FROM crm ORDER BY id_crm DESC");
while($r=mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= date('d-m-Y',strtotime($r['tanggal_input'])) ?></td>
<td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
<td><?= htmlspecialchars($r['nama_outlet']) ?></td>
<td><?= $r['qty'] ?></td>
<td><?= htmlspecialchars($r['jenis_es'] ?? '-') ?></td>
<td><?= $r['kota_kabupaten'] ?></td>
<td>
    <?= !empty($r['alamat_lengkap'])
        ? nl2br(htmlspecialchars($r['alamat_lengkap']))
        : '-' ?>
</td>
<td>
<?php if(!empty($r['lokasi'])):
    list($lat,$lng) = explode(',', $r['lokasi']);
?>
<a href="https://www.google.com/maps?q=<?= urlencode($r['lokasi']) ?>"
   target="_blank"
   class="map-preview-link">

<iframe
    src="https://www.openstreetmap.org/export/embed.html?bbox=<?= $lng-0.002 ?>,<?= $lat-0.002 ?>,<?= $lng+0.002 ?>,<?= $lat+0.002 ?>&layer=mapnik&marker=<?= $lat ?>,<?= $lng ?>"
    loading="lazy">
</iframe>

</a>
<?php else: ?>
-
<?php endif; ?>
</td>
<td><?= $r['marketing'] ?></td>
<td><?= $r['no_hp'] ?></td>
<td><?= $r['jalur'] ?></td>
<td><?= $r['foto'] ? "<img src='../assets/foto_crm/$r[foto]'>" : "-" ?></td>
<td>
    <div class="aksi-btn">
        <button class="edit-btn"
        onclick='openEditModal(<?= json_encode($r) ?>)'>Edit</button>

        <a href="?delete=<?= $r['id_crm'] ?>" class="delete-btn"
        onclick="return confirm('Hapus data?')">Hapus</a>
    </div>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<button onclick="closeModal()" class="btn-save">Tutup</button>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal-bg" id="modalEditCRM">
<div class="modal-box">
<h3>✏️ Edit Data CRM</h3>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="id_crm" id="edit_id">

<input name="nama_lengkap" id="edit_nama" required>
<input name="nama_outlet" id="edit_outlet">

<input type="number" name="qty" id="edit_qty" required>

<select name="jenis_es" id="edit_jenis" required>
    <option value="Kristal">Kristal</option>
    <option value="Serut">Serut</option>
</select>

<select name="kota_kabupaten" id="edit_kota" required>
    <option value="Kota Magelang">Kota Magelang</option>
    <option value="Kabupaten Magelang">Kabupaten Magelang</option>
</select>

<textarea name="alamat_lengkap" id="edit_alamat"></textarea>

<input type="hidden" name="lokasi" id="edit_lokasi">

<div id="mapEdit"
     style="height:260px;
            margin-bottom:14px;
            border-radius:14px;
            overflow:hidden;">
</div>

<input name="marketing" id="edit_marketing">
<input name="no_hp" id="edit_nohp">
<input name="jalur" id="edit_jalur">

<textarea name="keterangan_crm" id="edit_ket"></textarea>

<button class="btn-save" name="update">💾 Simpan Perubahan</button>
<button type="button" class="detail-btn" onclick="closeEditModal()">Batal</button>
</form>

</div>
</div>

<script>
document.getElementById("kota").addEventListener("change",function(){
    alamat_lengkap.style.display = this.value ? "block" : "none";
});

function openModal(){ modalCRM.style.display='flex'; }
function closeModal(){ modalCRM.style.display='none'; }

let map,marker;
document.addEventListener("DOMContentLoaded",function(){
    const lat=-7.4706,lng=110.2177;
    map=L.map("map").setView([lat,lng],13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
    marker=L.marker([lat,lng],{draggable:true}).addTo(map);
    setLokasi(lat,lng);
    map.on("click",e=>{marker.setLatLng(e.latlng);setLokasi(e.latlng.lat,e.latlng.lng);});
    marker.on("dragend",e=>{let p=e.target.getLatLng();setLokasi(p.lat,p.lng);});
});
function setLokasi(a,b){
    lokasi.value=a.toFixed(6)+","+b.toFixed(6);
}

function openEditModal(data){
    modalEditCRM.style.display = 'flex';

    edit_id.value       = data.id_crm;
    edit_nama.value     = data.nama_lengkap ?? '';
    edit_outlet.value   = data.nama_outlet ?? '';
    edit_qty.value      = data.qty ?? 0;
    edit_jenis.value    = data.jenis_es ?? '';
    edit_kota.value     = data.kota_kabupaten ?? '';
    edit_alamat.value   = data.alamat_lengkap ?? '';
    edit_lokasi.value   = data.lokasi ?? '';
    edit_marketing.value= data.marketing ?? '';
    edit_nohp.value     = data.no_hp ?? '';
    edit_jalur.value    = data.jalur ?? '';
    edit_ket.value      = data.keterangan_crm ?? '';
}

function closeEditModal(){
    modalEditCRM.style.display = 'none';
}
</script>

<script>
let mapEdit, markerEdit;

/* BUKA MODAL EDIT */
function openEditModal(data){
    modalEditCRM.style.display = 'flex';

    edit_id.value       = data.id_crm;
    edit_nama.value     = data.nama_lengkap ?? '';
    edit_outlet.value   = data.nama_outlet ?? '';
    edit_qty.value      = data.qty ?? 0;
    edit_jenis.value    = data.jenis_es ?? '';
    edit_kota.value     = data.kota_kabupaten ?? '';
    edit_alamat.value   = data.alamat_lengkap ?? '';
    edit_marketing.value= data.marketing ?? '';
    edit_nohp.value     = data.no_hp ?? '';
    edit_jalur.value    = data.jalur ?? '';
    edit_ket.value      = data.keterangan_crm ?? '';

    // === LOKASI ===
    let lat = -7.4706;
    let lng = 110.2177;

    if(data.lokasi){
        let split = data.lokasi.split(",");
        lat = parseFloat(split[0]);
        lng = parseFloat(split[1]);
    }

    edit_lokasi.value = lat.toFixed(6)+","+lng.toFixed(6);

    setTimeout(() => {
        initEditMap(lat, lng);
    }, 200);
}

/* INIT MAP EDIT */
function initEditMap(lat, lng){
    if(mapEdit){
        mapEdit.remove();
    }

    mapEdit = L.map('mapEdit').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mapEdit);

    markerEdit = L.marker([lat, lng], { draggable:true }).addTo(mapEdit);

    // Klik map
    mapEdit.on('click', function(e){
        markerEdit.setLatLng(e.latlng);
        updateEditLokasi(e.latlng.lat, e.latlng.lng);
    });

    // Geser marker
    markerEdit.on('dragend', function(e){
        let pos = e.target.getLatLng();
        updateEditLokasi(pos.lat, pos.lng);
    });
}

/* UPDATE INPUT LOKASI */
function updateEditLokasi(lat, lng){
    edit_lokasi.value = lat.toFixed(6)+","+lng.toFixed(6);
}

/* TUTUP MODAL */
function closeEditModal(){
    modalEditCRM.style.display = 'none';
    if(mapEdit){
        mapEdit.remove();
        mapEdit = null;
    }
}
</script>

<?php include "partials/footer.php"; ?>
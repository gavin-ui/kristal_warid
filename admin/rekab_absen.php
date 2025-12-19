<?php
include "../koneksi.php";
$dateToday = date('Y-m-d');

/* ===============================
   STATISTIK
================================ */
$hadir = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM absensi 
     WHERE DATE(waktu_masuk)='$dateToday'
     AND status_kehadiran='HADIR'"
))[0];

$izin = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM absensi 
     WHERE DATE(waktu_masuk)='$dateToday'
     AND status_kehadiran='IZIN'"
))[0];

$total = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(DISTINCT nomor_karyawan) FROM absensi"
))[0];

$belum = max($total - ($hadir + $izin), 0);

/* ===============================
   DATA HARI INI
================================ */
$data = mysqli_query($conn,
    "SELECT * FROM absensi
     WHERE DATE(waktu_masuk)='$dateToday'
     ORDER BY waktu_masuk DESC"
);
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
.dashboard{margin-left:290px;padding:30px}
body.collapsed .dashboard{margin-left:110px}

/* CARD GLASS */
.glass-card{
    background:var(--card-bg);
    border-radius:26px;
    padding:34px;
    box-shadow:
        0 18px 40px rgba(0,0,0,.12),
        inset 0 0 0 1px rgba(255,255,255,.4);
}

/* HEADER */
.page-title{
    font-size:28px;
    font-weight:900;
    background:linear-gradient(90deg,#2563eb,#0ea5e9);
    -webkit-background-clip:text;
    color:transparent;
}

/* STAT */
.stat-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:24px;
    margin:40px 0;
}
.stat-item{
    padding:30px;
    border-radius:24px;
    background:var(--card-bg);
    border-left:6px solid var(--accent);
    box-shadow:0 10px 28px rgba(0,0,0,.08);
}
.stat-item h4{font-weight:700;color:var(--title-color)}
.stat-item span{
    font-size:38px;
    font-weight:900;
    color:var(--accent);
}

/* SEARCH */
.search-box{
    max-width:320px;
    margin-bottom:20px;
}
.search-box input{
    border-radius:14px;
    padding:10px 14px;
}

/* TABLE */
.table{
    border-radius:18px;
    overflow:hidden;
}
.table thead{
    background:linear-gradient(90deg,#0ea5e9,#2563eb);
    color:white;
}
.table tbody tr:hover{
    background:rgba(59,130,246,.08);
}

/* BADGE */
.badge-hadir{background:#22c55e}
.badge-izin{background:#facc15;color:#000}
.badge-sakit{background:#ef4444}

/* BUTTON */
.btn-detail{
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:white;
    border:none;
    border-radius:12px;
    padding:6px 14px;
}

/* MODAL */
.modal-content{
    border-radius:24px;
    background:var(--card-bg);
}
</style>

<div class="dashboard">
<div class="content-wrapper">

<!-- HEADER -->
<div class="glass-card text-center mb-4">
    <h2 class="page-title">📅 Rekap Absensi Hari Ini</h2>
    <p class="opacity-75"><?= date("d F Y") ?></p>
</div>

<!-- STAT -->
<div class="stat-grid">
    <div class="stat-item">
        <h4>✅ Hadir</h4>
        <span><?= $hadir ?></span>
    </div>
    <div class="stat-item">
        <h4>📝 Izin</h4>
        <span><?= $izin ?></span>
    </div>
    <div class="stat-item">
        <h4>❌ Belum Absen</h4>
        <span><?= $belum ?></span>
    </div>
</div>

<!-- ACTION -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="search-box">
        <input type="text" id="searchInput" class="form-control"
        placeholder="Cari nama, divisi, status...">
    </div>

    <button class="btn btn-success fw-bold"
        data-bs-toggle="modal"
        data-bs-target="#modalExport">
        📥 Export Excel
    </button>
</div>

<!-- TABLE -->
<div class="glass-card">
<div class="table-responsive">
<table class="table table-hover align-middle text-center" id="absenTable">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Divisi</th>
<th>Status</th>
<th>Selfie Masuk</th>
<th>Selfie Pulang</th>
<th>Jam Masuk</th>
<th>Jam Pulang</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php $no=1; while($r=mysqli_fetch_assoc($data)): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= $r['nama_karyawan'] ?></td>
<td><?= $r['divisi'] ?></td>
<td>
<?php
$badge = $r['status_kehadiran']=="HADIR"?"badge-hadir":
        ($r['status_kehadiran']=="IZIN"?"badge-izin":"badge-sakit");
?>
<span class="badge <?= $badge ?>">
<?= $r['status_kehadiran'] ?>
</span>
</td>
<td>
<?php if($r['selfie_masuk']): ?>
<img src="../uploads/<?= $r['selfie_masuk'] ?>" width="45" class="rounded">
<?php else: ?>-<?php endif; ?>
</td>
<td>
<?php if($r['selfie_pulang']): ?>
<img src="../uploads/<?= $r['selfie_pulang'] ?>" width="45" class="rounded">
<?php else: ?>-<?php endif; ?>
</td>
<td><?= $r['waktu_masuk']?date("H:i",strtotime($r['waktu_masuk'])):"-" ?></td>
<td><?= $r['waktu_pulang']?date("H:i",strtotime($r['waktu_pulang'])):"-" ?></td>
<td>
<button class="btn-detail"
onclick='showDetail(<?= json_encode($r) ?>)'>
👁 Detail
</button>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>
</div>

</div>
</div>

<!-- MODAL EXPORT -->
<div class="modal fade" id="modalExport">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form method="GET" action="rekap_absen_export.php">
<div class="modal-header bg-success text-white">
<h5 class="modal-title">📥 Export Absensi</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<label class="form-label fw-bold">Pilih Data</label>
<select name="filter" class="form-select">
<option value="ALL">Semua</option>
<option value="HADIR">Hadir</option>
<option value="IZIN">Tidak Hadir</option>
</select>
</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button class="btn btn-success fw-bold">Export</button>
</div>
</form>
</div>
</div>
</div>

<script>
/* SEARCH */
document.getElementById("searchInput").addEventListener("keyup", function(){
let v=this.value.toLowerCase();
document.querySelectorAll("#absenTable tbody tr").forEach(tr=>{
tr.style.display = tr.innerText.toLowerCase().includes(v) ? "" : "none";
});
});

/* DETAIL */
function showDetail(d){
d_nama.innerText=d.nama_karyawan;
d_status.innerText=d.status_kehadiran;
d_masuk.innerText=d.waktu_masuk??"-";
d_pulang.innerText=d.waktu_pulang??"-";
d_alasan.innerText=d.alasan??"-";
new bootstrap.Modal(modalDetail).show();
}
</script>

<?php include "partials/footer.php"; ?>

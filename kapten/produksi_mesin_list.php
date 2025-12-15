<?php
session_start();
include "../koneksi.php";
$pageTitle = "Data Produksi Mesin";
include "partials/header.php";
include "partials/navbar.php";

// FILTER
$tanggal = $_GET['tanggal'] ?? '';
$mesin   = $_GET['mesin'] ?? '';

$where = [];
if ($tanggal) $where[] = "DATE(tanggal_input) = '$tanggal'";
if ($mesin)   $where[] = "mesin = '$mesin'";

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

$data = mysqli_query($conn, "SELECT * FROM produksi_mesin $whereSQL ORDER BY id_produksi DESC");
?>

<div class="container" style="margin-top:130px">

<div class="card shadow-lg border-0 rounded-4">
<div class="card-body p-4">

<h4 class="fw-bold text-primary mb-4">📊 Data Produksi Mesin</h4>

<!-- FILTER -->
<form class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= $tanggal ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">Mesin</label>
        <select name="mesin" class="form-select">
            <option value="">Semua Mesin</option>
            <option value="A" <?= $mesin=="A"?"selected":"" ?>>Mesin A</option>
            <option value="B" <?= $mesin=="B"?"selected":"" ?>>Mesin B</option>
        </select>
    </div>

    <div class="col-md-4 d-flex align-items-end gap-2">
        <button class="btn btn-primary w-100">🔍 Cari</button>
        <a href="produksi_mesin_list.php" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<!-- EXPORT -->
<div class="mb-3 text-end">
    <button class="btn btn-success fw-bold"
        data-bs-toggle="modal"
        data-bs-target="#modalExport">
        📥 Export Excel
    </button>
</div>

<!-- TABLE -->
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead class="table-primary text-center">
<tr>
<th>ID</th>
<th>Mesin</th>
<th>Jam</th>
<th>Qty</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php while($r=mysqli_fetch_assoc($data)): ?>
<tr class="text-center">
<td><?= $r['id_produksi'] ?></td>
<td><span class="badge bg-warning"><?= $r['mesin'] ?></span></td>
<td><?= $r['jam_mulai'] ?></td>
<td><?= $r['qty'] ?></td>
<td><?= date("d-m-Y", strtotime($r['tanggal_input'])) ?></td>
<td class="d-flex justify-content-center gap-1">

<button class="btn btn-sm btn-info text-white"
onclick='detailData(<?= json_encode($r) ?>)'>👁</button>

<button class="btn btn-sm btn-warning"
onclick='editData(<?= json_encode($r) ?>)'>✏</button>

<a href="produksi_mesin_delete.php?id=<?= $r['id_produksi'] ?>"
onclick="return confirm('Yakin hapus data ini?')"
class="btn btn-sm btn-danger">🗑</a>

</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</div>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="modalEdit" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content rounded-4">

<form method="POST" action="produksi_mesin_update.php">

<div class="modal-header bg-warning">
<h5 class="modal-title fw-bold">✏ Edit Produksi Mesin</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<input type="hidden" name="id" id="edit_id">

<div class="row g-3">
<div class="col-md-6">
<label class="form-label">Mesin</label>
<select name="mesin" id="edit_mesin" class="form-select">
<option value="A">Mesin A</option>
<option value="B">Mesin B</option>
</select>
</div>

<div class="col-md-6">
<label class="form-label">Jam Mulai</label>
<input type="time" name="jam_mulai" id="edit_jam" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Menit</label>
<input type="number" name="menit" id="edit_menit" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Defroz</label>
<input type="number" name="defroz" id="edit_defroz" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Pack</label>
<input type="number" name="pack" id="edit_pack" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Qty</label>
<input type="number" name="qty" id="edit_qty" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Kristal</label>
<input type="number" name="kristal" id="edit_kristal" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Serut</label>
<input type="number" name="serut" id="edit_serut" class="form-control">
</div>

<div class="col-12">
<label class="form-label">Keterangan</label>
<textarea name="keterangan" id="edit_ket" class="form-control"></textarea>
</div>
</div>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button class="btn btn-warning fw-bold">💾 Simpan</button>
</div>

</form>
</div>
</div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="modalDetail" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content rounded-4">

<div class="modal-header bg-info text-white">
<h5 class="modal-title">📋 Detail Produksi Mesin</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<table class="table table-bordered">
<tr><th>Mesin</th><td id="d_mesin"></td></tr>
<tr><th>Jam Mulai</th><td id="d_jam"></td></tr>
<tr><th>Menit</th><td id="d_menit"></td></tr>
<tr><th>Defroz</th><td id="d_defroz"></td></tr>
<tr><th>Pack</th><td id="d_pack"></td></tr>
<tr><th>Qty</th><td id="d_qty"></td></tr>
<tr><th>Kristal</th><td id="d_kristal"></td></tr>
<tr><th>Serut</th><td id="d_serut"></td></tr>
<tr><th>Keterangan</th><td id="d_ket"></td></tr>
<tr><th>Tanggal</th><td id="d_tanggal"></td></tr>
</table>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>

</div>
</div>
</div>

<!-- ================= MODAL EXPORT ================= -->
<div class="modal fade" id="modalExport" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content rounded-4">

<form method="GET" action="produksi_mesin_export.php">

<div class="modal-header bg-success text-white">
<h5 class="modal-title">📥 Export Data Produksi</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<label class="form-label fw-bold">Pilih Mesin</label>
<select name="mesin" class="form-select">
<option value="">Semua Mesin</option>
<option value="A">Mesin A</option>
<option value="B">Mesin B</option>
</select>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button class="btn btn-success fw-bold">⬇ Export</button>
</div>

</form>
</div>
</div>
</div>

<script>
function editData(data){
    edit_id.value = data.id_produksi;
    edit_mesin.value = data.mesin;
    edit_jam.value = data.jam_mulai;
    edit_menit.value = data.menit;
    edit_defroz.value = data.defroz;
    edit_pack.value = data.pack;
    edit_qty.value = data.qty;
    edit_kristal.value = data.kristal;
    edit_serut.value = data.serut;
    edit_ket.value = data.keterangan;

    new bootstrap.Modal(modalEdit).show();
}

function detailData(data){
    d_mesin.innerText = data.mesin;
    d_jam.innerText = data.jam_mulai;
    d_menit.innerText = data.menit;
    d_defroz.innerText = data.defroz;
    d_pack.innerText = data.pack;
    d_qty.innerText = data.qty;
    d_kristal.innerText = data.kristal;
    d_serut.innerText = data.serut;
    d_ket.innerText = data.keterangan;
    d_tanggal.innerText = data.tanggal_input;

    new bootstrap.Modal(modalDetail).show();
}
</script>

<?php include "partials/footer.php"; ?>

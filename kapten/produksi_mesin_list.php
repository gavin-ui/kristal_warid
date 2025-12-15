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
            <option value="">Semua</option>
            <option value="A" <?= $mesin=="A"?"selected":"" ?>>Mesin A</option>
            <option value="B" <?= $mesin=="B"?"selected":"" ?>>Mesin B</option>
        </select>
    </div>

    <div class="col-md-4 d-flex align-items-end gap-2">
        <button class="btn btn-primary w-100">🔍 Cari</button>
        <a href="produksi_mesin_list.php" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

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
<td>
<button class="btn btn-sm btn-warning"
onclick='editData(<?= json_encode($r) ?>)'>✏ Edit</button>

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

<!-- ===== MODAL EDIT (LANDSCAPE) ===== -->
<div class="modal fade" id="modalEdit" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content rounded-4">

<form method="POST" action="produksi_mesin_update.php">

<div class="modal-header bg-primary text-white">
<h5 class="modal-title">✏ Edit Produksi Mesin</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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

<script>
function editData(data){
    document.getElementById("edit_id").value = data.id_produksi;
    document.getElementById("edit_mesin").value = data.mesin;
    document.getElementById("edit_jam").value = data.jam_mulai;
    document.getElementById("edit_menit").value = data.menit;
    document.getElementById("edit_defroz").value = data.defroz;
    document.getElementById("edit_pack").value = data.pack;
    document.getElementById("edit_qty").value = data.qty;
    document.getElementById("edit_kristal").value = data.kristal;
    document.getElementById("edit_serut").value = data.serut;
    document.getElementById("edit_ket").value = data.keterangan;

    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>

<?php include "partials/footer.php"; ?>

<?php
include "../koneksi.php";

/* ================= INSERT ================= */
if (isset($_POST["submit"])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nama_outlet  = mysqli_real_escape_string($conn, $_POST['nama_outlet']);
    $alamat       = mysqli_real_escape_string($conn, $_POST['alamat']);
    $lokasi       = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $marketing    = mysqli_real_escape_string($conn, $_POST['marketing']);
    $no_hp        = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $jalur        = mysqli_real_escape_string($conn, $_POST['jalur']);
    $ket          = mysqli_real_escape_string($conn, $_POST['keterangan_crm']);

    $foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = "crm_" . time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/foto_crm/".$foto);
    }

    mysqli_query($conn,"INSERT INTO crm
    (nama_lengkap,nama_outlet,alamat,lokasi,marketing,no_hp,jalur,keterangan_crm,foto)
    VALUES
    ('$nama_lengkap','$nama_outlet','$alamat','$lokasi','$marketing','$no_hp','$jalur','$ket','$foto')");

    header("Location: crm.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST["update"])) {
    $id = intval($_POST['id_crm']);

    mysqli_query($conn,"UPDATE crm SET
        nama_lengkap='$_POST[nama_lengkap]',
        nama_outlet='$_POST[nama_outlet]',
        lokasi='$_POST[lokasi]',
        marketing='$_POST[marketing]',
        no_hp='$_POST[no_hp]',
        jalur='$_POST[jalur]',
        alamat='$_POST[alamat]',
        keterangan_crm='$_POST[keterangan_crm]'
        WHERE id_crm=$id
    ");

    header("Location: crm.php");
    exit;
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM crm WHERE id_crm=$id");
    header("Location: crm.php");
    exit;
}

include "partials/header.php";
include "partials/sidebar.php";
?>

<style>
.page-container{margin-left:290px;padding:35px;background:var(--body-bg);min-height:100vh}
body.collapsed .page-container{margin-left:110px}

.form-card{
    background:var(--card-bg);
    padding:25px;
    border-radius:15px;
    max-width:900px;
    margin:auto;
    box-shadow:0 8px 15px rgba(0,0,0,.1)
}

input,textarea{
    width:97%;
    padding:10px;
    border-radius:10px;
    border:2px solid var(--title-color);
    margin-bottom:10px;
    background:#fff;
}
body.dark input,body.dark textarea{background:#0f1729;color:#fff}

.btn-save,.detail-btn,.edit-btn,.delete-btn{
    padding:10px;
    border-radius:8px;
    border:none;
    cursor:pointer;
    font-weight:bold
}
.btn-save{background:var(--hover-bg);width:100%}
.detail-btn{background:#0075ff;color:#fff;margin-top:10px;width:100%}
.edit-btn{background:#ffc107}
.delete-btn{background:#dc3545;color:#fff}

/* ===== MODAL ===== */
.modal-bg{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:99999;
}
.modal-box{
    background:var(--card-bg);
    width:95%;
    max-width:1100px;
    max-height:85vh;
    overflow:auto;
    padding:20px;
    border-radius:16px;
}

table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{border:1px solid #ccc;padding:8px;text-align:center}
th{background:var(--hover-bg)}
img{width:70px;border-radius:8px}
</style>

<div class="page-container">
<div class="form-card">
<h2 style="text-align:center">📇 Input CRM</h2>

<!-- FORM ASLI TIDAK DIUBAH -->
<form method="POST" enctype="multipart/form-data">
    <input name="nama_lengkap" placeholder="Nama Lengkap" required>
    <input name="nama_outlet" placeholder="Nama Outlet">
    <input name="lokasi" placeholder="Lokasi">
    <input name="marketing" placeholder="Marketing">
    <input name="no_hp" placeholder="No HP">
    <input name="jalur" placeholder="Jalur">
    <textarea name="alamat" placeholder="Alamat"></textarea>
    <textarea name="keterangan_crm" placeholder="Keterangan"></textarea>
    <input type="file" name="foto">
    <button class="btn-save" name="submit">💾 Simpan</button>
</form>

<button class="detail-btn" onclick="openModal()">📋 Lihat Data CRM</button>
</div>
</div>

<!-- ========== MODAL DETAIL ========== -->
<div class="modal-bg" id="modalCRM">
<div class="modal-box">
<h3 style="text-align:center">📦 Data CRM</h3>

<table>
<thead>
<tr>
    <th>Nama</th>
    <th>Outlet</th>
    <th>Lokasi</th>
    <th>Marketing</th>
    <th>No HP</th>
    <th>Jalur</th>
    <th>Alamat</th>
    <th>Keterangan</th>
    <th>Foto</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q = mysqli_query($conn,"SELECT * FROM crm ORDER BY id_crm DESC");
while($r = mysqli_fetch_assoc($q)):
?>
<tr>
    <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
    <td><?= htmlspecialchars($r['nama_outlet']) ?></td>
    <td><?= htmlspecialchars($r['lokasi']) ?></td>
    <td><?= htmlspecialchars($r['marketing']) ?></td>
    <td><?= htmlspecialchars($r['no_hp']) ?></td>
    <td><?= htmlspecialchars($r['jalur']) ?></td>
    <td style="text-align:left"><?= nl2br(htmlspecialchars($r['alamat'])) ?></td>
    <td style="text-align:left"><?= nl2br(htmlspecialchars($r['keterangan_crm'])) ?></td>
    <td>
        <?php if(!empty($r['foto'])): ?>
            <img src="../assets/foto_crm/<?= $r['foto'] ?>">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td>
        <button class="edit-btn"
        onclick="openEdit(
            '<?= $r['id_crm'] ?>',
            '<?= htmlspecialchars($r['nama_lengkap']) ?>',
            '<?= htmlspecialchars($r['nama_outlet']) ?>',
            '<?= htmlspecialchars($r['lokasi']) ?>',
            '<?= htmlspecialchars($r['marketing']) ?>',
            '<?= htmlspecialchars($r['no_hp']) ?>',
            '<?= htmlspecialchars($r['jalur']) ?>',
            `<?= htmlspecialchars($r['alamat']) ?>`,
            `<?= htmlspecialchars($r['keterangan_crm']) ?>`
        )">Edit</button>

        <a class="delete-btn"
           href="?delete=<?= $r['id_crm'] ?>"
           onclick="return confirm('Hapus data ini?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<button onclick="closeModal()" class="btn-save" style="margin-top:15px">Tutup</button>
</div>
</div>

<!-- ========== MODAL EDIT ========== -->
<div class="modal-bg" id="modalEdit">
<div class="modal-box">
<h3 style="text-align:center">✏ Edit CRM</h3>

<form method="POST">
<input type="hidden" name="id_crm" id="e_id">

<input name="nama_lengkap" id="e_nama">
<input name="nama_outlet" id="e_outlet">
<input name="lokasi" id="e_lokasi">
<input name="marketing" id="e_marketing">
<input name="no_hp" id="e_hp">
<input name="jalur" id="e_jalur">
<textarea name="alamat" id="e_alamat"></textarea>
<textarea name="keterangan_crm" id="e_ket"></textarea>

<button class="btn-save" name="update">💾 Update</button>
</form>

<button onclick="closeEdit()" class="detail-btn">Tutup</button>
</div>
</div>

<script>
function openModal(){
    modalCRM.style.display='flex'
}
function closeModal(){
    modalCRM.style.display='none'
}
function openEdit(id,nama,outlet,lokasi,marketing,hp,jalur,alamat,ket){
    e_id.value=id
    e_nama.value=nama
    e_outlet.value=outlet
    e_lokasi.value=lokasi
    e_marketing.value=marketing
    e_hp.value=hp
    e_jalur.value=jalur
    e_alamat.value=alamat
    e_ket.value=ket
    modalEdit.style.display='flex'
}
function closeEdit(){
    modalEdit.style.display='none'
}
</script>

<?php include "partials/footer.php"; ?>

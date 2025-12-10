<?php
include "../koneksi.php";

// INSERT DATA CRM (dari form)
if (isset($_POST["submit"])) {
    $nama_lengkap   = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nama_outlet    = mysqli_real_escape_string($conn, $_POST['nama_outlet']);
    $alamat         = mysqli_real_escape_string($conn, $_POST['alamat']);
    $lokasi         = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $marketing      = mysqli_real_escape_string($conn, $_POST['marketing']);
    $no_hp          = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $jalur          = mysqli_real_escape_string($conn, $_POST['jalur']);
    $ket_crm        = mysqli_real_escape_string($conn, $_POST['keterangan_crm']);

    // Upload Foto (opsional)
    $fotoName = "";
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoName = "crm_" . time() . "." . $ext;
        // target folder (relative to admin/)
        $target = "../assets/foto_crm/" . $fotoName;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target);
    }

    mysqli_query($conn, "
        INSERT INTO crm (nama_lengkap, nama_outlet, alamat, lokasi, marketing, no_hp, jalur, keterangan_crm, foto)
        VALUES ('$nama_lengkap','$nama_outlet','$alamat','$lokasi','$marketing','$no_hp','$jalur','$ket_crm','$fotoName')
    ");

    header("Location: crm.php?success=1");
    exit;
}

// DELETE CRM (juga menghapus file foto jika ada)
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $q = mysqli_query($conn, "SELECT foto FROM crm WHERE id_crm='$id'");
    if ($q && mysqli_num_rows($q) > 0) {
        $d = mysqli_fetch_assoc($q);
        if (!empty($d['foto']) && file_exists("../assets/foto_crm/" . $d['foto'])) {
            @unlink("../assets/foto_crm/" . $d['foto']);
        }
    }
    mysqli_query($conn, "DELETE FROM crm WHERE id_crm='$id'");
    header("Location: crm.php?success=delete");
    exit;
}

include "partials/sidebar.php";
?>

<style>
/* layout & style consistent with prior pages */
.page-container{ margin-left:290px;padding:35px;background:var(--body-bg);min-height:100vh;transition:.3s;}
body.collapsed .page-container{ margin-left:110px; }

.form-card{ background:var(--card-bg); padding:22px; border-radius:14px; max-width:920px; margin:auto; box-shadow:0 8px 18px rgba(0,0,0,.08); }
.form-card h2{ margin:0 0 12px 0; color:var(--title-color); text-align:center; }

input, select, textarea{ width:100%; padding:10px; border-radius:10px; border:2px solid var(--title-color); margin-bottom:10px; background:#fff; color:#000; }
body.dark input, body.dark textarea, body.dark select { background:#0f1729 !important; color:#fff !important; border-color:var(--hover-bg); }

.btn-save, .detail-btn { padding:12px; width:100%; border-radius:10px; font-weight:700; border:none; cursor:pointer; transition:.25s; }
.btn-save { background:var(--hover-bg); color:#000; }
.detail-btn{ background:#0075ff;color:#fff;margin-top:10px; }

.table-wrapper{ margin-top:22px; background:var(--card-bg); padding:18px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,.06); max-width:1100px; margin:auto; }
table{ width:100%; border-collapse:collapse; margin-top:12px; font-size:14px; }
th, td{ padding:10px; border:1px solid #ddd; text-align:center; vertical-align:middle; }
th{ background:var(--hover-bg); font-weight:700; }
img.crm-thumb{ width:72px; height:72px; object-fit:cover; border-radius:6px; border:2px solid rgba(0,0,0,0.06); }

/* modal (landscape + scroll) */
.modal-bg{ position:fixed; inset:0; background:rgba(0,0,0,.55); display:none; justify-content:center; align-items:flex-start; padding:40px 20px; z-index:99999; }
.modal-box{ background:var(--card-bg); width:95%; max-width:1000px; border-radius:12px; padding:20px; max-height:85vh; overflow:auto; box-shadow:0 12px 30px rgba(0,0,0,.28); }
.modal-grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px; align-items:start; }
.modal-grid label{ font-weight:600; font-size:13px; display:block; margin-bottom:6px; color:var(--title-color); }
.modal-actions{ margin-top:14px; display:flex; gap:12px; }
.small-muted{ font-size:13px; color:rgba(0,0,0,.6); }
@media(max-width:760px){ .modal-grid{ grid-template-columns:1fr; } }
</style>

<div class="page-container">
  <?php if(isset($_GET["success"])): ?>
  <?php endif; ?>

  <div class="form-card">
    <h2>📇 Input CRM Customer</h2>

    <form method="POST" enctype="multipart/form-data">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
        <input type="text" name="nama_outlet" placeholder="Nama Outlet">
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <input type="text" name="lokasi" placeholder="Lokasi">
        <input type="text" name="marketing" placeholder="Marketing">
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <input type="text" name="no_hp" placeholder="Nomor HP">
        <input type="text" name="jalur" placeholder="Jalur">
      </div>

      <textarea name="alamat" placeholder="Alamat lengkap"></textarea>
      <textarea name="keterangan_crm" placeholder="Keterangan (opsional)"></textarea>

      <label class="small-muted">Foto Outlet / Lokasi (opsional)</label>
      <input type="file" name="foto" accept="image/*">

      <button class="btn-save" name="submit">💾 Simpan Data CRM</button>
    </form>
  </div>

  <!-- TABLE -->
  <div id="tableSection" class="table-wrapper">
    <h3 style="text-align:center;color:var(--title-color);margin:0 0 12px 0;">📦 Data CRM</h3>

    <table>
      <thead>
        <tr>
          <th>ID</th><th>Nama</th><th>Outlet</th><th>No HP</th><th>Marketing</th><th>Foto</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM crm ORDER BY id_crm DESC");
        while ($r = mysqli_fetch_assoc($res)) {
            // safe defaults
            $foto = !empty($r['foto']) ? $r['foto'] : '';
            $imgPath = $foto ? "../assets/foto_crm/{$foto}" : "";
            // encode row as JSON for data attribute (safe)
            $rowJson = htmlspecialchars(json_encode($r), ENT_QUOTES);
        ?>
        <tr>
          <td><?= $r['id_crm'] ?></td>
          <td style="text-align:left;padding-left:12px;"><?= htmlspecialchars($r['nama_lengkap']) ?></td>
          <td style="text-align:left;padding-left:12px;"><?= htmlspecialchars($r['nama_outlet']) ?></td>
          <td><?= htmlspecialchars($r['no_hp']) ?></td>
          <td><?= htmlspecialchars($r['marketing']) ?></td>
          <td>
            <?php if ($imgPath && file_exists($imgPath)): ?>
              <img class="crm-thumb" src="<?= $imgPath ?>" alt="foto-<?= $r['id_crm'] ?>">
            <?php else: ?>
              <div style="width:72px;height:72px;border-radius:6px;border:1px dashed #ddd;display:inline-flex;align-items:center;justify-content:center;color:#888;">-</div>
            <?php endif; ?>
          </td>
          <td>
            <button class="edit-btn" data-row='<?= $rowJson ?>'>✏ Edit</button>
            &nbsp;
            <a href="?delete=<?= $r['id_crm'] ?>" class="delete-btn" onclick="return confirm('Hapus data ini?')">🗑 Hapus</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal-bg" id="modalEdit">
  <div class="modal-box">
    <h3 style="text-align:center;color:var(--title-color)">✏ Edit Data CRM</h3>

    <form method="POST" action="update_crm.php" enctype="multipart/form-data">
      <input type="hidden" id="edit_id" name="id_crm">

      <div class="modal-grid">
        <div>
          <label>Nama Lengkap</label>
          <input id="edit_nama" name="nama_lengkap" type="text" required>

          <label>Nama Outlet</label>
          <input id="edit_outlet" name="nama_outlet" type="text">

          <label>Alamat</label>
          <textarea id="edit_alamat" name="alamat"></textarea>

          <label>Lokasi</label>
          <input id="edit_lokasi" name="lokasi" type="text">
        </div>

        <div>
          <label>Marketing</label>
          <input id="edit_marketing" name="marketing" type="text">

          <label>No HP</label>
          <input id="edit_hp" name="no_hp" type="text">

          <label>Jalur</label>
          <input id="edit_jalur" name="jalur" type="text">

          <label>Keterangan CRM</label>
          <textarea id="edit_ket" name="keterangan_crm"></textarea>
        </div>

        <div style="grid-column:span 2;">
          <label>Foto Saat Ini</label>
          <div id="edit_foto_wrap" style="margin-bottom:10px;"></div>

          <label>Ganti Foto (opsional)</label>
          <input type="file" name="foto" accept="image/*">
        </div>
      </div>

      <div class="modal-actions">
        <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
        <button type="button" onclick="closeEdit()" class="detail-btn" style="background:#aaa;">Tutup</button>
      </div>
    </form>
  </div>
</div>

<script>
// attach edit handler to all edit buttons (delegation)
document.addEventListener('click', function(e){
  if(e.target && e.target.classList.contains('edit-btn')){
    const rowJson = e.target.getAttribute('data-row');
    if(!rowJson) return;
    let row;
    try { row = JSON.parse(rowJson); } catch (err) { console.error(err); return; }

    // fill modal fields
    document.getElementById('edit_id').value = row.id_crm || '';
    document.getElementById('edit_nama').value = row.nama_lengkap || '';
    document.getElementById('edit_outlet').value = row.nama_outlet || '';
    document.getElementById('edit_alamat').value = row.alamat || '';
    document.getElementById('edit_lokasi').value = row.lokasi || '';
    document.getElementById('edit_marketing').value = row.marketing || '';
    document.getElementById('edit_hp').value = row.no_hp || '';
    document.getElementById('edit_jalur').value = row.jalur || '';
    document.getElementById('edit_ket').value = row.keterangan_crm || '';

    // foto preview: build img or placeholder
    const wrap = document.getElementById('edit_foto_wrap');
    wrap.innerHTML = '';
    if(row.foto){
       const img = document.createElement('img');
       img.src = "../assets/foto_crm/" + row.foto;
       img.className = "crm-thumb";
       img.style.width = "120px";
       img.style.height = "120px";
       img.style.objectFit = "cover";
       wrap.appendChild(img);
    } else {
       wrap.innerHTML = '<div style="width:120px;height:120px;border:1px dashed #ddd;display:inline-flex;align-items:center;justify-content:center;color:#888">Tidak ada foto</div>';
    }

    // show modal
    document.getElementById('modalEdit').style.display = "flex";
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
}, false);

function closeEdit(){
  document.getElementById('modalEdit').style.display = "none";
}
</script>

<?php include "partials/footer.php"; ?>

<?php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_stok'])) {

    $id = intval($_POST['id_plastik']);

    $stok_cs_kemarin = $_POST['stok_cs_kemarin'] !== '' ? intval($_POST['stok_cs_kemarin']) : "NULL";
    $repack_stok     = $_POST['repack_stok'] !== '' ? intval($_POST['repack_stok']) : "NULL";
    $jumlah_total    = $_POST['jumlah_total_stok_kemarin_retur_repack'] !== '' ? intval($_POST['jumlah_total_stok_kemarin_retur_repack']) : "NULL";
    $total_barel     = $_POST['total_barel'] !== '' ? intval($_POST['total_barel']) : "NULL";
    $stok_setelah    = $_POST['stok_cs_setelah_dikurangi_barel'] !== '' ? intval($_POST['stok_cs_setelah_dikurangi_barel']) : "NULL";
    $total_final     = $_POST['total_produksi_hari_ini_final'] !== '' ? intval($_POST['total_produksi_hari_ini_final']) : "NULL";

    $sql = "
        UPDATE penggunaan_plastik SET
            stok_cs_kemarin = " . ($stok_cs_kemarin === "NULL" ? "NULL" : $stok_cs_kemarin) . ",
            repack_stok = " . ($repack_stok === "NULL" ? "NULL" : $repack_stok) . ",
            jumlah_total_stok_kemarin_retur_repack = " . ($jumlah_total === "NULL" ? "NULL" : $jumlah_total) . ",
            total_barel = " . ($total_barel === "NULL" ? "NULL" : $total_barel) . ",
            stok_cs_setelah_dikurangi_barel = " . ($stok_setelah === "NULL" ? "NULL" : $stok_setelah) . ",
            total_produksi_hari_ini_final = " . ($total_final === "NULL" ? "NULL" : $total_final) . "
        WHERE id_plastik = $id
    ";

    mysqli_query($conn, $sql);
    header("Location: penggunaan_plastik_stok.php?success=1");
    exit;
}
?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
.page-container { margin-left:290px; padding:32px; min-height:100vh; background:var(--body-bg); }
.form-card { max-width:920px; margin:auto; padding:22px; background:var(--card-bg); border-radius:12px; }

.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
input, select { width:100%; padding:10px; border-radius:8px; border:2px solid var(--title-color); }

.btn { padding:12px; border-radius:10px; width:100%; border:none; font-weight:700; cursor:pointer; transition:.2s; }
.btn:hover { transform:scale(1.04); }

.btn-save { background:var(--hover-bg); }
.export-btn { background:#1cbfff; }

.notice{ color:green; font-weight:700; margin-bottom:10px; }
</style>

<div class="page-container">
  <div class="form-card">

    <h2 style="color:var(--title-color); text-align:center;">📦 Input / Update — Stok (Ringkas)</h2>

    <?php if(isset($_GET['success'])): ?>
        <div class="notice">✔ Data stok berhasil diperbarui.</div>
    <?php endif; ?>

    <!-- FORM UPDATE STOK -->
    <form method="POST">

      <label>Pilih ID</label>
      <select name="id_plastik" required>
        <option value="">-- Pilih ID --</option>
        <?php
        $q = mysqli_query($conn,"SELECT id_plastik,tanggal_input FROM penggunaan_plastik ORDER BY id_plastik DESC LIMIT 200");
        while($rw = mysqli_fetch_assoc($q)){
            echo "<option value='{$rw['id_plastik']}'>ID {$rw['id_plastik']} — {$rw['tanggal_input']}</option>";
        }
        ?>
      </select>

      <div class="form-grid" style="margin-top:16px;">
        <div>
          <label>Stok CS Kemarin</label>
          <input name="stok_cs_kemarin" type="number">

          <label>Repack Stok</label>
          <input name="repack_stok" type="number">

          <label>Jumlah Total Stok (kemarin + retur + repack)</label>
          <input name="jumlah_total_stok_kemarin_retur_repack" type="number">
        </div>

        <div>
          <label>Total Barkel</label>
          <input name="total_barel" type="number">

          <label>Stok CS Setelah Dikurangi Barkel</label>
          <input name="stok_cs_setelah_dikurangi_barel" type="number">

          <label>Total Produksi Hari Ini (Final)</label>
          <input name="total_produksi_hari_ini_final" type="number">
        </div>
      </div>

      <button class="btn btn-save" name="submit_stok" type="submit" style="margin-top:18px;">💾 Update Stok</button>

    </form>

    <!-- FORM EXPORT (BERDIRI SENDIRI) -->
    <form action="export_penggunaan_plastik_stok.php" method="GET" style="margin-top:12px;">
        <button class="btn export-btn" type="submit">📤 Export Excel (Stok)</button>
    </form>

  </div>
</div>

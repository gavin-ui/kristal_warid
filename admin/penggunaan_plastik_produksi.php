<?php
// penggunaan_plastik_produksi.php
include "../koneksi.php";

/* PROSES UPDATE: jika form submit untuk produksi, update record yang dipilih */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_produksi'])) {

    $id = intval($_POST['id_plastik']);

    // ambil nilai (jika kosong, set NULL)
    $total_kristal = $_POST['total_kristal'] !== '' ? intval($_POST['total_kristal']) : "NULL";
    $total_serut   = $_POST['total_serut'] !== '' ? intval($_POST['total_serut']) : "NULL";
    $total_rusak   = $_POST['total_rusak'] !== '' ? intval($_POST['total_rusak']) : "NULL";
    $sisa_terpakai = $_POST['sisa_total_plastik_terpakai'] !== '' ? intval($_POST['sisa_total_plastik_terpakai']) : "NULL";
    $hasil_hari    = $_POST['hasil_produksi_hari_ini'] !== '' ? intval($_POST['hasil_produksi_hari_ini']) : "NULL";
    $total_a       = $_POST['total_hasil_produksi_mesin_a'] !== '' ? intval($_POST['total_hasil_produksi_mesin_a']) : "NULL";
    $total_b       = $_POST['total_hasil_produksi_mesin_b'] !== '' ? intval($_POST['total_hasil_produksi_mesin_b']) : "NULL";
    $jumlah_total  = $_POST['jumlah_hasil_produksi_keseluruhan'] !== '' ? intval($_POST['jumlah_hasil_produksi_keseluruhan']) : "NULL";
    $total_final   = $_POST['total_produksi_hari_ini_final'] !== '' ? intval($_POST['total_produksi_hari_ini_final']) : "NULL";
    $retur_total   = $_POST['retur_total_dari_armada'] !== '' ? intval($_POST['retur_total_dari_armada']) : "NULL";

    $sql = "UPDATE penggunaan_plastik SET
        total_kristal = " . ($total_kristal === "NULL" ? "NULL" : $total_kristal) . ",
        total_serut = "   . ($total_serut   === "NULL" ? "NULL" : $total_serut)   . ",
        total_rusak = "   . ($total_rusak   === "NULL" ? "NULL" : $total_rusak)   . ",
        sisa_total_plastik_terpakai = " . ($sisa_terpakai === "NULL" ? "NULL" : $sisa_terpakai) . ",
        hasil_produksi_hari_ini = " . ($hasil_hari === "NULL" ? "NULL" : $hasil_hari) . ",
        total_hasil_produksi_mesin_a = " . ($total_a === "NULL" ? "NULL" : $total_a) . ",
        total_hasil_produksi_mesin_b = " . ($total_b === "NULL" ? "NULL" : $total_b) . ",
        jumlah_hasil_produksi_keseluruhan = " . ($jumlah_total === "NULL" ? "NULL" : $jumlah_total) . ",
        total_produksi_hari_ini_final = " . ($total_final === "NULL" ? "NULL" : $total_final) . ",
        retur_total_dari_armada = " . ($retur_total === "NULL" ? "NULL" : $retur_total) . "
        WHERE id_plastik = " . $id;

    mysqli_query($conn, $sql);
    header("Location: penggunaan_plastik_produksi.php?success=1");
    exit;
}

/* --- setelah semua proses, include sidebar dan tampilkan form --- */
?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
.page-container { margin-left:290px; padding:32px; min-height:100vh; background:var(--body-bg); }
.form-card { max-width:980px; margin:auto; background:var(--card-bg); padding:20px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,.06);}
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
input, select { width:100%; padding:10px; border-radius:8px; border:2px solid var(--title-color); box-sizing:border-box; }
.btn { width:100%; padding:12px; border-radius:10px; border:none; font-weight:700; cursor:pointer; }
.btn-save { background:var(--hover-bg); }
.notice { margin-bottom:12px; color:green; font-weight:700;}
</style>

<div class="page-container">
  <div class="form-card">
    <h2 style="color:var(--title-color); text-align:center;">🏭 Input / Update — Produksi</h2>

    <?php if(isset($_GET['success'])): ?>
      <div class="notice">✔ Data produksi berhasil diperbarui.</div>
    <?php endif; ?>

    <!-- Pilih ID (record yang mau di-update) -->
    <form method="POST">
      <label>Pilih ID (rekam yang ingin di-update)</label>
      <select name="id_plastik" required>
        <option value="">-- Pilih ID --</option>
        <?php
        $r = mysqli_query($conn, "SELECT id_plastik, tanggal_input FROM penggunaan_plastik ORDER BY id_plastik DESC LIMIT 200");
        while($row = mysqli_fetch_assoc($r)) {
            echo "<option value=\"{$row['id_plastik']}\">ID {$row['id_plastik']} — {$row['tanggal_input']}</option>";
        }
        ?>
      </select>

      <div class="form-grid" style="margin-top:12px;">
        <div>
          <label>Total Kristal</label>
          <input name="total_kristal" type="number" step="1" placeholder="0">
          <label>Total Serut</label>
          <input name="total_serut" type="number" step="1" placeholder="0">
          <label>Total Rusak</label>
          <input name="total_rusak" type="number" step="1" placeholder="0">
          <label>Sisa Total Plastik Terpakai</label>
          <input name="sisa_total_plastik_terpakai" type="number" step="1" placeholder="0">
        </div>

        <div>
          <label>Hasil Produksi Hari Ini</label>
          <input name="hasil_produksi_hari_ini" type="number" step="1" placeholder="0">
          <label>Total Mesin A</label>
          <input name="total_hasil_produksi_mesin_a" type="number" step="1" placeholder="0">
          <label>Total Mesin B</label>
          <input name="total_hasil_produksi_mesin_b" type="number" step="1" placeholder="0">
          <label>Jumlah Produksi Keseluruhan</label>
          <input name="jumlah_hasil_produksi_keseluruhan" type="number" step="1" placeholder="0">
        </div>
      </div>

      <div style="margin-top:12px;" class="form-grid">
        <div>
          <label>Total Produksi Hari Ini (Final)</label>
          <input name="total_produksi_hari_ini_final" type="number" step="1" placeholder="0">
        </div>
        <div>
          <label>Retur Total dari Armada</label>
          <input name="retur_total_dari_armada" type="number" step="1" placeholder="0">
        </div>
      </div>

      <div style="margin-top:14px;">
        <button class="btn btn-save" name="submit_produksi" type="submit">💾 Update Produksi</button>
      </div>
    </form>
  </div>
</div>

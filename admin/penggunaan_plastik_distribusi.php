<?php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_distribusi'])) {
    $id = intval($_POST['id_plastik']);

    $d1 = $_POST['distribusi_barkel_carry_h8516gk'] !== '' ? intval($_POST['distribusi_barkel_carry_h8516gk']) : "NULL";
    $d2 = $_POST['distribusi_barkel_long_hb017ov'] !== '' ? intval($_POST['distribusi_barkel_long_hb017ov']) : "NULL";
    $d3 = $_POST['distribusi_barkel_traga_h9876ag'] !== '' ? intval($_POST['distribusi_barkel_traga_h9876ag']) : "NULL";
    $d4 = $_POST['distribusi_barkel_elf_h8023ov'] !== '' ? intval($_POST['distribusi_barkel_elf_h8023ov']) : "NULL";
    $d5 = $_POST['distribusi_barkel_elf_h8019ov'] !== '' ? intval($_POST['distribusi_barkel_elf_h8019ov']) : "NULL";

    // total_barel optional sum
    $arr = [];
    foreach([$d1,$d2,$d3,$d4,$d5] as $x) if ($x !== "NULL") $arr[] = $x;
    $total_barel = count($arr) ? array_sum($arr) : "NULL";

    $sql = "UPDATE penggunaan_plastik SET
        distribusi_barkel_carry_h8516gk = " . ($d1 === "NULL" ? "NULL" : $d1) . ",
        distribusi_barkel_long_hb017ov = " . ($d2 === "NULL" ? "NULL" : $d2) . ",
        distribusi_barkel_traga_h9876ag = " . ($d3 === "NULL" ? "NULL" : $d3) . ",
        distribusi_barkel_elf_h8023ov = " . ($d4 === "NULL" ? "NULL" : $d4) . ",
        distribusi_barkel_elf_h8019ov = " . ($d5 === "NULL" ? "NULL" : $d5) . ",
        total_barel = " . ($total_barel === "NULL" ? "NULL" : $total_barel) . "
        WHERE id_plastik = " . $id;

    mysqli_query($conn, $sql);
    header("Location: penggunaan_plastik_distribusi.php?success=1");
    exit;
}
?>

<?php include "partials/sidebar.php"; ?>

<style>
.page-container { margin-left:290px; padding:32px; min-height:100vh; background:var(--body-bg); }
.form-card { max-width:920px; margin:auto; padding:18px; background:var(--card-bg); border-radius:12px; }
.form-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
input, select{ width:100%; padding:10px; border-radius:8px; border:2px solid var(--title-color); }
.btn { padding:12px; border-radius:10px; width:100%; border:none; font-weight:700; cursor:pointer; }
.btn-save { background:var(--hover-bg); }
.notice{ color:green; font-weight:700; margin-bottom:8px;}
</style>

<div class="page-container">
  <div class="form-card">
    <h2 style="color:var(--title-color); text-align:center;">🚚 Input / Update — Distribusi Barkel</h2>

    <?php if(isset($_GET['success'])): ?><div class="notice">✔ Data distribusi berhasil diperbarui.</div><?php endif; ?>

    <form method="POST">
      <label>Pilih ID</label>
      <select name="id_plastik" required>
        <option value="">-- Pilih ID --</option>
        <?php
        $q = mysqli_query($conn,"SELECT id_plastik,tanggal_input FROM penggunaan_plastik ORDER BY id_plastik DESC LIMIT 200");
        while($rw = mysqli_fetch_assoc($q)) echo "<option value=\"{$rw['id_plastik']}\">ID {$rw['id_plastik']} — {$rw['tanggal_input']}</option>";
        ?>
      </select>

      <div class="form-grid" style="margin-top:12px;">
        <div>
          <label>Distribusi Carry</label><input name="distribusi_barkel_carry_h8516gk" type="number" step="1">
          <label>Distribusi Long</label><input name="distribusi_barkel_long_hb017ov" type="number" step="1">
          <label>Distribusi Traga</label><input name="distribusi_barkel_traga_h9876ag" type="number" step="1">
        </div>
        <div>
          <label>Distribusi Elf 8023</label><input name="distribusi_barkel_elf_h8023ov" type="number" step="1">
          <label>Distribusi Elf 8019</label><input name="distribusi_barkel_elf_h8019ov" type="number" step="1">
          <label>Total Barkel (opsional)</label><input name="total_barel" type="number" step="1">
        </div>
      </div>

      <div style="margin-top:12px;">
        <button class="btn btn-save" name="submit_distribusi" type="submit">💾 Update Distribusi</button>
      </div>
    </form>
  </div>
</div>

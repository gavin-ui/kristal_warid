<?php
// penggunaan_plastik_retur.php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_retur'])) {
    $id = intval($_POST['id_plastik']);

    $r1 = $_POST['retur_armada_carry_h8516gk'] !== '' ? intval($_POST['retur_armada_carry_h8516gk']) : "NULL";
    $r2 = $_POST['retur_armada_long_hb017ov'] !== '' ? intval($_POST['retur_armada_long_hb017ov']) : "NULL";
    $r3 = $_POST['retur_armada_traga_h9876ag'] !== '' ? intval($_POST['retur_armada_traga_h9876ag']) : "NULL";
    $r4 = $_POST['retur_armada_elf_h8023ov'] !== '' ? intval($_POST['retur_armada_elf_h8023ov']) : "NULL";
    $r5 = $_POST['retur_armada_elf_h8019ov'] !== '' ? intval($_POST['retur_armada_elf_h8019ov']) : "NULL";
    $r6 = $_POST['retur_armada_elf_dobel_h8021ov'] !== '' ? intval($_POST['retur_armada_elf_dobel_h8021ov']) : "NULL";

    // recompute retur_total_dari_armada optionally from inputs (sum of available)
    $sumPieces = [];
    foreach ([$r1,$r2,$r3,$r4,$r5,$r6] as $x) {
        if ($x !== "NULL") $sumPieces[] = $x;
    }
    $retur_total = count($sumPieces) ? array_sum($sumPieces) : "NULL";

    $sql = "UPDATE penggunaan_plastik SET
        retur_armada_carry_h8516gk = " . ($r1 === "NULL" ? "NULL" : $r1) . ",
        retur_armada_long_hb017ov = " . ($r2 === "NULL" ? "NULL" : $r2) . ",
        retur_armada_traga_h9876ag = " . ($r3 === "NULL" ? "NULL" : $r3) . ",
        retur_armada_elf_h8023ov = " . ($r4 === "NULL" ? "NULL" : $r4) . ",
        retur_armada_elf_h8019ov = " . ($r5 === "NULL" ? "NULL" : $r5) . ",
        retur_armada_elf_dobel_h8021ov = " . ($r6 === "NULL" ? "NULL" : $r6) . ",
        retur_total_dari_armada = " . ($retur_total === "NULL" ? "NULL" : $retur_total) . "
        WHERE id_plastik = " . $id;

    mysqli_query($conn, $sql);
    header("Location: penggunaan_plastik_retur.php?success=1");
    exit;
}
?>

<?php include "partials/sidebar.php"; ?>

<style>
.page-container { margin-left:290px; padding:32px; min-height:100vh; background:var(--body-bg); }
.form-card { max-width:900px; margin:auto; background:var(--card-bg); padding:18px; border-radius:10px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
input, select { width:100%; padding:10px; border-radius:8px; border:2px solid var(--title-color); }
.btn { padding:12px; border-radius:10px; width:100%; border:none; font-weight:700; cursor:pointer;}
.btn-save { background:var(--hover-bg); }
.notice{ color:green; font-weight:700; margin-bottom:10px;}
</style>

<div class="page-container">
  <div class="form-card">
    <h2 style="color:var(--title-color); text-align:center;">↩️ Input / Update — Retur Armada</h2>

    <?php if(isset($_GET['success'])): ?><div class="notice">✔ Data retur berhasil diperbarui.</div><?php endif; ?>

    <form method="POST">
      <label>Pilih ID yang ingin di-update</label>
      <select name="id_plastik" required>
        <option value="">-- Pilih ID --</option>
        <?php
        $q = mysqli_query($conn,"SELECT id_plastik, tanggal_input FROM penggunaan_plastik ORDER BY id_plastik DESC LIMIT 200");
        while($rw = mysqli_fetch_assoc($q)) {
            echo "<option value=\"{$rw['id_plastik']}\">ID {$rw['id_plastik']} — {$rw['tanggal_input']}</option>";
        }
        ?>
      </select>

      <div class="form-grid" style="margin-top:12px;">
        <div>
          <label>Retur Carry</label><input name="retur_armada_carry_h8516gk" type="number" step="1">
          <label>Retur Long</label><input name="retur_armada_long_hb017ov" type="number" step="1">
        </div>
        <div>
          <label>Retur Traga</label><input name="retur_armada_traga_h9876ag" type="number" step="1">
          <label>Retur Elf 8023</label><input name="retur_armada_elf_h8023ov" type="number" step="1">
        </div>
        <div>
          <label>Retur Elf 8019</label><input name="retur_armada_elf_h8019ov" type="number" step="1">
          <label>Retur Elf Dobel</label><input name="retur_armada_elf_dobel_h8021ov" type="number" step="1">
        </div>
      </div>

      <div style="margin-top:12px;">
        <button class="btn btn-save" name="submit_retur" type="submit">💾 Update Retur</button>
      </div>
    </form>
  </div>
</div>

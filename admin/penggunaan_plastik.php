<?php
include "../koneksi.php";

/* jika submit full edit */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_full_edit'])) {
    $id = intval($_POST['id_plastik']);

    // daftar kolom yang akan di-update (ambil dari struktur tabel)
    $cols = [
      'plastik_awal','sisa_plastik_kemarin','total_penggunaan_plastik',
      'total_kristal','total_serut','total_rusak','sisa_total_plastik_terpakai','keterangan',
      'retur_armada_carry_h8516gk','retur_armada_long_hb017ov','retur_armada_traga_h9876ag',
      'retur_armada_elf_h8023ov','retur_armada_elf_h8019ov','retur_armada_elf_dobel_h8021ov',
      'distribusi_barkel_carry_h8516gk','distribusi_barkel_long_hb017ov','distribusi_barkel_traga_h9876ag',
      'distribusi_barkel_elf_h8023ov','distribusi_barkel_elf_h8019ov','hasil_produksi_hari_ini',
      'retur_total_dari_armada','stok_cs_kemarin','repack_stok','jumlah_total_stok_kemarin_retur_repack',
      'total_hasil_produksi_mesin_a','total_hasil_produksi_mesin_b','jumlah_hasil_produksi_keseluruhan',
      'total_barel','total_produksi_hari_ini_final','stok_cs_setelah_dikurangi_barel'
    ];

    $sets = [];
    foreach($cols as $c) {
        if (isset($_POST[$c]) && $_POST[$c] !== '') {
            $val = mysqli_real_escape_string($conn, $_POST[$c]);
            $sets[] = "$c = '$val'";
        } else {
            $sets[] = "$c = NULL";
        }
    }

    if (count($sets)) {
        $sql = "UPDATE penggunaan_plastik SET " . implode(",", $sets) . " WHERE id_plastik = $id";
        mysqli_query($conn, $sql);
    }

    header("Location: penggunaan_plastik.php?success=1");
    exit;
}

/* ambil data untuk dropdown */
$rows = mysqli_query($conn, "SELECT id_plastik, tanggal_input FROM penggunaan_plastik ORDER BY id_plastik DESC LIMIT 300");
?>
<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
.page-container { margin-left:290px; padding:24px; min-height:100vh; background:var(--body-bg); }
.form-card { max-width:1100px; margin:auto; background:var(--card-bg); padding:18px; border-radius:12px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
input, textarea, select { width:100%; padding:8px; border-radius:8px; border:2px solid var(--title-color); box-sizing:border-box; }
textarea { min-height:80px; }
.btn { padding:12px; border-radius:10px; border:none; cursor:pointer; font-weight:700; }
.btn-save { background:var(--hover-bg); }
.notice{ color:green; font-weight:700; }
</style>

<div class="page-container">
  <div class="form-card">
    <h2 style="color:var(--title-color); text-align:center;">🛠 Edit Semua Field — Penggunaan Plastik</h2>

    <?php if(isset($_GET['success'])): ?><div class="notice">✔ Perubahan berhasil disimpan.</div><?php endif; ?>

    <label>Pilih ID</label>
    <select id="selectRecord" onchange="loadRecord()" >
      <option value="">-- Pilih ID untuk load data --</option>
      <?php while($r = mysqli_fetch_assoc($rows)) echo "<option value=\"{$r['id_plastik']}\">ID {$r['id_plastik']} — {$r['tanggal_input']}</option>"; ?>
    </select>

    <form method="POST" id="fullEditForm" style="margin-top:12px;">
      <input type="hidden" name="id_plastik" id="id_plastik">

      <div class="form-grid">
        <div>
          <label>Plastik Awal</label><input name="plastik_awal" id="plastik_awal">
          <label>Sisa Plastik Kemarin</label><input name="sisa_plastik_kemarin" id="sisa_plastik_kemarin">
          <label>Total Penggunaan Plastik</label><input name="total_penggunaan_plastik" id="total_penggunaan_plastik">
          <label>Total Kristal</label><input name="total_kristal" id="total_kristal">
          <label>Total Serut</label><input name="total_serut" id="total_serut">
          <label>Total Rusak</label><input name="total_rusak" id="total_rusak">
        </div>

        <div>
          <label>Sisa Total Plastik Terpakai</label><input name="sisa_total_plastik_terpakai" id="sisa_total_plastik_terpakai">
          <label>Keterangan</label><textarea name="keterangan" id="keterangan"></textarea>
          <label>Hasil Produksi Hari Ini</label><input name="hasil_produksi_hari_ini" id="hasil_produksi_hari_ini">
          <label>Total Mesin A</label><input name="total_hasil_produksi_mesin_a" id="total_hasil_produksi_mesin_a">
          <label>Total Mesin B</label><input name="total_hasil_produksi_mesin_b" id="total_hasil_produksi_mesin_b">
        </div>

        <div>
          <label>Jumlah Produksi Keseluruhan</label><input name="jumlah_hasil_produksi_keseluruhan" id="jumlah_hasil_produksi_keseluruhan">
          <label>Retur Carry</label><input name="retur_armada_carry_h8516gk" id="retur_armada_carry_h8516gk">
          <label>Retur Long</label><input name="retur_armada_long_hb017ov" id="retur_armada_long_hb017ov">
          <label>Retur Traga</label><input name="retur_armada_traga_h9876ag" id="retur_armada_traga_h9876ag">
          <label>Retur Elf 8023</label><input name="retur_armada_elf_h8023ov" id="retur_armada_elf_h8023ov">
        </div>

        <div>
          <label>Retur Elf 8019</label><input name="retur_armada_elf_h8019ov" id="retur_armada_elf_h8019ov">
          <label>Retur Elf Dobel</label><input name="retur_armada_elf_dobel_h8021ov" id="retur_armada_elf_dobel_h8021ov">
          <label>Distribusi Carry</label><input name="distribusi_barkel_carry_h8516gk" id="distribusi_barkel_carry_h8516gk">
          <label>Distribusi Long</label><input name="distribusi_barkel_long_hb017ov" id="distribusi_barkel_long_hb017ov">
          <label>Distribusi Traga</label><input name="distribusi_barkel_traga_h9876ag" id="distribusi_barkel_traga_h9876ag">
        </div>

        <div>
          <label>Distribusi Elf 8023</label><input name="distribusi_barkel_elf_h8023ov" id="distribusi_barkel_elf_h8023ov">
          <label>Distribusi Elf 8019</label><input name="distribusi_barkel_elf_h8019ov" id="distribusi_barkel_elf_h8019ov">
          <label>Retur Total dari Armada</label><input name="retur_total_dari_armada" id="retur_total_dari_armada">
          <label>Stok CS Kemarin</label><input name="stok_cs_kemarin" id="stok_cs_kemarin">
          <label>Repack Stok</label><input name="repack_stok" id="repack_stok">
        </div>

        <div>
          <label>Jumlah Total Stok Kemarin/Retur/Repack</label><input name="jumlah_total_stok_kemarin_retur_repack" id="jumlah_total_stok_kemarin_retur_repack">
          <label>Total Barkel</label><input name="total_barel" id="total_barel">
          <label>Total Produksi Hari Ini Final</label><input name="total_produksi_hari_ini_final" id="total_produksi_hari_ini_final">
          <label>Stok CS Setelah Dikurangi Barkel</label><input name="stok_cs_setelah_dikurangi_barel" id="stok_cs_setelah_dikurangi_barel">
        </div>
      </div>

      <div style="margin-top:12px;">
        <button class="btn btn-save" name="submit_full_edit" type="submit">💾 Simpan Semua Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
// load record via AJAX-ish (fetch) - simple approach: call a tiny endpoint that returns JSON
// but kita tidak buat endpoint baru; sebagai solusi ringan, kita dapat fetch data lewat PHP file that outputs JSON
// implementasi simple: AJAX fetch ke same file with ?get=id -> we return JSON
async function loadRecord(){
    const id = document.getElementById('selectRecord').value;
    if(!id) return;
    const res = await fetch('penggunaan_plastik_get.php?id=' + encodeURIComponent(id));
    if(!res.ok) return alert('Gagal mengambil data');
    const data = await res.json();

    document.getElementById('id_plastik').value = data.id_plastik || '';
    // set many fields safely (if element exists)
    const setIf = (idk, val) => { const el = document.getElementById(idk); if(el) el.value = val === null ? '' : val; };
    setIf('plastik_awal', data.plastik_awal);
    setIf('sisa_plastik_kemarin', data.sisa_plastik_kemarin);
    setIf('total_penggunaan_plastik', data.total_penggunaan_plastik);
    setIf('total_kristal', data.total_kristal);
    setIf('total_serut', data.total_serut);
    setIf('total_rusak', data.total_rusak);
    setIf('sisa_total_plastik_terpakai', data.sisa_total_plastik_terpakai);
    setIf('keterangan', data.keterangan);
    setIf('retur_armada_carry_h8516gk', data.retur_armada_carry_h8516gk);
    setIf('retur_armada_long_hb017ov', data.retur_armada_long_hb017ov);
    setIf('retur_armada_traga_h9876ag', data.retur_armada_traga_h9876ag);
    setIf('retur_armada_elf_h8023ov', data.retur_armada_elf_h8023ov);
    setIf('retur_armada_elf_h8019ov', data.retur_armada_elf_h8019ov);
    setIf('retur_armada_elf_dobel_h8021ov', data.retur_armada_elf_dobel_h8021ov);
    setIf('distribusi_barkel_carry_h8516gk', data.distribusi_barkel_carry_h8516gk);
    setIf('distribusi_barkel_long_hb017ov', data.distribusi_barkel_long_hb017ov);
    setIf('distribusi_barkel_traga_h9876ag', data.distribusi_barkel_traga_h9876ag);
    setIf('distribusi_barkel_elf_h8023ov', data.distribusi_barkel_elf_h8023ov);
    setIf('distribusi_barkel_elf_h8019ov', data.distribusi_barkel_elf_h8019ov);
    setIf('hasil_produksi_hari_ini', data.hasil_produksi_hari_ini);
    setIf('retur_total_dari_armada', data.retur_total_dari_armada);
    setIf('stok_cs_kemarin', data.stok_cs_kemarin);
    setIf('repack_stok', data.repack_stok);
    setIf('jumlah_total_stok_kemarin_retur_repack', data.jumlah_total_stok_kemarin_retur_repack);
    setIf('total_hasil_produksi_mesin_a', data.total_hasil_produksi_mesin_a);
    setIf('total_hasil_produksi_mesin_b', data.total_hasil_produksi_mesin_b);
    setIf('jumlah_hasil_produksi_keseluruhan', data.jumlah_hasil_produksi_keseluruhan);
    setIf('total_barel', data.total_barel);
    setIf('total_produksi_hari_ini_final', data.total_produksi_hari_ini_final);
    setIf('stok_cs_setelah_dikurangi_barel', data.stok_cs_setelah_dikurangi_barel);
}
</script>

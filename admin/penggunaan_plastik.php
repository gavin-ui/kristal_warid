<?php
include "../koneksi.php";

/* =========================
   SIMPAN FULL EDIT
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_full_edit'])) {

    $id = intval($_POST['id_plastik']);

    $cols = [
        'plastik_awal','sisa_plastik_kemarin','total_penggunaan_plastik',
        'total_kristal','total_serut','total_rusak','sisa_total_plastik_terpakai','keterangan',
        'retur_armada_carry_h8516gk','retur_armada_long_hb017ov','retur_armada_traga_h9876ag',
        'retur_armada_elf_h8023ov','retur_armada_elf_h8019ov','retur_armada_elf_dobel_h8021ov',
        'distribusi_barkel_carry_h8516gk','distribusi_barkel_long_hb017ov','distribusi_barkel_traga_h9876ag',
        'distribusi_barkel_elf_h8023ov','distribusi_barkel_elf_h8019ov','hasil_produksi_hari_ini',
        'retur_total_dari_armada','stok_cs_kemarin','repack_stok',
        'jumlah_total_stok_kemarin_retur_repack','total_hasil_produksi_mesin_a',
        'total_hasil_produksi_mesin_b','jumlah_hasil_produksi_keseluruhan',
        'total_barel','total_produksi_hari_ini_final','stok_cs_setelah_dikurangi_barel'
    ];

    $set = [];
    foreach ($cols as $c) {
        if (isset($_POST[$c]) && $_POST[$c] !== '') {
            $v = mysqli_real_escape_string($conn, $_POST[$c]);
            $set[] = "$c = '$v'";
        } else {
            $set[] = "$c = NULL";
        }
    }

    mysqli_query($conn,"
        UPDATE penggunaan_plastik SET ".implode(',', $set)."
        WHERE id_plastik = $id
    ");

    header("Location: penggunaan_plastik_edit_full.php?success=1");
    exit;
}

/* =========================
   DROPDOWN ID
========================= */
$list = mysqli_query($conn,"
    SELECT id_plastik, tanggal_input
    FROM penggunaan_plastik
    ORDER BY id_plastik DESC
    LIMIT 300
");
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
.page-container{
    margin-left:290px;
    padding:32px;
    min-height:100vh;
    background:var(--body-bg);
}
.form-card{
    max-width:1200px;
    margin:auto;
    background:var(--card-bg);
    padding:24px;
    border-radius:16px;
}
h2{
    color:var(--title-color);
    text-align:center;
    margin-bottom:16px;
}
.notice{
    color:#16a34a;
    font-weight:700;
    margin-bottom:12px;
}
.form-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
}
label{
    font-weight:700;
    color:var(--title-color);
    margin:10px 0 6px;
    display:block;
}
input, textarea, select{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:2px solid var(--title-color);
    background:transparent;
    color:var(--title-color);
    box-sizing:border-box;
}
textarea{ min-height:90px; }

/* HILANGKAN SPINNER */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
    margin:0;
}
input[type=number]{ -moz-appearance:textfield; }

.btn{
    padding:12px;
    border:none;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}
.btn-save{
    background:var(--hover-bg);
    color:var(--title-color);
}
.btn-save:hover{ transform:scale(1.05); }
</style>

<div class="page-container">
<div class="form-card">

<h2>🛠 Edit Semua Data — Penggunaan Plastik</h2>

<?php if(isset($_GET['success'])): ?>
<div class="notice">✔ Data berhasil diperbarui</div>
<?php endif; ?>

<label>Pilih ID</label>
<select id="selectID" onchange="loadData()">
    <option value="">-- Pilih ID --</option>
    <?php while($r=mysqli_fetch_assoc($list)): ?>
        <option value="<?= $r['id_plastik'] ?>">
            ID <?= $r['id_plastik'] ?> — <?= $r['tanggal_input'] ?>
        </option>
    <?php endwhile; ?>
</select>

<form method="POST" style="margin-top:16px;">
<input type="hidden" name="id_plastik" id="id_plastik">

<div class="form-grid">

<div>
<label>Plastik Awal</label><input id="plastik_awal" name="plastik_awal" type="number">
<label>Sisa Plastik Kemarin</label><input id="sisa_plastik_kemarin" name="sisa_plastik_kemarin" type="number">
<label>Total Penggunaan Plastik</label><input id="total_penggunaan_plastik" name="total_penggunaan_plastik" type="number">
<label>Total Kristal</label><input id="total_kristal" name="total_kristal" type="number">
<label>Total Serut</label><input id="total_serut" name="total_serut" type="number">
<label>Total Rusak</label><input id="total_rusak" name="total_rusak" type="number">
</div>

<div>
<label>Sisa Total Plastik Terpakai</label><input id="sisa_total_plastik_terpakai" name="sisa_total_plastik_terpakai" type="number">
<label>Keterangan</label><textarea id="keterangan" name="keterangan"></textarea>
<label>Hasil Produksi Hari Ini</label><input id="hasil_produksi_hari_ini" name="hasil_produksi_hari_ini" type="number">
<label>Total Mesin A</label><input id="total_hasil_produksi_mesin_a" name="total_hasil_produksi_mesin_a" type="number">
<label>Total Mesin B</label><input id="total_hasil_produksi_mesin_b" name="total_hasil_produksi_mesin_b" type="number">
</div>

<div>
<label>Jumlah Produksi Keseluruhan</label><input id="jumlah_hasil_produksi_keseluruhan" name="jumlah_hasil_produksi_keseluruhan" type="number">
<label>Retur Carry</label><input id="retur_armada_carry_h8516gk" name="retur_armada_carry_h8516gk" type="number">
<label>Retur Long</label><input id="retur_armada_long_hb017ov" name="retur_armada_long_hb017ov" type="number">
<label>Retur Traga</label><input id="retur_armada_traga_h9876ag" name="retur_armada_traga_h9876ag" type="number">
<label>Retur Elf 8023</label><input id="retur_armada_elf_h8023ov" name="retur_armada_elf_h8023ov" type="number">
<label>Retur Elf 8019</label><input id="retur_armada_elf_h8019ov" name="retur_armada_elf_h8019ov" type="number">
</div>

<div>
<label>Retur Elf Dobel</label><input id="retur_armada_elf_dobel_h8021ov" name="retur_armada_elf_dobel_h8021ov" type="number">
<label>Distribusi Carry</label><input id="distribusi_barkel_carry_h8516gk" name="distribusi_barkel_carry_h8516gk" type="number">
<label>Distribusi Long</label><input id="distribusi_barkel_long_hb017ov" name="distribusi_barkel_long_hb017ov" type="number">
<label>Distribusi Traga</label><input id="distribusi_barkel_traga_h9876ag" name="distribusi_barkel_traga_h9876ag" type="number">
<label>Distribusi Elf 8023</label><input id="distribusi_barkel_elf_h8023ov" name="distribusi_barkel_elf_h8023ov" type="number">
<label>Distribusi Elf 8019</label><input id="distribusi_barkel_elf_h8019ov" name="distribusi_barkel_elf_h8019ov" type="number">
</div>

<div>
<label>Retur Total Armada</label><input id="retur_total_dari_armada" name="retur_total_dari_armada" type="number">
<label>Stok CS Kemarin</label><input id="stok_cs_kemarin" name="stok_cs_kemarin" type="number">
<label>Repack Stok</label><input id="repack_stok" name="repack_stok" type="number">
<label>Jumlah Total Stok</label><input id="jumlah_total_stok_kemarin_retur_repack" name="jumlah_total_stok_kemarin_retur_repack" type="number">
<label>Total Barkel</label><input id="total_barel" name="total_barel" type="number">
<label>Total Produksi Final</label><input id="total_produksi_hari_ini_final" name="total_produksi_hari_ini_final" type="number">
</div>

<div>
<label>Stok CS Setelah Barkel</label>
<input id="stok_cs_setelah_dikurangi_barel" name="stok_cs_setelah_dikurangi_barel" type="number">
</div>

</div>

<button class="btn btn-save" name="submit_full_edit" style="margin-top:18px;">
💾 Simpan Semua Perubahan
</button>

</form>
</div>
</div>

<script>
async function loadData(){
    const id = document.getElementById('selectID').value;
    if(!id) return;

    const res = await fetch('penggunaan_plastik_get.php?id='+id);
    const d = await res.json();

    for(const k in d){
        const el = document.getElementById(k);
        if(el) el.value = d[k] ?? '';
    }
    document.getElementById('id_plastik').value = d.id_plastik;
}
</script>

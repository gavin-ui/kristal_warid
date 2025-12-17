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
/* =====================================================
   PAGE LAYOUT
===================================================== */
.page-container{
    margin-left:290px;
    padding:32px 28px 160px;
    min-height:100vh;
    background:var(--body-bg);
    transition:.35s ease;
}

body.collapsed .page-container{
    margin-left:110px;
}

/* =====================================================
   CARD UTAMA
===================================================== */
.form-card{
    max-width:1180px;
    margin:auto;
    padding:26px 30px 32px;
    border-radius:22px;

    background:linear-gradient(
        180deg,
        rgba(255,255,255,.96),
        rgba(255,255,255,.88)
    );

    border:1.5px solid rgba(255,186,39,.45);

    box-shadow:
        0 28px 55px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.7);
}

body.dark .form-card{
    background:linear-gradient(
        180deg,
        rgba(10,18,36,.96),
        rgba(8,14,30,.92)
    );
    border:1.5px solid rgba(90,169,255,.25);
    box-shadow:
        0 28px 55px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.12);
}

/* =====================================================
   TITLE
===================================================== */
.form-card h2{
    text-align:center;
    margin-bottom:20px;
    font-size:24px;
    font-weight:900;
    letter-spacing:.6px;

    background:linear-gradient(90deg,#2563eb,#f59e0b);
    -webkit-background-clip:text;
    color:transparent;
}

/* =====================================================
   NOTICE
===================================================== */
.notice{
    background:rgba(34,197,94,.12);
    border-left:6px solid #22c55e;
    padding:10px 14px;
    border-radius:10px;
    font-weight:700;
    margin-bottom:14px;
    color:#16a34a;
}

/* =====================================================
   SELECT ID
===================================================== */
select{
    width:100%;
    padding:11px 14px;
    border-radius:14px;
    border:1.8px solid #cbd5e1;
    font-size:13.5px;
    background:#fff;
    color:#0f172a;
}

body.dark select{
    background:rgba(10,18,36,.85);
    border-color:rgba(90,169,255,.35);
    color:#e5e7eb;
}

/* =====================================================
   FORM GRID BESAR (RAPI & RINGKAS)
===================================================== */
.form-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:30px 50px;
    margin-top:18px;
}

/* =====================================================
   LABEL
===================================================== */
label{
    display:block;
    margin:8px 0 5px;
    font-size:12.5px;
    font-weight:800;
    letter-spacing:.3px;
    color:#0f172a;
}

body.dark label{
    color:#e5e7eb;
}

/* =====================================================
   INPUT / TEXTAREA
===================================================== */
input,
textarea{
    width:100%;
    padding:9px 12px;
    border-radius:12px;
    border:1.6px solid #cbd5e1;
    font-size:13px;

    background:rgba(255,255,255,.92);
    transition:.25s ease;
}

textarea{
    min-height:78px;
    resize:none;
}

body.dark input,
body.dark textarea{
    background:rgba(10,18,36,.85);
    border-color:rgba(90,169,255,.35);
    color:#fff;
}

input:focus,
textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.25);
    outline:none;
}

/* =====================================================
   REMOVE NUMBER SPINNER
===================================================== */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
    margin:0;
}
input[type=number]{ -moz-appearance:textfield; }

/* =====================================================
   BUTTON SAVE
===================================================== */
.btn-save{
    margin-top:22px;
    width:100%;
    padding:14px;
    border-radius:18px;

    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;

    font-size:14.5px;
    font-weight:900;
    letter-spacing:.6px;

    border:2.5px solid #f59e0b;

    box-shadow:
        0 0 0 4px rgba(245,158,11,.35),
        0 18px 30px rgba(37,99,235,.45);

    cursor:pointer;
    transition:.35s ease;
}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow:
        0 0 0 6px rgba(245,158,11,.55),
        0 28px 45px rgba(37,99,235,.6);
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media(max-width:1200px){
    .form-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:768px){
    .page-container{
        margin-left:0;
        padding:28px 16px 140px;
    }

    .form-grid{
        grid-template-columns:1fr;
    }

    .form-card{
        padding:22px 18px 26px;
    }
}
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

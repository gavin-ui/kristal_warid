<?php 
include "../koneksi.php";

// INSERT DATA AWAL (membuat 1 baris baru di tabel penggunaan_plastik)
if(isset($_POST["submit"])){

    $tanggal   = $_POST["tanggal_input"];
    $plastik_awal = $_POST["plastik_awal"];
    $sisa_kemarin = $_POST["sisa_plastik_kemarin"];
    $total_penggunaan = $_POST["total_penggunaan_plastik"];

    mysqli_query($conn,"
        INSERT INTO penggunaan_plastik (
            tanggal_input,
            plastik_awal,
            sisa_plastik_kemarin,
            total_penggunaan_plastik
        )
        VALUES (
            '$tanggal',
            '$plastik_awal',
            '$sisa_kemarin',
            '$total_penggunaan'
        )
    ");

    header("Location: penggunaan_plastik_data_awal.php?success=1");
    exit;
}

?>
<?php include "partials/sidebar.php"; ?>

<style>
.page-container { 
    margin-left:290px; padding:35px; min-height:100vh; 
    background:var(--body-bg); 
}
.form-card { 
    background:var(--card-bg); padding:25px; border-radius:15px; 
    max-width:750px; margin:auto; 
}
input, select {
    width:100%; padding:12px; border-radius:10px;
    border:2px solid var(--title-color); margin-bottom:14px;
}
.btn-save { 
    width:100%; padding:12px; border-radius:10px; 
    background:var(--hover-bg); border:none; cursor:pointer; font-weight:bold;
}
.notice { 
    background:#22bb33; padding:12px; border-radius:10px; 
    color:white; text-align:center; margin-bottom:15px;
}
</style>

<div class="page-container">
<div class="form-card">

<h2 style="text-align:center;color:var(--title-color)">📦 Input Data Awal Plastik</h2>

<?php if(isset($_GET['success'])): ?>
<div class="notice">✔ Data awal berhasil disimpan!</div>
<?php endif; ?>

<form method="POST">

<label>Tanggal Input</label>
<input type="date" name="tanggal_input" required>

<label>Plastik Awal (Roll)</label>
<input type="number" name="plastik_awal" placeholder="Masukkan jumlah plastik awal" required>

<label>Sisa Plastik Kemarin (Roll)</label>
<input type="number" name="sisa_plastik_kemarin" placeholder="Masukkan sisa plastik dari hari sebelumnya" required>

<label>Total Penggunaan Plastik (Roll)</label>
<input type="number" name="total_penggunaan_plastik" placeholder="Total penggunaan plastik hari ini" required>

<button class="btn-save" name="submit">💾 Simpan Data Awal</button>

</form>
</div>
</div>

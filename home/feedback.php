<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>
<?php include "../koneksi.php"; ?>

<style>
.page-wrapper {
    width: 92%;
    max-width: 1100px;
    margin: auto;
    padding: 50px 0;
}

/* Card Form */
.feedback-box {
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    padding: 25px;
    border-left: 6px solid #007bff;
    box-shadow: 0 5px 18px rgba(0,140,255,.15);
}

/* Star Rating */
.star-rating {
    direction: rtl;
    font-size: 32px;
    cursor: pointer;
}

.star-rating input {
    display: none;
}

.star-rating label {
    color: #ccc;
    transition: .3s;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffb300;
}

/* Display Reviews */
.review {
    background: white;
    padding: 18px;
    border-radius: 16px;
    margin-top: 15px;
    border-left: 4px solid #007bff;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    animation: fadeUp .5s ease;
}

@keyframes fadeUp {
    from { opacity:0; transform: translateY(20px); }
    to { opacity:1; transform:translateY(0); }
}
</style>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
    $komentar = mysqli_real_escape_string($conn, $_POST["komentar"]);
    $rating = $_POST["rating"];

    mysqli_query($conn, "INSERT INTO feedback (nama, komentar, rating) VALUES ('$nama', '$komentar', '$rating')");
}
?>

<div class="page-wrapper">

    <h2 class="section-title">Kritik, Saran & Penilaian</h2>

    <!-- FORM -->
    <div class="feedback-box mb-4">
        <form method="POST">
            <label class="fw-bold">Nama Anda:</label>
            <input type="text" name="nama" class="form-control mb-3" required>

            <label class="fw-bold">Kritik / Saran:</label>
            <textarea name="komentar" class="form-control mb-3" rows="3" required></textarea>

            <label class="fw-bold">Beri Rating:</label>
            <div class="star-rating mb-3">
                <input type="radio" name="rating" id="5" value="5" required><label for="5">★</label>
                <input type="radio" name="rating" id="4" value="4"><label for="4">★</label>
                <input type="radio" name="rating" id="3" value="3"><label for="3">★</label>
                <input type="radio" name="rating" id="2" value="2"><label for="2">★</label>
                <input type="radio" name="rating" id="1" value="1"><label for="1">★</label>
            </div>

            <button class="btn btn-primary rounded-pill px-4">Kirim</button>
        </form>
    </div>

    <h2 class="section-title">Ulasan Pelanggan</h2>

    <?php 
    $result = mysqli_query($conn, "SELECT * FROM feedback ORDER BY id DESC");
    while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="review">
            <div style="font-size:20px; color:#ffb300;">
                <?= str_repeat("⭐", $row['rating']); ?>
            </div>
            <p class="mt-2">"<?= $row['komentar']; ?>"</p>
            <small><strong>- <?= $row['nama']; ?></strong> | <?= $row['tanggal']; ?></small>
        </div>
    <?php } ?>
</div>

<?php include "partials/footer.php"; ?>

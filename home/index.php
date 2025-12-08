<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* ===== GENERAL ===== */
body {
    background-color: #f4faff;
    font-family: 'Poppins', sans-serif;
}

/* ===== HERO TEXT ===== */
.hero {
    padding: 120px 20px 60px;
    color: #004aad;
}

/* ===== TITLES ===== */
.section-title {
    font-weight: 700;
    color: #005ce6;
    text-align: center;
    margin-bottom: 18px;
    font-size: 2rem;
}

/* ===== INFO CARD ===== */
.info-box {
    background: rgba(255,255,255,0.9);
    border-radius: 18px;
    padding: 22px;
    border-left: 6px solid #007bff;
    transition: 0.35s;
    backdrop-filter: blur(6px);
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
}

/* ===== GALLERY LAYOUT ===== */
.gallery-grid {
    display: grid;
    gap: 16px;
    margin-top: 25px;
}

/* 2 landscape top */
.gallery-grid-top {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 16px;
}

/* 3 vertical bottom */
.gallery-grid-bottom {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 16px;
}

.gallery-item {
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    transition: 0.4s;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-item:hover {
    transform: scale(1.06);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    filter: brightness(1.15);
}

/* Small snowflake style */
.gallery-item::after {
    content: "❄";
    position: absolute;
    bottom: 10px;
    right: 12px;
    background: rgba(255,255,255,0.8);
    border-radius: 50%;
    padding: 6px 12px;
    font-size: 14px;
    transition: 0.3s;
}
.gallery-item:hover::after {
    background: white;
}

/* ===== BUTTON ===== */
.learn-btn {
    background: #ffb200;
    border: none;
    padding: 14px 26px;
    color: #003366;
    font-weight: bold;
    border-radius: 50px;
    box-shadow: 0px 6px 12px rgba(255,180,0,0.3);
    transition: 0.3s;
}

.learn-btn:hover {
    background: #ff9900;
    transform: scale(1.06);
}

/* ===== MOBILE RESPONSIVE ===== */
@media(max-width: 768px) {
    .gallery-grid-top {
        grid-template-columns: 1fr;
    }
    .gallery-grid-bottom {
        grid-template-columns: 1fr 1fr;
    }
}
@media(max-width: 480px) {
    .gallery-grid-bottom {
        grid-template-columns: 1fr;
    }
}
</style>


<!-- ===== HERO SECTION ===== -->
<div class="hero text-center" data-aos="fade-down">
    <h1 class="fw-bold">PT Dongzan Jaya Utama</h1>
    <p class="lead">Produsen Es Kristal Modern, Higienis, dan Siap Distribusi Nasional</p>
</div>


<!-- ===== MAIN CONTENT ===== -->
<div class="container py-5">

    <section id="about" class="mb-5" data-aos="fade-right">
        <h2 class="section-title">Tentang Kami</h2>
        <div class="info-box">
            <p>
            PT Dongzan Jaya Utama merupakan produsen es kristal higienis dengan fasilitas modern 
            dan teknologi berstandar tinggi yang berlokasi di Magelang. Kami melayani kebutuhan 
            hotel, restoran, kafe, distributor, hingga industri penyimpanan pangan dan logistik pendingin.
            </p>
        </div>
    </section>

    <section id="layanan" class="mb-5" data-aos="fade-left">
        <h2 class="section-title">Keunggulan Kami</h2>
        <div class="info-box">
            <ul style="font-size:1.1rem;">
                <li>Produksi menggunakan mesin industri modern dan higienis</li>
                <li>Distribusi stabil dengan armada operasional internal</li>
                <li>Tersedia setiap hari untuk skala kecil hingga industri besar</li>
                <li>Kualitas kristal bening, aman konsumsi, dan bebas kontaminasi</li>
            </ul>
        </div>
    </section>


    <section id="gallery" data-aos="zoom-in">
        <h2 class="section-title">Dokumentasi Perusahaan</h2>

        <!-- New Grid Structure -->
        <div class="gallery-grid">

            <!-- Top (Landscape) -->
            <div class="gallery-grid-top">
                <div class="gallery-item" data-aos="zoom-in">
                    <img src="assets/gallery/WhatsApp Image 2025-12-04 at 09.29.13 (4).jpeg">
                </div>
                <div class="gallery-item" data-aos="zoom-in">
                    <img src="assets/gallery/WhatsApp Image 2025-12-04 at 09.29.15.jpeg">
                </div>
            </div>

            <!-- Bottom (Vertical) -->
            <div class="gallery-grid-bottom">
                <div class="gallery-item" data-aos="fade-up">
                    <img src="assets/gallery/WhatsApp Image 2025-12-04 at 09.29.13 (5).jpeg">
                </div>
                <div class="gallery-item" data-aos="fade-up">
                    <img src="assets/gallery/WhatsApp Image 2025-12-04 at 09.29.14 (3).jpeg">
                </div>
                <div class="gallery-item" data-aos="fade-up">
                    <img src="assets/gallery/WhatsApp Image 2025-12-04 at 09.29.14 (4).jpeg">
                </div>
            </div>

        </div>
    </section>

</div>


<!-- FOOTER -->
<?php include "partials/footer.php"; ?>

<!-- AOS Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({
    duration: 900,
    once: true
});
</script>

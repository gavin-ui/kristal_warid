<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>

<style>
/* === PAGE GLOBAL THEME === */
.page-wrapper {
    width: 92%;
    max-width: 1150px;
    margin: auto;
    padding: 50px 0;
    animation: fadeIn .9s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Section Title */
.section-title {
    font-weight: 700;
    font-size: 28px;
    text-align: left;
    border-left: 6px solid #007bff;
    padding-left: 12px;
    margin-bottom: 18px;
    color: #005ce6;
}

/* Glass Box */
.info-box {
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(10px);
    padding: 25px;
    border-radius: 18px;
    border-left: 6px solid #007bff;
    transition: .3s;
}

.info-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,140,255,0.2);
}

/* Google Maps Frame */
.map-container {
    width: 100%;
    height: 330px;
    border-radius: 15px;
    overflow: hidden;
    border: 4px solid #007bff;
    margin-top: 15px;
}

/* QR Code */
.qr-box {
    text-align: center;
    margin-top: 30px;
}

.qr-box img {
    width: 240px;
    border-radius: 12px;
    border: 3px solid #007bff;
    transition: .3s;
}

.qr-box img:hover {
    transform: scale(1.08);
    box-shadow: 0 0 20px rgba(0,140,255,.4);
}

/* FAQ Section */
.accordion-button {
    font-weight: 600;
    color: #005ce6;
}

.accordion-button:not(.collapsed) {
    background-color: #e9f2ff;
    color: #003b99;
}

.accordion-body {
    font-size: 15px;
    line-height: 1.6;
}
</style>


<div class="page-wrapper">

    <!-- Tentang Perusahaan -->
    <h2 class="section-title">Tentang Perusahaan</h2>
    <div class="info-box">
        <p>
        PT Dongzan Jaya Utama merupakan perusahaan yang bergerak di bidang produksi 
        <strong>es kristal higienis</strong> dengan fasilitas modern, teknologi food-grade, 
        dan standar sanitasi tinggi. Berlokasi di Magelang, Jawa Tengah, perusahaan ini melayani hotel, restoran,
        café, distributor, hingga industri rantai dingin (cold storage).
        </p>
    </div>

    <!-- Visi & Misi -->
    <h2 class="section-title mt-4">Visi & Misi</h2>
    <div class="info-box">
        <strong>Visi:</strong>
        <p>Mewujudkan distribusi es kristal higienis terbaik yang dapat diakses secara luas dengan mutu tinggi dan konsisten.</p>
        
        <strong>Misi:</strong>
        <ul>
            <li>Menyediakan produk es kristal yang aman dan higienis.</li>
            <li>Menggunakan teknologi modern dan sistem sanitasi standar industri.</li>
            <li>Memperluas jaringan distribusi dengan pelayanan cepat dan stabil.</li>
            <li>Mendukung kebutuhan pangan dan industri minuman dengan suplai es berkualitas.</li>
        </ul>
    </div>

    <!-- Lokasi -->
    <h2 class="section-title mt-4">Lokasi Kami</h2>
    <div class="info-box">
        <p>Berikut lokasi resmi produksi PT Dongzan Jaya Utama:</p>

        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.166157247912!2d110.143387!3d-7.560627!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a947a1c8b345b%3A0xa6434db9c998029b!2sC5PC%2B4W5%2C%20Rejomulyo%202%2C%20Sidoagung%2C%20Tempuran%2C%20Magelang%2C%20Jawa%20Tengah!5e0!3m2!1sid!2sid!4v1702311110000" 
                width="100%" height="100%" allowfullscreen loading="lazy">
            </iframe>
        </div>
    </div>

    <!-- ORDER -->
    <h2 class="section-title mt-4">Cara Order</h2>
    <div class="info-box text-center">
        <p class="fw-semibold text-primary">Scan barcode di bawah ini untuk pemesanan & informasi lebih lanjut</p>

        <div class="qr-box">
            <img src="../home/assets/WhatsApp Image 2025-12-04 at 10.37.12.jpeg" alt="QR Order">
        </div>
    </div>

    <!-- FAQ Section -->
    <h2 class="section-title mt-4">FAQ Pelanggan</h2>
    <div class="info-box">
        <div class="accordion" id="faqAccordion">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#q1">
                        Berapa minimal pemesanan?
                    </button>
                </h2>
                <div id="q1" class="accordion-collapse collapse show">
                    <div class="accordion-body">Minimal pemesanan mengikuti jumlah permintaan dan wilayah distribusi.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#q2">
                        Apakah tersedia pengiriman harian?
                    </button>
                </h2>
                <div id="q2" class="accordion-collapse collapse">
                    <div class="accordion-body">Ya, kami menyediakan sistem pengiriman rutin untuk kebutuhan harian.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#q3">
                        Produk aman dikonsumsi?
                    </button>
                </h2>
                <div id="q3" class="accordion-collapse collapse">
                    <div class="accordion-body">Ya, es kristal diproduksi menggunakan standar higienis dan food-grade.</div>
                </div>
            </div>

        </div>
    </div>

</div>

<?php include "partials/footer.php"; ?>

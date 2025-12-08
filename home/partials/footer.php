<footer class="footer mt-5">

<style>
.footer {
    background: linear-gradient(180deg, #e9f4ff, #cfe6ff);
    padding: 38px 10px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Subtle frost texture */
.footer::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url('../home/assets/snow.png') repeat;
    opacity: 0.22;
    animation: footerSnow 25s linear infinite;
}

@keyframes footerSnow {
    0% { background-position: 0 0; }
    100% { background-position: 0 1200px; }
}

.footer-content {
    position: relative;
    z-index: 2;
}

/* Title */
.footer h3 {
    font-weight: 700;
    color: #0054d6;
}

/* Subtext */
.footer p {
    font-size: 14px;
    margin-top: 6px;
    color: #2b3e55;
}

/* Social icons */
.social-icons {
    margin: 12px 0 18px;
}

.social-icons a {
    display: inline-block;
    margin: 0 6px;
    color: #005fcc;
    font-size: 22px;
    transition: .3s;
    background: white;
    padding: 10px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.social-icons a:hover {
    transform: scale(1.15) rotate(4deg);
    background: #ffc400;
    color: #003060;
    box-shadow: 0 0 15px rgba(255,208,0,.5);
}

/* Copyright area */
.copyright {
    margin-top: 18px;
    font-size: 13px;
    color: #6b7b91;
}
</style>

<div class="footer-content">
    <h3>Es Kristal Warid</h3>
    <p>Produsen Es Kristal Higienis — PT Dongzan Jaya Utama, Magelang Jawa Tengah</p>

    <!-- Social Buttons -->
    <div class="social-icons">
        <a href="https://www.tiktok.com/@es.kristal.warid?_r=1&_t=ZS-91xlGSV66HH"><i class="bi bi-tiktok "></i></a>
        <a href="https://www.instagram.com/eskirstal_warid?igsh=MTE5N3Vncnd3bG1vZw=="><i class="bi bi-instagram"></i></a>
        <a href="https://wa.me/6282138074949"><i class="bi bi-whatsapp"></i></a>
    </div>

    <div class="copyright">
        © <?= date("Y") ?> — Semua Hak Cipta Dilindungi
    </div>
</div>
</footer>


<!-- Bootstrap & Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>

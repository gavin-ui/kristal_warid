</div> <!-- tutup .main-content -->

<footer class="admin-footer">

<style>
.admin-footer {
    position: fixed;
    bottom: 0;
    left: 265px;
    width: calc(100% - 265px);
    padding: 15px;
    text-align: center;
    background: var(--card-bg);
    border-top: 3px solid var(--title-color);
    color: var(--title-color);
    box-shadow: 0 -4px 15px rgba(0,0,0,.1);
    font-size: 14px;
    transition: .3s ease;
    z-index: 90;
}

/* Saat sidebar di-collapse */
body.collapsed .admin-footer {
    left: 90px;
    width: calc(100% - 90px);
}

/* Warna saat mode gelap */
body.dark .admin-footer {
    background: var(--card-bg);
    color: white;
}
</style>

© <?= date("Y") ?> — Sistem Manajemen | <strong>Es Kristal Warid</strong> ❄️
</footer>

</body>
</html>

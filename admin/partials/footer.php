</div> <!-- tutup .main-content -->

<footer class="admin-footer">

<style>
.admin-footer {
    position: fixed;
    bottom: 0;
    left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
    padding: 14px 20px;

    text-align: center;
    font-size: 13.5px;
    font-weight: 600;
    letter-spacing: .3px;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.75),
        rgba(255,255,255,0.92)
    );
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    color: var(--title-color);
    border-top: 2px solid rgba(255,255,255,0.55);

    box-shadow:
        0 -8px 24px rgba(0,0,0,0.12),
        inset 0 1px 0 rgba(255,255,255,0.6);

    transition: .35s ease;
    z-index: 90;
}

/* Accent glow line */
.admin-footer::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), var(--title-color));
    opacity: .85;
}

/* Sidebar collapse */
body.collapsed .admin-footer {
    left: var(--sidebar-collapsed-width);
    width: calc(100% - var(--sidebar-collapsed-width));
}

/* DARK MODE */
body.dark .admin-footer {
    background: linear-gradient(
        180deg,
        rgba(22,36,71,0.75),
        rgba(22,36,71,0.92)
    );
    color: #eaf2ff;
    border-top: 2px solid rgba(255,255,255,0.15);

    box-shadow:
        0 -8px 24px rgba(0,0,0,0.45),
        inset 0 1px 0 rgba(255,255,255,0.08);
}

/* Footer text accent */
.admin-footer strong {
    color: var(--accent);
    font-weight: 800;
}

/* Snow emoji glow */
.admin-footer span,
.admin-footer::after {
    filter: drop-shadow(0 0 6px rgba(0,153,255,.35));
}

</style>

© <?= date("Y") ?> — Sistem Manajemen | <strong>Es Kristal Warid</strong> ❄️
</footer>

</body>
</html>

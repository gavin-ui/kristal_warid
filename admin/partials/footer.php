<footer class="admin-footer">

<style>

/* Normal layout style */
.admin-footer {
    margin-left: 260px;
    width: calc(100% - 260px);
    padding: 15px;
    text-align: center;
    font-size: 14px;
    background: var(--card-bg);
    border-top: 3px solid var(--title-color);
    color: #007bff; /* Light mode color */
    transition: .4s ease;
    box-shadow: 0 -5px 12px rgba(0,0,0,.08);
    margin-top: 40px;
}

/* Apply when sidebar collapsed */
body.collapsed .admin-footer {
    margin-left: 85px;
    width: calc(100% - 85px);
}

/* Auto dark mode text color */
body.dark .admin-footer {
    color: #ffffff !important;
}

/* Hover smooth glow */
.admin-footer:hover {
    background: rgba(0,140,255,0.08);
    box-shadow: 0 -6px 18px rgba(0,140,255,.25);
}

/* Fade animation */
@keyframes fadeFooter {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.admin-footer {
    animation: fadeFooter .6s ease-in-out;
}

</style>

© <?= date("Y") ?> — Sistem Manajemen | <strong>Es Kristal Warid</strong> ❄️

</footer>

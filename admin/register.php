<?php
include '../koneksi.php';
include 'partials/header.php';

// HANYA ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$message = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_admin = trim($_POST['nama_admin']);
    $email      = trim($_POST['email']);
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $no_wa      = trim($_POST['no_wa']);
    $role       = trim($_POST['role']);

    if ($nama_admin === "" || $email === "" || $username === "" ||
        $password === "" || $no_wa === "" || $role === "") {

        $message = "Semua field harus diisi.";

    } else {

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("
            INSERT INTO admin (nama_admin, email, username, password, no_wa, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssssss", $nama_admin, $email, $username, $hash, $no_wa, $role);

        if ($stmt->execute()) {
            $success = "Akun berhasil ditambahkan!";
            $_POST = [];
        } else {
            $message = "Gagal menambahkan akun. Username atau email sudah digunakan.";
        }
    }
}
?>

<?php include 'partials/sidebar.php'; ?>

<style>
/* =====================================================
   PAGE LOCK (NO SCROLL GLOBAL)
===================================================== */
html, body {
    height: 100%;
    margin: 0;
    overflow: hidden;
}

/* =====================================================
   WRAPPER UTAMA (AUTO CENTER AMAN SEMUA DEVICE)
===================================================== */
.page-wrapper {
    position: fixed;
    top: var(--header-height);
    left: 280px;
    right: 0;
    bottom: var(--footer-height);

    display: flex;
    justify-content: center;
    align-items: center; /* 🔥 KUNCI STABIL */

    padding: 24px;
    box-sizing: border-box;

    transition: left .35s ease;
}

body.collapsed .page-wrapper {
    left: 100px;
}

/* =====================================================
   CARD FORM (COMPACT PREMIUM)
===================================================== */
.card-form,
.form-card {
    width: 100%;
    max-width: 640px;

    padding: 36px 40px;
    border-radius: 24px;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.92),
        rgba(255,255,255,0.82)
    );

    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    border: 1.5px solid rgba(255,255,255,0.6);

    box-shadow:
        0 28px 55px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.7);

    position: relative;
}

/* Accent Glow */
.card-form::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 160px;
    height: 160px;
    background: radial-gradient(circle, rgba(37,99,235,.18), transparent 70%);
    border-radius: 50%;
}

/* =====================================================
   DARK MODE
===================================================== */
body.dark .card-form,
body.dark .form-card {
    background: linear-gradient(
        180deg,
        rgba(18,30,60,0.95),
        rgba(10,18,36,0.92)
    );
    border: 1px solid rgba(90,169,255,0.25);
    box-shadow:
        0 24px 45px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.12);
}

/* =====================================================
   TITLE
===================================================== */
.card-form h3,
.form-card h2 {
    text-align: center;
    font-size: 24px;
    font-weight: 900;
    margin-bottom: 28px;
    letter-spacing: .5px;

    background: linear-gradient(90deg,#2563eb,#fbbf24);
    -webkit-background-clip: text;
    color: transparent;
}

/* =====================================================
   GRID
===================================================== */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px 36px;
}

/* =====================================================
   LABEL
===================================================== */
label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

body.dark label {
    color: #e5e7eb;
}

/* =====================================================
   INPUT
===================================================== */
input, select {
    width: 100%;
    padding: 11px 14px;
    border-radius: 11px;
    border: 1.6px solid #cbd5e1;
    font-size: 13.5px;

    background: rgba(255,255,255,0.92);
    transition: .3s ease;
}

body.dark input,
body.dark select {
    background: rgba(10,18,36,0.85);
    border-color: rgba(90,169,255,.35);
    color: #fff;
}

input:focus, select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.25);
    outline: none;
}

/* =====================================================
   BUTTON (BIRU + RING ORANYE)
===================================================== */
button[type="submit"],
.btn-submit {
    margin-top: 30px;
    width: 100%;
    padding: 14px;
    border-radius: 18px;

    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: #fff;

    font-size: 15px;
    font-weight: 900;

    border: 2.5px solid #fbbf24;

    box-shadow:
        0 0 0 4px rgba(251,191,36,.35),
        0 18px 30px rgba(37,99,235,.45);

    cursor: pointer;
    transition: .35s ease;
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow:
        0 0 0 6px rgba(251,191,36,.55),
        0 28px 45px rgba(37,99,235,.6);
}

/* =====================================================
   MOBILE FIX (100% STABIL)
===================================================== */
@media (max-width: 768px) {
    .page-wrapper {
        left: 0;
        padding: 16px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .card-form,
    .form-card {
        padding: 26px 22px;
    }
}




</style>

<div class="page-wrapper">

    <div class="card-form">

        <h3>Tambah Admin / Kapten</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">

            <div class="form-grid">

                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_admin" value="<?= $_POST['nama_admin'] ?? '' ?>">
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
                </div>

                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>">
                </div>

                <div>
                    <label>No WhatsApp</label>
                    <input type="text" name="no_wa" placeholder="08xxxx" value="<?= $_POST['no_wa'] ?? '' ?>">
                </div>

                <div>
                    <label>Role</label>
                    <select name="role">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" <?= (($_POST['role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="kapten" <?= (($_POST['role'] ?? '') == 'kapten') ? 'selected' : '' ?>>Kapten</option>
                    </select>
                </div>

                <div>
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password">
                        <span class="toggle-password" onclick="togglePassword()">👁</span>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn-submit">Tambah Akun</button>

        </form>

    </div>

</div>

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
}
</script>

<?php include 'partials/footer.php'; ?>

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
/* ================= PAGE LOCK ================= */
body.register-page {
    min-height: 100vh;
    overflow-x: hidden;
}

/* ================= WRAPPER ================= */
.page-wrapper {
    margin-left: 280px;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 110px;
    transition: .35s ease;
}

body.collapsed .page-wrapper {
    margin-left: 100px;
}

/* ================= CARD (GLASS PREMIUM) ================= */
.card-form {
    width: 100%;
    max-width: 700px;
    padding: 42px 46px;
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
    overflow: hidden;
}

/* Accent Glow */
.card-form::before {
    content: "";
    position: absolute;
    top: -70px;
    right: -70px;
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, rgba(37,99,235,.22), transparent 70%);
    border-radius: 50%;
}

/* ================= DARK MODE CARD ================= */
body.dark .card-form {
    background: linear-gradient(
        180deg,
        rgba(18,30,60,0.95),
        rgba(10,18,36,0.92)
    );
    border: 1px solid rgba(90,169,255,0.25);
    box-shadow:
        0 28px 55px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(90,169,255,.12);
}

/* ================= TITLE ================= */
.card-form h3 {
    text-align: center;
    font-size: 26px;
    font-weight: 900;
    letter-spacing: .6px;
    margin-bottom: 34px;

    background: linear-gradient(90deg,#2563eb,#fbbf24);
    -webkit-background-clip: text;
    color: transparent;
}

/* ================= GRID ================= */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px 48px;
}

/* ================= LABEL ================= */
.card-form label {
    display: block;
    margin-bottom: 8px;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: .3px;
    color: #0f172a;
}

body.dark .card-form label {
    color: #e5e7eb;
}

/* ================= INPUT ================= */
.card-form input,
.card-form select {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1.7px solid #cbd5e1;
    font-size: 14px;

    background: rgba(255,255,255,0.9);
    color: #0f172a;
    transition: .3s ease;
}

body.dark .card-form input,
body.dark .card-form select {
    background: rgba(10,18,36,0.85);
    border-color: rgba(90,169,255,.35);
    color: #fff;
}

.card-form input:focus,
.card-form select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,.28);
    outline: none;
}

/* ================= PASSWORD ================= */
.password-wrapper {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #64748b;
    transition: .25s;
}

.toggle-password:hover {
    color: #2563eb;
}

/* ================= BUTTON ================= */
.btn-submit {
    margin-top: 38px;
    width: 100%;
    padding: 15px;
    border-radius: 18px;
    border: none;

    background: linear-gradient(135deg,#2563eb,#1d4ed8,#fbbf24);
    color: #fff;

    font-size: 15px;
    font-weight: 900;
    letter-spacing: .6px;
    cursor: pointer;

    box-shadow: 0 18px 32px rgba(37,99,235,.35);
    transition: .35s ease;
}

.btn-submit:hover {
    transform: translateY(-3px) scale(1.01);
    box-shadow: 0 28px 48px rgba(37,99,235,.55);
}

/* ================= ALERT ================= */
.alert {
    margin-bottom: 26px;
    padding: 14px 20px;
    border-radius: 16px;
    font-weight: 800;
    text-align: center;
    letter-spacing: .3px;
}

.alert-success {
    background: linear-gradient(135deg,#dcfce7,#bbf7d0);
    color: #14532d;
}

.alert-danger {
    background: linear-gradient(135deg,#fee2e2,#fecaca);
    color: #7f1d1d;
}

/* ================= RESPONSIVE ================= */
@media(max-width: 900px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 22px;
    }

    .card-form {
        max-width: 92%;
        padding: 34px 26px;
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

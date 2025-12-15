<?php
include '../koneksi.php';
include 'partials/header.php';

/* =============================
   PROTEKSI ADMIN
============================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$message = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $no_wa    = trim($_POST['no_wa']);

    if ($username === "" || $password === "" || $no_wa === "") {
        $message = "Semua field wajib diisi.";
    } else {

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("
            UPDATE karyawan 
            SET username=?, password=?, no_wa=? 
            WHERE username IS NULL OR username=''
            LIMIT 1
        ");
        $stmt->bind_param("sss", $username, $hash, $no_wa);

        if ($stmt->execute()) {
            $success = "Akun login karyawan berhasil dibuat.";
            $_POST = [];
        } else {
            $message = "Gagal menyimpan data.";
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
    max-width: 640px;
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

/* Accent glow */
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
.card-form input {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1.7px solid #cbd5e1;
    font-size: 14px;
    margin-bottom: 22px;

    background: rgba(255,255,255,0.9);
    color: #0f172a;
    transition: .3s ease;
}

body.dark .card-form input {
    background: rgba(10,18,36,0.85);
    border-color: rgba(90,169,255,.35);
    color: #fff;
}

.card-form input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,.28);
    outline: none;
}

/* ================= PASSWORD ================= */
.password-wrap {
    position: relative;
}

.password-wrap svg {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    cursor: pointer;
    stroke: #64748b;
    transition: .25s;
}

.password-wrap svg:hover {
    stroke: #2563eb;
}

/* ================= BUTTON ================= */
.btn-submit {
    margin-top: 28px;
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
@media(max-width: 768px) {
    .card-form {
        max-width: 92%;
        padding: 34px 26px;
    }
}

</style>

<div class="page-wrapper">

    <div class="card-form">

        <h3>Aktivasi Akun Karyawan</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">

            <label>Username</label>
            <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>">

            <label>Password</label>
            <div class="password-wrap">
                <input type="password" id="password" name="password">
                <!-- ICON MATA PROFESIONAL (SVG) -->
                <svg onclick="togglePassword()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                             c4.477 0 8.268 2.943 9.542 7
                             -1.274 4.057-5.065 7-9.542 7
                             -4.477 0-8.268-2.943-9.542-7z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>

            <label>No WhatsApp</label>
            <input type="text" name="no_wa" placeholder="08xxxxxxxxxx"
                   value="<?= $_POST['no_wa'] ?? '' ?>">

            <button type="submit" class="btn-submit">
                Simpan Akun
            </button>

        </form>

    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

<?php include 'partials/footer.php'; ?>

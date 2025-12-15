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
/* ===============================
   FIX HALAMAN (NO SCROLL)
================================ */
html, body {
    height: 100%;
    overflow: hidden;
}

/* ===============================
   PAGE CENTER
================================ */
.page-wrapper {
    margin-left: 260px;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;

    /* 👉 ATUR POSISI CARD DI SINI */
    transform: translateY(-130px); /* NAIK / TURUN CARD */
}

body.collapsed .page-wrapper {
    margin-left: 85px;
}

/* ===============================
   CARD
================================ */
.card-form {
    background: var(--card-bg);
    width: 100%;
    max-width: 520px;
    padding: 42px 46px;
    border-radius: 22px;
    box-shadow: 0 25px 60px rgba(0,0,0,.15);
}

/* ===============================
   TITLE
================================ */
.card-form h3 {
    text-align: center;
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 32px;
    color: #1e3a8a;
}
body.dark .card-form h3 {
    color: #e5e7eb;
}

/* ===============================
   LABEL
================================ */
.card-form label {
    display: block;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 6px;
    color: #0f172a;
}
body.dark .card-form label {
    color: #e5e7eb;
}

/* ===============================
   INPUT
================================ */
.card-form input {
    width: 100%;
    padding: 13px 14px;
    border-radius: 10px;
    border: 1.8px solid #cbd5e1;
    font-size: 14px;
    margin-bottom: 20px; /* 👉 JARAK ANTAR INPUT */
    background: #fff;
    color: #0f172a;
}

body.dark .card-form input {
    background: #0f1729;
    color: #fff;
    border-color: #334155;
}

.card-form input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.25);
    outline: none;
}

/* ===============================
   PASSWORD WRAPPER
================================ */
.password-wrap {
    position: relative;
}

.password-wrap svg {
    position: absolute;
    right: -20px;
    top: 40%;
    transform: translateY(-50%);
    width: 30px;
    height: 22px;
    cursor: pointer;
    stroke: #64748b;
}
.password-wrap svg:hover {
    stroke: #2563eb;
}

/* ===============================
   BUTTON
================================ */
.btn-submit {
    width: 100%;
    padding: 15px;
    border-radius: 16px;
    border: none;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: white;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    margin-top: 10px;
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(37,99,235,.35);
}

/* ===============================
   ALERT
================================ */
.alert {
    margin-bottom: 22px;
    padding: 14px 18px;
    border-radius: 14px;
    font-weight: 700;
    text-align: center;
}
.alert-success {
    background: #dcfce7;
    color: #166534;
}
.alert-danger {
    background: #fee2e2;
    color: #991b1b;
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

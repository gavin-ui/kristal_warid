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
html, body {
    height: 100%;
    overflow: hidden;
}

/* ================= WRAPPER ================= */
.page-wrapper {
    margin-left: 260px;
    height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    transform: translateY(-20px); /* CARD NAIK */
    transition: .3s;
}

body.collapsed .page-wrapper {
    margin-left: 85px;
}

/* ================= CARD ================= */
.card-form {
    background: var(--card-bg);
    width: 100%;
    max-width: 780px;
    padding: 45px 50px;
    border-radius: 22px;
    box-shadow: 0 25px 55px rgba(0,0,0,.15);
}

/* ================= TITLE ================= */
.card-form h3 {
    text-align: center;
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 35px;
    color: #1e3a8a;
}

body.dark .card-form h3 {
    color: #e5e7eb;
}

/* ================= GRID ================= */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 60px; /* JARAK RAPI */
}

/* ================= LABEL ================= */
.card-form label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}

body.dark .card-form label {
    color: #e5e7eb;
}

/* ================= INPUT ================= */
.card-form input,
.card-form select {
    width: 100%;
    padding: 13px 15px;
    border-radius: 12px;
    border: 1.8px solid #cbd5e1;
    font-size: 14px;
    background: #fff;
    color: #0f172a;
}

body.dark .card-form input,
body.dark .card-form select {
    background: #0f1729;
    color: #fff;
    border-color: #334155;
}

.card-form input:focus,
.card-form select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.25);
    outline: none;
}

/* ================= PASSWORD ================= */
.password-wrapper {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #64748b;
    user-select: none;
}

.toggle-password:hover {
    color: #2563eb;
}

/* ================= BUTTON ================= */
.btn-submit {
    margin-top: 34px;
    width: 100%;
    padding: 15px;
    border-radius: 16px;
    border: none;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: #fff;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    transition: .3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(37,99,235,.4);
}

/* ================= ALERT ================= */
.alert {
    margin-bottom: 25px;
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

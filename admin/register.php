<?php
include '../koneksi.php';
session_start();

// HANYA ADMIN YANG BOLEH AKSES
if (!isset($_SESSION['admin_login']) || $_SESSION['role'] !== 'admin') {
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

<?php include 'partials/header.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<style>
/* ===========================
   PAGE CONTENT
=========================== */
.page-content {
    margin-left: 260px;
    padding: 100px 40px 60px;
    transition: .3s;
}

body.collapsed .page-content {
    margin-left: 85px;
}

/* ===========================
   CARD FORM WRAPPER
=========================== */
.card-form {
    background: var(--card-bg);
    padding: 35px;
    border-radius: 18px;
    max-width: 900px;
    margin: auto;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    color: var(--text-color);
    transition: .3s ease;
}

/* ===========================
   TITLE
=========================== */
.card-form h3 {
    font-weight: bold;
    font-size: 24px;
    color: var(--title-color);
    border-bottom: 2px solid var(--title-color);
    padding-bottom: 10px;
    margin-bottom: 25px;
}

/* ===========================
   FORM GRID
=========================== */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* ===========================
   LABEL
=========================== */
.card-form label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    transition: .3s;
}

/* MODE CERAH – TEXT BLUE LIGHT */
body:not(.dark) .card-form label,
body:not(.dark) .card-form h3,
body:not(.dark) .card-form {
    color: #187bcd !important; /* biru muda */
}

/* ===========================
   INPUT & SELECT
=========================== */
.card-form input,
.card-form select {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #cdd5df;
    width: 100%;
    background: var(--card-bg);
    color: var(--text-color);
    transition: .25s;
}

.card-form input:focus,
.card-form select:focus {
    border-color: var(--title-color);
    box-shadow: 0 0 0 2px rgba(0,123,255,.25);
    outline: none;
}

/* ===========================
   BUTTON
=========================== */
.btn-submit {
    background: var(--accent);
    border: none;
    padding: 13px;
    font-weight: bold;
    border-radius: 12px;
    color: #fff;
    width: 100%;
    cursor: pointer;
    margin-top: 15px;
    font-size: 16px;
    transition: .25s;
}

.btn-submit:hover {
    background: #e79a00;
}

/* ===========================
   ALERTS
=========================== */
.alert {
    border-radius: 10px;
    font-weight: 600;
    padding: 12px 15px;
}
</style>



<div class="page-content">

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
                    <input type="text" name="nama_admin" 
                           value="<?= $_POST['nama_admin'] ?? '' ?>">
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" 
                           value="<?= $_POST['email'] ?? '' ?>">
                </div>

                <div>
                    <label>Username</label>
                    <input type="text" name="username" 
                           value="<?= $_POST['username'] ?? '' ?>">
                </div>

                <div>
                    <label>No WhatsApp</label>
                    <input type="text" name="no_wa" placeholder="08xxxx"
                           value="<?= $_POST['no_wa'] ?? '' ?>">
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
                    <input type="password" name="password">
                </div>

            </div>

            <button type="submit" class="btn-submit">Tambah Akun</button>

        </form>

    </div>

</div>


<?php include 'partials/footer.php'; ?>
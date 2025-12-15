<?php
include '../koneksi.php';
include 'partials/header.php'; // header sudah start session

/* =============================
   PROTEKSI ADMIN
============================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$message = "";
$success = "";

/* =============================
   AUTO USERNAME
============================= */
function generateUsername($nama) {
    $nama = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
    return substr($nama, 0, 8) . rand(100,999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama   = trim($_POST['nama']);
    $no_wa  = trim($_POST['no_wa']);

    if ($nama === "" || $no_wa === "") {
        $message = "Semua field wajib diisi.";
    } else {

        $username = generateUsername($nama);
        $password_plain = "karyawan123"; // password default
        $password = password_hash($password_plain, PASSWORD_BCRYPT);
        $role = "karyawan";

        $stmt = $conn->prepare("
            INSERT INTO karyawan (nama, username, password, no_wa, role)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss", $nama, $username, $password, $no_wa, $role);

        if ($stmt->execute()) {
            $success = "Akun karyawan berhasil dibuat!<br>
                        <b>Username:</b> $username <br>
                        <b>Password:</b> $password_plain";
            $_POST = [];
        } else {
            $message = "Gagal menyimpan data.";
        }
    }
}
?>

<?php include 'partials/header.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<style>
.page-content {
    margin-left: 260px;
    padding: 100px 40px;
}
.card-form {
    background: var(--card-bg);
    padding: 35px;
    border-radius: 18px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 8px 25px rgba(0,0,0,.12);
}
.card-form h3 {
    border-bottom: 2px solid var(--title-color);
    padding-bottom: 10px;
    margin-bottom: 25px;
}
.card-form label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}
.card-form input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.btn-submit {
    background: var(--accent);
    border: none;
    padding: 13px;
    border-radius: 12px;
    width: 100%;
    font-weight: bold;
    color: white;
    margin-top: 15px;
    cursor: pointer;
}
.alert {
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    margin-bottom: 15px;
}
</style>

<div class="page-content">
<div class="card-form">

    <h3>Register Karyawan</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= $_POST['nama'] ?? '' ?>">

        <label>No WhatsApp</label>
        <input type="text" name="no_wa" value="<?= $_POST['no_wa'] ?? '' ?>">

        <button type="submit" class="btn-submit">Buat Akun Karyawan</button>

    </form>

</div>
</div>

<?php include 'partials/footer.php'; ?>

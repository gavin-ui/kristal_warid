<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../koneksi.php';

// ==== KONFIGURASI ====
$OTP_TTL = 10 * 60;
$OTP_MIN_INTERVAL = 60;
$FONNTE_TOKEN = "LtRSv7gYViSznbJ8GnU1";
// ======================

$error = "";
$success = "";

// ========= FONNTE OTP =========
function send_otp_wa($nomor, $otp, $token) {
    $message = "Kode OTP reset password Anda adalah: *$otp*\nBerlaku 10 menit.\n\nEs Kristal Warid";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "target" => $nomor,
            "message" => $message
        ],
        CURLOPT_HTTPHEADER => ["Authorization: $token"],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response ? true : false;
}


// ===============================================
// =============== LOGIN PROCESS =================
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $error = "Isi username dan password.";
    } else {

        // =====================================================
        // 1. LOGIN ADMIN UTAMA (HARDCODE)
        // =====================================================
        if ($username === "admin") {

            $adminPassword = "admin123";

            if ($password === $adminPassword) {

                $_SESSION['role'] = "admin";
                $_SESSION['username'] = "admin";
                $_SESSION['nama_admin'] = "Administrator";

                header("Location: ../admin/index.php");
                exit;

            } else {
                $error = "Password admin salah.";
            }
        }

        // =====================================================
        // 2. LOGIN ADMIN / KAPTEN DATABASE
        // =====================================================
        $stmt = $conn->prepare("
            SELECT id_admin, username, password, nama_admin, role 
            FROM admin 
            WHERE username = ?
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {

            $row = $res->fetch_assoc();

            if (password_verify($password, $row['password'])) {

                // ADMIN
                if ($row['role'] === "admin") {
                    $_SESSION['role'] = "admin";
                    $_SESSION['id_admin'] = $row['id_admin'];
                    $_SESSION['nama_admin'] = $row['nama_admin'];
                    $_SESSION['username'] = $row['username'];

                    header("Location: ../admin/index.php");
                    exit;
                }

                // KAPTEN
                if ($row['role'] === "kapten") {
                    $_SESSION['role'] = "kapten";
                    $_SESSION['id_admin'] = $row['id_admin'];
                    $_SESSION['nama_admin'] = $row['nama_admin'];
                    $_SESSION['username'] = $row['username'];

                    header("Location: ../kapten/index.php");
                    exit;
                }
            }
        }

        // =====================================================
        // 3. LOGIN KARYAWAN (TAMBAHAN BARU)
        // =====================================================
        $stmt2 = $conn->prepare("
            SELECT id_karyawan, nama_karyawan, nomor_karyawan, divisi, username, password 
            FROM karyawan 
            WHERE username = ?
        ");
        $stmt2->bind_param("s", $username);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        if ($res2->num_rows === 1) {

            $kr = $res2->fetch_assoc();

            if (password_verify($password, $kr['password'])) {

                // SET SESSION KARYAWAN
                $_SESSION['role'] = "karyawan";
                $_SESSION['id_karyawan'] = $kr['id_karyawan'];
                $_SESSION['nama_karyawan'] = $kr['nama_karyawan'];
                $_SESSION['nomor_karyawan'] = $kr['nomor_karyawan'];
                $_SESSION['divisi'] = $kr['divisi'];
                $_SESSION['username'] = $kr['username'];

                header("Location: ../karyawan/index.php");
                exit;
            }
        }

        $error = "Username atau password salah!";
    }
}


// ===============================================
// ================ OTP REQUEST ==================
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'send_otp') {

    $no_wa = trim($_POST['no_wa']);

    $stmt = $conn->prepare("SELECT id_admin FROM admin WHERE no_wa = ?");
    $stmt->bind_param("s", $no_wa);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        if (!empty($_SESSION['otp_last_sent_time']) &&
            (time() - $_SESSION['otp_last_sent_time']) < $OTP_MIN_INTERVAL) {

            $error = "Tunggu 1 menit untuk meminta OTP lagi.";

        } else {

            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $_SESSION['reset_no_wa'] = $no_wa;
            $_SESSION['reset_otp'] = $otp;
            $_SESSION['reset_expires'] = time() + $OTP_TTL;
            $_SESSION['otp_last_sent_time'] = time();

            if (send_otp_wa($no_wa, $otp, $FONNTE_TOKEN)) {
                header("Location: login.php?action=verify");
                exit;
            } else {
                $error = "Gagal mengirim OTP.";
            }
        }

    } else {
        $error = "Nomor WA tidak terdaftar.";
    }

    $stmt->close();
}


// ===============================================
// ================ VERIFIKASI OTP ===============
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'verify_otp') {

    $otp = trim($_POST['otp']);

    if ($otp === "") {
        $error = "Masukkan OTP.";
    } elseif (!isset($_SESSION['reset_otp'])) {
        $error = "Tidak ada permintaan reset aktif.";
    } elseif (time() > $_SESSION['reset_expires']) {
        $error = "OTP telah kadaluarsa.";
    } elseif ($otp !== $_SESSION['reset_otp']) {
        $error = "OTP salah.";
    } else {

        $_SESSION['otp_verified'] = true;
        header("Location: login.php?action=newpass");
        exit;
    }
}


// ===============================================
// ============== SET PASSWORD BARU ==============
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'set_new_password') {

    $new = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if ($new === "" || $confirm === "") {
        $error = "Semua field wajib diisi.";
    } elseif ($new !== $confirm) {
        $error = "Konfirmasi tidak sama.";
    } else {

        $hash = password_hash($new, PASSWORD_DEFAULT);
        $no_wa = $_SESSION['reset_no_wa'];

        $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE no_wa = ?");
        $stmt->bind_param("ss", $hash, $no_wa);

        if ($stmt->execute()) {
            $success = "Password berhasil diganti. Silakan login.";

            unset($_SESSION['reset_no_wa'], $_SESSION['reset_otp'], $_SESSION['reset_expires'], $_SESSION['otp_verified']);
        } else {
            $error = "Gagal mengganti password.";
        }

        $stmt->close();
    }
}

$action = $_GET['action'] ?? 'login';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Es Kristal Warid</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* FULL BACKGROUND */
body {
    height: 100vh; 
    margin: 0; 
    display:flex; 
    justify-content:center; 
    align-items:center;
    background: radial-gradient(circle at center,
        rgba(255,255,255,0.85) 0%,
        rgba(255,255,255,0.65) 35%,
        rgba(0, 174, 239, 0.9) 100%);
    background-color:#00AEEF;
    font-family: Arial, sans-serif;
}

/* GLASS CARD */
.card-glass {
    width: 420px; 
    padding:35px; 
    border-radius:20px;
    background:rgba(255,255,255,0.35); 
    backdrop-filter:blur(10px);
    box-shadow:0 8px 25px rgba(0,0,0,0.15); 
    animation:fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

/* BUTTON */
.btn-primary {
    background:#00AEEF;
    border:none;
    font-weight:bold;
}
.btn-primary:hover {
    background:#008FC7;
}

/* SMALL TEXT */
.small-muted {
    color:rgba(0,0,0,0.6);
}

/* LABELS */
.form-label {
    font-weight:600;
    color:#004b66;
}

/* INPUT */
.form-control {
    border-radius:10px;
}

/* BACK BUTTON */
.back-btn {
    position:absolute; 
    top:20px; 
    left:20px; 
    text-decoration:none;
    display:flex; 
    align-items:center; 
}
</style>
</head>
<body>

<!-- BACK BUTTON -->
<a href="../home/index.php" class="back-btn">
    <svg width="55" height="55" viewBox="0 0 24 24" fill="#004b66">
        <path d="M10 17v-3H3v-4h7V7l5 5-5 5zm-7-15h11v2H5v14h9v2H3V2z"/>
    </svg>
</a>

<div class="card-glass">

<?php if ($action === 'forgot'): ?>

    <h3 class="text-center mb-4" style="color:#00AEEF;">Lupa Password</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>

    <form method="post">
        <input type="hidden" name="action" value="send_otp">

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="no_wa" class="form-control" placeholder="08xxxx" required>
        </div>

        <div class="d-grid"><button class="btn btn-primary py-2">Kirim OTP</button></div>
    </form>

    <p class="mt-3 text-center small-muted">
        Kembali ke <a href="login.php" style="color:#00AEEF;">Login</a>
    </p>

<?php elseif ($action === 'verify'): ?>

    <h3 class="text-center mb-4" style="color:#00AEEF;">Verifikasi OTP</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>

    <form method="post">
        <input type="hidden" name="action" value="verify_otp">

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['reset_no_wa'] ?? '') ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Kode OTP</label>
            <input type="text" name="otp" maxlength="6" pattern="\d{6}" class="form-control" required>
        </div>

        <div class="d-grid"><button class="btn btn-primary py-2">Verifikasi</button></div>
    </form>

    <p class="mt-3 text-center small-muted">
        <a href="login.php" style="color:#00AEEF;">Kembali ke Login</a>
    </p>

<?php elseif ($action === 'newpass'): ?>

    <h3 class="text-center mb-4" style="color:#00AEEF;">Password Baru</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success py-2"><?=$success?></div><?php endif; ?>

    <form method="post">
        <input type="hidden" name="action" value="set_new_password">

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <div class="d-grid"><button class="btn btn-primary py-2">Ganti Password</button></div>
    </form>

    <p class="mt-3 text-center small-muted">
        <a href="login.php" style="color:#00AEEF;">Kembali ke Login</a>
    </p>

<?php else: ?>

    <h3 class="text-center mb-4" style="color:#00AEEF;">Admin Es Kristal Warid</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="hidden" name="action" value="login">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autocomplete="off">
        </div>

        <div class="mb-3 position-relative">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="off">
        </div>

        <div class="d-grid"><button class="btn btn-primary py-2">Login</button></div>
    </form>

    <div class="mt-3 text-center">
        <a href="?action=forgot" style="color:#00AEEF; font-weight:600;">Lupa password?</a>
    </div>

<?php endif; ?>

</div>

</body>
</html>

<?php
// login.php FINAL — Login + Lupa Password via OTP WhatsApp + Reset Password
session_start();
include '../koneksi.php';

// === KONFIGURASI ===
$OTP_TTL = 10 * 60; // 10 menit
$OTP_MIN_INTERVAL = 60; // jeda minimal 1 menit antar OTP
$FONNTE_TOKEN = "NbMNneKGkPUn8bxjBFY6";
// ====================

$error = "";
$info  = "";
$success = "";

// ===== Fungsi Kirim OTP via Fonnte =====
function send_otp_wa($nomor, $otp, $token) {
    $message = "Kode OTP reset password Anda adalah: *$otp*\nBerlaku 10 menit.\n\nEs Kristal Warid";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array(
            "target" => $nomor,
            "message" => $message
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response ? true : false;
}

// ============== LOGIN ===============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Isi username dan password.";
    } else {
        $stmt = $conn->prepare("SELECT id_admin, username, password, nama_admin, role 
                                FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_login'] = true;
                $_SESSION['id_admin'] = $row['id_admin'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['nama_admin'] = $row['nama_admin'];
                $_SESSION['role'] = $row['role'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password salah.";
                $_SESSION['last_failed_username'] = $username;
            }
        } else {
            $error = "Username tidak ditemukan.";
        }

        $stmt->close();
    }
}

// =========== MINTA OTP RESET PASSWORD ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'send_otp') {

    $no_wa = trim($_POST['no_wa'] ?? '');

    if ($no_wa === '') {
        $error = "Masukkan nomor WhatsApp.";
    } else {
        $stmt = $conn->prepare("SELECT id_admin FROM admin WHERE no_wa = ?");
        $stmt->bind_param("s", $no_wa);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {

            $now = time();
            if (!empty($_SESSION['otp_last_sent_time']) && ($now - $_SESSION['otp_last_sent_time']) < $OTP_MIN_INTERVAL) {
                $error = "Tunggu 1 menit untuk meminta OTP lagi.";
            } else {
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $_SESSION['reset_no_wa'] = $no_wa;
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_expires'] = time() + $OTP_TTL;
                $_SESSION['otp_last_sent_time'] = time();

                $sent = send_otp_wa($no_wa, $otp, $FONNTE_TOKEN);

                if ($sent) {
                    $info = "OTP telah dikirim ke WhatsApp <b>$no_wa</b>";
                } else {
                    $error = "Gagal mengirim OTP.";
                }
            }

        } else {
            $error = "Nomor WA tidak terdaftar.";
        }

        $stmt->close();
    }
}

// =========== RESET PASSWORD =============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'reset_password') {

    $otp = trim($_POST['otp'] ?? '');
    $newpw = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if ($otp === '' || $newpw === '' || $confirm === '') {
        $error = "Semua field wajib diisi.";
    } elseif ($newpw !== $confirm) {
        $error = "Konfirmasi password tidak sama.";
    } elseif (empty($_SESSION['reset_otp']) || empty($_SESSION['reset_no_wa'])) {
        $error = "Tidak ada permintaan reset aktif.";
    } elseif (time() > ($_SESSION['reset_expires'] ?? 0)) {
        $error = "OTP telah kadaluarsa.";
    } elseif (!hash_equals($_SESSION['reset_otp'], $otp)) {
        $error = "OTP salah.";
    } else {

        $no_wa = $_SESSION['reset_no_wa'];
        $hash = password_hash($newpw, PASSWORD_DEFAULT);

        $upd = $conn->prepare("UPDATE admin SET password = ? WHERE no_wa = ?");
        $upd->bind_param("ss", $hash, $no_wa);

        if ($upd->execute()) {
            $success = "Password berhasil diganti. Silakan login.";
            unset($_SESSION['reset_no_wa'], $_SESSION['reset_otp'], $_SESSION['reset_expires']);
        } else {
            $error = "Gagal mengganti password.";
        }

        $upd->close();
    }
}

$action = $_GET['action'] ?? 'login';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login Admin — Es Kristal Warid</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* TIDAK DIUBAH SAMA SEKALI */
body {
    height: 100vh; margin: 0; display:flex; justify-content:center; align-items:center;
    background: radial-gradient(circle at center,
        rgba(255,255,255,0.85) 0%,
        rgba(255,255,255,0.65) 35%,
        rgba(0, 174, 239, 0.9) 100%);
    background-color:#00AEEF;
}
.card-glass {
    width:400px; padding:30px; border-radius:20px;
    background:rgba(255,255,255,0.35); backdrop-filter:blur(10px);
    box-shadow:0 8px 25px rgba(0,0,0,0.15); animation:fadeIn 0.8s ease;
}
@keyframes fadeIn { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }
.btn-primary { background:#00AEEF; border:none; }
.btn-primary:hover { background:#008FC7; }
.small-muted { color:rgba(0,0,0,0.6); }
</style>
</head>
<body>

<div class="card-glass">

<?php if ($action === 'forgot'): ?>
    <h3 class="text-center mb-3" style="color:#00AEEF;">Lupa Password</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>
    <?php if ($info): ?><div class="alert alert-info py-2"><?=$info?></div><?php endif; ?>

    <form method="post">
        <input type="hidden" name="action" value="send_otp">

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="no_wa" class="form-control" placeholder="08xxxx" required>
        </div>

        <div class="d-grid"><button class="btn btn-primary">Kirim OTP</button></div>
    </form>

    <p class="mt-3 text-center small-muted">
        Kembali ke <a href="login.php" style="color:#00AEEF;">Login</a>
    </p>

<?php elseif ($action === 'verify'): ?>
    <h3 class="text-center mb-3" style="color:#00AEEF;">Reset Password</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success py-2"><?=$success?></div><?php endif; ?>

    <form method="post">
        <input type="hidden" name="action" value="reset_password">

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" class="form-control" value="<?=htmlspecialchars($_SESSION['reset_no_wa']??'')?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Kode OTP</label>
            <input type="text" name="otp" maxlength="6" pattern="\d{6}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <div class="d-grid"><button class="btn btn-primary">Ganti Password</button></div>
    </form>

    <p class="mt-3 text-center small-muted">
        <a href="login.php" style="color:#00AEEF;">Kembali ke Login</a>
    </p>

<?php else: ?>
    <h3 class="text-center mb-3" style="color:#00AEEF;">Admin Es Kristal Warid</h3>

    <?php if ($error): ?><div class="alert alert-danger py-2"><?=$error?></div><?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="hidden" name="action" value="login">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>

        <div class="mb-3 position-relative">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="d-grid"><button class="btn btn-primary">Login</button></div>
    </form>

    <div class="mt-3 text-center">
        <small class="small-muted">Belum punya akun? 
            <a href="register.php" style="color:#00AEEF;">Daftar</a>
        </small>
    </div>

    <?php if (!empty($_SESSION['last_failed_username'])): ?>
    <div class="mt-3 text-center">
        <a href="?action=forgot" style="color:#00AEEF; font-weight:600;">Lupa password?</a>
    </div>
    <?php endif; ?>

<?php endif; ?>

</div>
</body>
</html>

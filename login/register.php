<?php
include '../koneksi.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_admin = trim($_POST['nama_admin']);
    $email      = trim($_POST['email']);
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $no_wa      = trim($_POST['no_wa']); // Tambahan WA

    if ($nama_admin === "" || $email === "" || $username === "" || $password === "" || $no_wa === "") {
        $message = "Semua field harus diisi.";
    } else {

        // Enkripsi password
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Simpan dengan no_wa
        $stmt = $conn->prepare("
            INSERT INTO admin (nama_admin, email, username, password, no_wa, role) 
            VALUES (?, ?, ?, ?, ?, 'admin')
        ");
        $stmt->bind_param("sssss", $nama_admin, $email, $username, $hash, $no_wa);

        if ($stmt->execute()) {
            header("Location: login.php?success=1");
            exit;
        } else {
            $message = "Gagal mendaftar. Username atau email mungkin sudah digunakan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;

        background: radial-gradient(circle at center,
            rgba(255,255,255,0.85) 0%,
            rgba(255,255,255,0.65) 35%,
            rgba(0,174,239,0.9) 100%
        );
        background-color: #00AEEF;
    }
    .card-glass {
        width: 400px;
        padding: 30px;
        border-radius: 20px;
        background: rgba(255,255,255,0.35);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .btn-primary { background-color: #00AEEF; border: none; }
    .btn-primary:hover { background-color: #008FC7; }
    .toggle-eye { cursor: pointer; width: 22px; }
</style>
</head>

<body>

<div class="card-glass">
    <h3 class="text-center mb-3" style="color:#00AEEF;">Daftar Admin</h3>

    <?php if ($message): ?>
        <div class="alert alert-warning py-2"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_admin" class="form-control" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">No WhatsApp</label>
            <input type="text" name="no_wa" class="form-control" required placeholder="08xxxxxxx" autocomplete="off">
        </div>

        <div class="mb-3 position-relative">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">

            <span class="position-absolute" style="right:10px; top:38px;" onclick="togglePassword()">
                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="toggle-eye" viewBox="0 0 24 24" stroke="black" fill="none">
                    <path stroke-width="2"
                        d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12s-4 7.5-10.5 7.5S1.5 12 1.5 12z"/>
                    <circle cx="12" cy="12" r="3" stroke-width="2"></circle>
                </svg>
                <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" class="toggle-eye d-none" viewBox="0 0 24 24" stroke="black" fill="none">
                    <path stroke-width="2"
                        d="M3 3l18 18M10.7 10.7a3 3 0 013.6 3.6M6.1 6.1C3.9 7.8 2.3 10.2 1.5 12c.8 1.8 2.4 4.2 4.6 5.9M17.9 17.9c2.2-1.7 3.8-4.1 4.6-5.9-.8-1.8-2.4-4.2-4.6-5.9"/>
                </svg>
            </span>
        </div>

        <button class="btn btn-primary w-100 mt-2" type="submit">Daftar</button>
    </form>

    <p class="text-center mt-3">
        Sudah punya akun? 
        <a href="login.php" style="color:#00AEEF; text-decoration:none;">Login</a>
    </p>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    const open = document.getElementById("eyeOpen");
    const close = document.getElementById("eyeClose");

    if (pass.type === "password") {
        pass.type = "text";
        open.classList.add("d-none");
        close.classList.remove("d-none");
    } else {
        pass.type = "password";
        open.classList.remove("d-none");
        close.classList.add("d-none");
    }
}
</script>

</body>
</html>

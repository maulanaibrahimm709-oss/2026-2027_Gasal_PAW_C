<?php
ob_start();
require_once __DIR__ . '/helpers/auth.php';        // memulai session
require_once __DIR__ . '/conn.php';
$url = isset($_GET['url']) && $_GET['url'] !== '' ? $_GET['url'] : '/';

// --- Proses login/logout (sebelum ada output HTML) ---
if ($url === '/api/login')  { require __DIR__ . '/actions/auth/apiLogin.php';  exit; }
if ($url === '/api/logout') { require __DIR__ . '/actions/auth/apiLogout.php'; exit; }

// --- Proteksi halaman ---
if (!isLoggedIn()) {                               // belum login -> hanya boleh ke halaman login
    if ($url !== '/login') { header('Location: index.php?url=/login'); exit; }
    require __DIR__ . '/views/login.php';
    exit;
}
if ($url === '/login') { header('Location: index.php?url=/'); exit; }   // sudah login -> tidak boleh ke login

// Cegah halaman tersimpan di cache (tombol Back setelah logout)
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

if (!bolehAkses($url)) { http_response_code(403); $url = '/403'; }       // hak akses per level
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="?url=/">Home</a>
        <?php if (userLevel() === 1): ?>
            <div class="dropdown">
                <a href="#" class="dropbtn">Data Master &#9662;</a>
                <div class="dropdown-menu">
                    <a href="?url=/barang">Data Barang</a>
                    <a href="?url=/supplier">Data Supplier</a>
                    <a href="?url=/pelanggan">Data Pelanggan</a>
                    <a href="?url=/user">Data User</a>
                </div>
            </div>
        <?php endif; ?>
        <a href="?url=/transaksi">Transaksi</a>
        <a href="?url=/laporan">Laporan</a>
        <div class="dropdown dropdown-right">
            <a href="#" class="dropbtn"><?= htmlspecialchars(userNama()); ?> &#9662;</a>
            <div class="dropdown-menu">
                <a href="?url=/api/logout">Logout</a>
            </div>
        </div>
    </nav>
    <main>
        <?php
            require __DIR__ . '/proses.php';
        ?>
    </main>
</body>
</html>

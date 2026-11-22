<?php
// ===== Session & keamanan (Modul 8) =====
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

function isLoggedIn(): bool { return isset($_SESSION['user']['id']); }
function userId(): int      { return (int)($_SESSION['user']['id'] ?? 0); }
function userNama(): string { return (string)($_SESSION['user']['nama'] ?? ''); }
function userLevel(): int   { return (int)($_SESSION['user']['level'] ?? 0); }
function namaLevel(int $l): string { return $l === 1 ? 'Owner' : ($l === 2 ? 'Kasir' : '-'); }

// Hak akses: Data Master (barang, supplier, pelanggan, user) hanya untuk level 1 (owner)
function bolehAkses(string $url): bool {
    foreach (['/barang', '/supplier', '/pelanggan', '/user'] as $p) {
        if ($url === $p || str_starts_with($url, $p . '/') || str_starts_with($url, '/api' . $p . '/')) {
            return userLevel() === 1;
        }
    }
    return true;
}

function setFlash(string $type, string $text): void { $_SESSION['flash'] = ['type' => $type, 'text' => $text]; }
function tampilFlash(): void {
    if (empty($_SESSION['flash'])) return;
    $f = $_SESSION['flash']; unset($_SESSION['flash']);
    $cls = $f['type'] === 'error' ? 'pesan pesan-error' : 'pesan pesan-ok';
    echo '<p class="' . $cls . '">' . htmlspecialchars($f['text']) . '</p>';
}

// Membuat tabel user, supplier, pelanggan bila belum ada (tidak menyentuh tabel Modul 7)
function siapkanTabelAuth(mysqli $conn): void {
    $conn->query("CREATE TABLE IF NOT EXISTS `user` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(30) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        level TINYINT NOT NULL COMMENT '1=owner, 2=kasir'
    ) ENGINE=InnoDB");
    $conn->query("CREATE TABLE IF NOT EXISTS supplier (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_supplier VARCHAR(100) NOT NULL,
        alamat VARCHAR(200) NOT NULL DEFAULT '',
        telepon VARCHAR(20) NOT NULL DEFAULT ''
    ) ENGINE=InnoDB");
    $conn->query("CREATE TABLE IF NOT EXISTS pelanggan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_pelanggan VARCHAR(100) NOT NULL,
        alamat VARCHAR(200) NOT NULL DEFAULT '',
        telepon VARCHAR(20) NOT NULL DEFAULT ''
    ) ENGINE=InnoDB");
    // Migrasi: hubungkan transaksi dengan pelanggan & kasir (untuk database lama)
    try {
        foreach (['pelanggan_id', 'user_id'] as $col) {
            $r = $conn->query("SHOW COLUMNS FROM transaksi LIKE '$col'");
            if ($r && $r->num_rows === 0) $conn->query("ALTER TABLE transaksi ADD COLUMN $col INT NULL");
        }
    } catch (Exception $e) { /* tabel transaksi belum ada: dibuat oleh database.php */ }
    $r = $conn->query("SELECT COUNT(*) AS c FROM `user`")->fetch_assoc();
    if ((int)$r['c'] === 0) {   // akun awal, password: 123456
        $hash = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO `user` (nama, username, password, level) VALUES (?, ?, ?, ?)");
        foreach ([['Budi', 'owner', 1], ['Wati', 'kasir', 2]] as [$nama, $uname, $lv]) {
            $stmt->bind_param('sssi', $nama, $uname, $hash, $lv);
            $stmt->execute();
        }
    }
}

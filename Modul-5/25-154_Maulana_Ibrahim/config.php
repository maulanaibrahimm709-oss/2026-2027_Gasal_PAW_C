<?php
// Isi DB_PASS dengan password akun root MySQL Laragon jika memang menggunakan password.
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', ''); // Kosong hanya jika akun root Laragon Anda tanpa password.
define('DB_NAME', 'penjualan');
define('DB_PORT', 3306);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
  $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
  $db->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
  http_response_code(500);
  exit('<h2>Koneksi database gagal</h2><p>Pastikan MySQL Laragon aktif, database penjualan sudah diimpor, port 3306 benar, dan DB_PASS pada config.php sesuai password akun root.</p><p>Detail: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>');
}

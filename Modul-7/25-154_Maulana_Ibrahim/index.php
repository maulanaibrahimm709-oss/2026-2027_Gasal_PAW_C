<?php
ob_start();
require_once __DIR__ . '/conn.php';
$url = isset($_GET['url']) && $_GET['url'] !== '' ? $_GET['url'] : '/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="?url=/">Home</a> |
        <a href="?url=/transaksi">Transaksi</a> |
        <a href="?url=/transaksiDetail">Detail Transaksi</a> |
        <a href="?url=/barang">Barang</a> |
        <a href="?url=/laporan">Laporan</a>
    </nav>
    <main>
		<?php
			require __DIR__ . '/proses.php';
		?>
    </main>
</body>
</html>

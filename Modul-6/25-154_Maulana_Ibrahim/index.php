<?php
require 'koneksi.php';
$pesan = $_GET['pesan'] ?? '';
$transaksi = mysqli_query($koneksi, "SELECT t.*, p.nama AS nama_pelanggan
    FROM transaksi t LEFT JOIN pelanggan p ON p.id = t.pelanggan_id
    ORDER BY t.id ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Master Detail Transaksi</title>
</head>

<body>
    <h1>Master-Detail Transaksi Penjualan</h1>
    <?php if ($pesan !== ''): ?><p><strong><?= htmlspecialchars($pesan) ?></strong></p><?php endif; ?>
    <p><a href="barang.php">Kelola Barang</a> | <a href="transaksi_baru.php">Tambah Transaksi</a></p>
    <h2>Data Transaksi (Master)</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Detail</th>
        </tr>
        <?php while ($t = mysqli_fetch_assoc($transaksi)): ?>
            <tr>
                <td><?= (int)$t['id'] ?></td>
                <td><?= htmlspecialchars($t['waktu_transaksi']) ?></td>
                <td><?= htmlspecialchars($t['nama_pelanggan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($t['keterangan']) ?></td>
                <td>Rp <?= number_format((int)$t['total'], 0, ',', '.') ?></td>
                <td><a href="detail.php?id=<?= (int)$t['id'] ?>">Lihat / tambah detail</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>
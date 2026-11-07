<?php
require 'koneksi.php';
if (isset($_GET['hapus'])) {
    $id = filter_input(INPUT_GET, 'hapus', FILTER_VALIDATE_INT);
    if (!$id) {
        header('Location: barang.php?pesan=ID barang tidak valid');
        exit;
    }
    $cek = mysqli_prepare($koneksi, "SELECT COUNT(*) AS jumlah FROM transaksi_detail WHERE barang_id=?");
    mysqli_stmt_bind_param($cek, "i", $id);
    mysqli_stmt_execute($cek);
    $jumlah = (int)mysqli_fetch_assoc(mysqli_stmt_get_result($cek))['jumlah'];
    if ($jumlah > 0) {
        header('Location: barang.php?pesan=' . urlencode('Barang tidak dapat dihapus karena masih digunakan pada transaksi.'));
        exit;
    }
    $hapus = mysqli_prepare($koneksi, "DELETE FROM barang WHERE id=?");
    mysqli_stmt_bind_param($hapus, "i", $id);
    mysqli_stmt_execute($hapus);
    header('Location: barang.php?pesan=' . urlencode(mysqli_stmt_affected_rows($hapus) ? 'Barang berhasil dihapus.' : 'Barang tidak ditemukan.'));
    exit;
}
$barang = mysqli_query($koneksi, "SELECT b.*, s.nama AS nama_supplier FROM barang b LEFT JOIN supplier s ON s.id=b.supplier_id ORDER BY b.id");
$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Barang</title>
</head>

<body>
    <p><a href="index.php">Kembali</a></p>
    <h1>Data Barang</h1>
    <?php if ($pesan !== ''): ?><p><strong><?= htmlspecialchars($pesan) ?></strong></p><?php endif; ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
        <?php while ($b = mysqli_fetch_assoc($barang)): ?><tr>
                <td><?= (int)$b['id'] ?></td>
                <td><?= htmlspecialchars($b['kode_barang']) ?></td>
                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                <td><?= (int)$b['stok'] ?></td>
                <td><?= htmlspecialchars($b['nama_supplier'] ?? '-') ?></td>
                <td><a href="barang.php?hapus=<?= (int)$b['id'] ?>" onclick="return confirm('Yakin menghapus barang ini?')">Hapus</a></td>
            </tr><?php endwhile; ?>
    </table>
</body>

</html>
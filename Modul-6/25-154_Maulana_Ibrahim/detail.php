<?php
require 'koneksi.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php?pesan=ID transaksi tidak valid');
    exit;
}
$stmt = mysqli_prepare($koneksi, "SELECT t.*, p.nama AS nama_pelanggan FROM transaksi t LEFT JOIN pelanggan p ON p.id=t.pelanggan_id WHERE t.id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$transaksi = mysqli_fetch_assoc($hasil);
if (!$transaksi) {
    header('Location: index.php?pesan=Transaksi tidak ditemukan');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = filter_input(INPUT_POST, 'barang_id', FILTER_VALIDATE_INT);
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
    if (!$barang_id || !$qty || $qty < 1) {
        $pesan = 'Pilih barang dan isi qty minimal 1.';
    } else {
        // Cegah barang yang sama dimasukkan dua kali pada transaksi yang sama.
        $cek = mysqli_prepare($koneksi, "SELECT id FROM transaksi_detail WHERE transaksi_id=? AND barang_id=?");
        mysqli_stmt_bind_param($cek, "ii", $id, $barang_id);
        mysqli_stmt_execute($cek);
        $sudah = mysqli_stmt_get_result($cek);
        if (mysqli_num_rows($sudah) > 0) {
            $pesan = 'Barang sudah ada pada detail transaksi ini.';
        } else {
            $ambil = mysqli_prepare($koneksi, "SELECT harga FROM barang WHERE id=?");
            mysqli_stmt_bind_param($ambil, "i", $barang_id);
            mysqli_stmt_execute($ambil);
            $data_barang = mysqli_fetch_assoc(mysqli_stmt_get_result($ambil));
            if (!$data_barang) {
                $pesan = 'Barang tidak ditemukan.';
            } else {
                mysqli_begin_transaction($koneksi);
                try {
                    $harga = (int)$data_barang['harga'];
                    $ins = mysqli_prepare($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($ins, "iiii", $id, $barang_id, $harga, $qty);
                    if (!mysqli_stmt_execute($ins)) throw new Exception(mysqli_error($koneksi));
                    $upd = mysqli_prepare($koneksi, "UPDATE transaksi SET total=(SELECT COALESCE(SUM(harga * qty),0) FROM transaksi_detail WHERE transaksi_id=?) WHERE id=?");
                    mysqli_stmt_bind_param($upd, "ii", $id, $id);
                    if (!mysqli_stmt_execute($upd)) throw new Exception(mysqli_error($koneksi));
                    mysqli_commit($koneksi);
                    header('Location: detail.php?id=' . $id . '&pesan=' . urlencode('Detail berhasil disimpan dan total diperbarui.'));
                    exit;
                } catch (Throwable $e) {
                    mysqli_rollback($koneksi);
                    $pesan = 'Gagal menyimpan detail transaksi.';
                }
            }
        }
    }
}
if (isset($_GET['pesan'])) $pesan = $_GET['pesan'];
$detail = mysqli_prepare($koneksi, "SELECT td.*, b.kode_barang, b.nama_barang FROM transaksi_detail td JOIN barang b ON b.id=td.barang_id WHERE td.transaksi_id=? ORDER BY td.id");
mysqli_stmt_bind_param($detail, "i", $id);
mysqli_stmt_execute($detail);
$daftar_detail = mysqli_stmt_get_result($detail);
// Dropdown hanya menampilkan barang yang belum digunakan dalam transaksi ini.
$opsi = mysqli_prepare($koneksi, "SELECT b.id, b.kode_barang, b.nama_barang, b.harga FROM barang b WHERE NOT EXISTS (SELECT 1 FROM transaksi_detail td WHERE td.transaksi_id=? AND td.barang_id=b.id) ORDER BY b.nama_barang");
mysqli_stmt_bind_param($opsi, "i", $id);
mysqli_stmt_execute($opsi);
$barang_tersedia = mysqli_stmt_get_result($opsi);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
</head>

<body>
    <p><a href="index.php">Kembali ke transaksi</a></p>
    <h1>Transaksi = <?= (int)$transaksi['id'] ?></h1>
    <p>Tanggal: <?= htmlspecialchars($transaksi['waktu_transaksi']) ?> | Pelanggan: <?= htmlspecialchars($transaksi['nama_pelanggan'] ?? '-') ?></p>
    <?php if (!empty($pesan)): ?><p><strong><?= htmlspecialchars($pesan) ?></strong></p><?php endif; ?>
    <h2>Detail Barang</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($d = mysqli_fetch_assoc($daftar_detail)): ?><tr>
                <td><?= htmlspecialchars($d['kode_barang'] . ' - ' . $d['nama_barang']) ?></td>
                <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                <td><?= (int)$d['qty'] ?></td>
                <td>Rp <?= number_format($d['harga'] * $d['qty'], 0, ',', '.') ?></td>
            </tr><?php endwhile; ?>
    </table>
    <h3>Total transaksi: Rp <?= number_format((int)$transaksi['total'], 0, ',', '.') ?></h3>
    <h2>Tambah Detail</h2>
    <form method="post">
        <label>Barang:
            <select name="barang_id" required>
                <option value="">-- Pilih barang --</option>
                <?php while ($b = mysqli_fetch_assoc($barang_tersedia)): ?>
                    <option value="<?= (int)$b['id'] ?>"><?= htmlspecialchars($b['kode_barang'] . ' - ' . $b['nama_barang'] . ' (Rp ' . number_format($b['harga'], 0, ',', '.') . ')') ?></option>
                <?php endwhile; ?>
            </select></label>
        <label>Qty: <input type="number" name="qty" min="1" required></label>
        <button type="submit">Simpan Detail</button>
    </form>
</body>

</html>
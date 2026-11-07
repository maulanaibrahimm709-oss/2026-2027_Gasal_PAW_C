<?php
require 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelanggan_id = filter_input(INPUT_POST, 'pelanggan_id', FILTER_VALIDATE_INT);
    $waktu = $_POST['waktu_transaksi'] ?? date('Y-m-d');
    $ket = trim($_POST['keterangan'] ?? '');
    if (!$pelanggan_id || !$waktu) {
        $pesan = 'Pelanggan dan tanggal wajib dipilih.';
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi (waktu_transaksi,keterangan,total,pelanggan_id) VALUES (?,?,0,?)");
        mysqli_stmt_bind_param($stmt, "ssi", $waktu, $ket, $pelanggan_id);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: detail.php?id=' . mysqli_insert_id($koneksi));
            exit;
        }
        $pesan = 'Transaksi gagal dibuat.';
    }
}
$pelanggan = mysqli_query($koneksi, "SELECT id,nama FROM pelanggan ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
</head>

<body>
    <p><a href="index.php">Kembali</a></p>
    <h1>Tambah Transaksi</h1>
    <?php if (!empty($pesan)): ?><p><?= htmlspecialchars($pesan) ?></p><?php endif; ?>
    <form method="post">
        <label>Tanggal <input type="date" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required></label><br><br>
        <label>Pelanggan <select name="pelanggan_id" required>
                <option value="">-- Pilih pelanggan --</option>
                <?php while ($p = mysqli_fetch_assoc($pelanggan)): ?><option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['nama']) ?></option><?php endwhile; ?>
            </select></label><br><br>
        <label>Keterangan <input type="text" name="keterangan" maxlength="255"></label><br><br><button type="submit">Buat transaksi</button>
    </form>
</body>

</html>
<?php
$pelanggan = $conn->query("SELECT id, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan")->fetch_all(MYSQLI_ASSOC);
?>
<h2>Tambah Transaksi</h2>
<?php tampilFlash(); ?>
<form method="post" action="?url=/api/transaksi/add">
    <label>tanggal :
        <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
    </label>
    <label>pelanggan :
        <select name="pelanggan_id" required>
            <option value="">- pilih pelanggan -</option>
            <?php foreach ($pelanggan as $p): ?>
                <option value="<?= (int)$p['id']; ?>"><?= htmlspecialchars($p['nama_pelanggan']); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>kasir :
        <input type="text" value="<?= htmlspecialchars(userNama()); ?>" disabled>
    </label>
    <button type="submit">Tambahkan</button>
</form>
<?php if (empty($pelanggan)): ?>
    <p class="pesan pesan-error">Data pelanggan masih kosong. Minta owner menambahkannya di Data Master &raquo; Data Pelanggan.</p>
<?php endif; ?>

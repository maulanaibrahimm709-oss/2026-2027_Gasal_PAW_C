<?php
$id = (int)($_GET['id'] ?? 0);
$result = null;
try {
    $stmt = $conn->prepare("SELECT id, tanggal, pelanggan, pelanggan_id, total FROM transaksi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $pelanggan = $conn->query("SELECT id, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan")->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
}
?>
<h2>Edit Transaksi</h2>
<?php tampilFlash(); ?>
<?php if (!$result): ?>
    <p class="pesan pesan-error">Data tidak ditemukan.</p>
<?php else: ?>
<form method="post" action="?url=/api/transaksi/edit">
    <input type="hidden" name="id" value="<?= (int)$result['id']; ?>">
    <label>tanggal :
        <input type="date" value="<?= htmlspecialchars($result['tanggal']); ?>" name="tanggal" required>
    </label>
    <label>pelanggan :
        <select name="pelanggan_id" required>
            <?php if (empty($result['pelanggan_id'])): ?>
                <option value="">- <?= htmlspecialchars($result['pelanggan']); ?> (data pelanggan sudah dihapus) -</option>
            <?php endif; ?>
            <?php foreach ($pelanggan as $p): ?>
                <option value="<?= (int)$p['id']; ?>" <?= ((int)$result['pelanggan_id'] === (int)$p['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($p['nama_pelanggan']); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Simpan</button>
</form>
<?php endif; ?>

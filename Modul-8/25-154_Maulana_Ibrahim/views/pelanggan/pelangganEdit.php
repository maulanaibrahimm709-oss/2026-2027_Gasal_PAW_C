<?php
$id = (int)($_GET['id'] ?? 0);
$result = null;
try {
    $stmt = $conn->prepare("SELECT id, nama_pelanggan, alamat, telepon FROM `pelanggan` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
}
?>
<h2>Edit Pelanggan</h2>
<?php tampilFlash(); ?>
<?php if (!$result): ?>
    <p class="pesan pesan-error">Data tidak ditemukan.</p>
<?php else: ?>
<form method="post" action="?url=/api/pelanggan/edit" autocomplete="off">
    <input type="hidden" name="id" value="<?= (int)$result['id']; ?>">
    <label>Nama Pelanggan :
        <input type="text" name="nama_pelanggan" value="<?= htmlspecialchars($result['nama_pelanggan'] ?? ''); ?>" placeholder="masukkan nama pelanggan..." required>
    </label>
    <label>Alamat :
        <input type="text" name="alamat" value="<?= htmlspecialchars($result['alamat'] ?? ''); ?>" placeholder="masukkan alamat...">
    </label>
    <label>Telepon :
        <input type="text" name="telepon" value="<?= htmlspecialchars($result['telepon'] ?? ''); ?>" placeholder="masukkan telepon...">
    </label>
    <button type="submit">Simpan</button>
</form>
<?php endif; ?>

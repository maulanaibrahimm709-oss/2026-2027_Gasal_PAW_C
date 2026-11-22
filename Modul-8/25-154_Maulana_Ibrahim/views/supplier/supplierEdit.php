<?php
$id = (int)($_GET['id'] ?? 0);
$result = null;
try {
    $stmt = $conn->prepare("SELECT id, nama_supplier, alamat, telepon FROM `supplier` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
}
?>
<h2>Edit Supplier</h2>
<?php tampilFlash(); ?>
<?php if (!$result): ?>
    <p class="pesan pesan-error">Data tidak ditemukan.</p>
<?php else: ?>
<form method="post" action="?url=/api/supplier/edit" autocomplete="off">
    <input type="hidden" name="id" value="<?= (int)$result['id']; ?>">
    <label>Nama Supplier :
        <input type="text" name="nama_supplier" value="<?= htmlspecialchars($result['nama_supplier'] ?? ''); ?>" placeholder="masukkan nama supplier..." required>
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

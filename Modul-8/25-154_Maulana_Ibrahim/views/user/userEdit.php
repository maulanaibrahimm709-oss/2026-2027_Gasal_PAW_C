<?php
$id = (int)($_GET['id'] ?? 0);
$result = null;
try {
    $stmt = $conn->prepare("SELECT id, nama, username, level FROM `user` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
}
?>
<h2>Edit User</h2>
<?php tampilFlash(); ?>
<?php if (!$result): ?>
    <p class="pesan pesan-error">Data tidak ditemukan.</p>
<?php else: ?>
<form method="post" action="?url=/api/user/edit" autocomplete="off">
    <input type="hidden" name="id" value="<?= (int)$result['id']; ?>">
    <label>Nama :
        <input type="text" name="nama" value="<?= htmlspecialchars($result['nama'] ?? ''); ?>" placeholder="masukkan nama..." required>
    </label>
    <label>Username :
        <input type="text" name="username" value="<?= htmlspecialchars($result['username'] ?? ''); ?>" placeholder="masukkan username..." required>
    </label>
    <label>Password baru (kosongkan jika tidak diganti) :
        <input type="password" name="password" minlength="6" autocomplete="new-password" placeholder="minimal 6 karakter...">
    </label>
    <label>Level :
        <select name="level" required>
            <option value="1" <?= ((int)($result['level'] ?? 1) === 1) ? 'selected' : ''; ?>>1 - Owner</option>
            <option value="2" <?= ((int)($result['level'] ?? 1) === 2) ? 'selected' : ''; ?>>2 - Kasir</option>
        </select>
    </label>
    <button type="submit">Simpan</button>
</form>
<?php endif; ?>

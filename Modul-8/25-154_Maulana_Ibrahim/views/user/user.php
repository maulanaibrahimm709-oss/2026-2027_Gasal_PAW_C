<?php
try {
    $result = $conn->query("SELECT * FROM `user` ORDER BY id DESC");
    $rows = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
    $rows = [];
}
?>
<h2>Daftar user</h2>
<?php tampilFlash(); ?>
<a href="?url=/user/add">
    tambah
</a>
<?php if (empty($rows)): ?>
    <p style="margin-top: 1rem; color: #64748b;">Belum ada data user.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($rows as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars((string)$row['id']); ?></td>
                    <td><?= htmlspecialchars((string)$row['nama']); ?></td>
                    <td><?= htmlspecialchars((string)$row['username']); ?></td>
                    <td><?= htmlspecialchars(namaLevel((int)$row['level'])); ?></td>
                    <td>
                        <a href="?url=/user/edit&id=<?= $row['id']; ?>">Edit</a>
                        <a href="?url=/api/user/delete&id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus user ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

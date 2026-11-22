<?php
try {
    $result = $conn->query("SELECT * FROM `pelanggan` ORDER BY id DESC");
    $rows = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
    $rows = [];
}
?>
<h2>Daftar pelanggan</h2>
<?php tampilFlash(); ?>
<a href="?url=/pelanggan/add">
    tambah
</a>
<?php if (empty($rows)): ?>
    <p style="margin-top: 1rem; color: #64748b;">Belum ada data pelanggan.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($rows as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars((string)$row['id']); ?></td>
                    <td><?= htmlspecialchars((string)$row['nama_pelanggan']); ?></td>
                    <td><?= htmlspecialchars((string)$row['alamat']); ?></td>
                    <td><?= htmlspecialchars((string)$row['telepon']); ?></td>
                    <td>
                        <a href="?url=/pelanggan/edit&id=<?= $row['id']; ?>">Edit</a>
                        <a href="?url=/api/pelanggan/delete&id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus pelanggan ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

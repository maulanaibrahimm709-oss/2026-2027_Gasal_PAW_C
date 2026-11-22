<?php
try {
    $result = $conn->query("SELECT * FROM `supplier` ORDER BY id DESC");
    $rows = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
    $rows = [];
}
?>
<h2>Daftar supplier</h2>
<?php tampilFlash(); ?>
<a href="?url=/supplier/add">
    tambah
</a>
<?php if (empty($rows)): ?>
    <p style="margin-top: 1rem; color: #64748b;">Belum ada data supplier.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>ID</th>
                <th>Nama Supplier</th>
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
                    <td><?= htmlspecialchars((string)$row['nama_supplier']); ?></td>
                    <td><?= htmlspecialchars((string)$row['alamat']); ?></td>
                    <td><?= htmlspecialchars((string)$row['telepon']); ?></td>
                    <td>
                        <a href="?url=/supplier/edit&id=<?= $row['id']; ?>">Edit</a>
                        <a href="?url=/api/supplier/delete&id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus supplier ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

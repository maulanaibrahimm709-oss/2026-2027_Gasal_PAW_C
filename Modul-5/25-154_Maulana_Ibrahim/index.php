<?php
require 'config.php';
$defs = [
    'pelanggan' => ['id', 'nama', 'jenis_kelamin', 'telp', 'alamat'],
    'transaksi' => ['id', 'waktu_transaksi', 'keterangan', 'total', 'pelanggan_id'],
    'transaksi_detail' => ['transaksi_id', 'barang_id', 'harga', 'qty'],
    'barang' => ['id', 'kode_barang', 'nama_barang', 'harga', 'stok', 'supplier_id'],
    'supplier' => ['id', 'nama', 'telp', 'alamat'],
    'user' => ['id_user', 'username', 'password', 'nama', 'alamat', 'hp', 'level'],
    'pembayaran' => ['id', 'waktu_bayar', 'total', 'metode', 'transaksi_id']
];
$pk = ['pelanggan' => ['id'], 'transaksi' => ['id'], 'transaksi_detail' => ['transaksi_id', 'barang_id'], 'barang' => ['id'], 'supplier' => ['id'], 'user' => ['id_user'], 'pembayaran' => ['id']];
$auto = ['pelanggan' => 'id', 'transaksi' => 'id', 'barang' => 'id', 'supplier' => 'id', 'user' => 'id_user', 'pembayaran' => 'id'];
$labels = ['pelanggan' => 'Pelanggan', 'transaksi' => 'Transaksi', 'transaksi_detail' => 'Transaksi Detail', 'barang' => 'Barang', 'supplier' => 'Supplier', 'user' => 'User', 'pembayaran' => 'Pembayaran'];
$t = $_GET['t'] ?? 'supplier';
if (!isset($defs[$t])) $t = 'pelanggan';
$cols = $defs[$t];
$keys = $pk[$t];
$msg = '';
$err = '';
function qi($s)
{
    return '`' . str_replace('`', '``', $s) . '`';
}
function bindrun($db, $sql, $v)
{
    $s = $db->prepare($sql);
    if ($v) {
        $ty = str_repeat('s', count($v));
        $s->bind_param($ty, ...$v);
    }
    $s->execute();
}
function getrow($db, $t, $keys, $vals)
{
    $w = implode(' AND ', array_map(fn($k) => qi($k) . '=?', $keys));
    $s = $db->prepare('SELECT * FROM ' . qi($t) . ' WHERE ' . $w);
    $s->bind_param(str_repeat('s', count($vals)), ...$vals);
    $s->execute();
    return $s->get_result()->fetch_assoc();
}
$act = $_GET['a'] ?? 'list';
$rec = null;
$old = [];
if (isset($_GET['k'])) {
    $old = json_decode(base64_decode($_GET['k']), true) ?? [];
    if (count($old) === count($keys)) $rec = getrow($db, $t, $keys, array_values($old));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($_POST['do'] === 'delete') {
            $o = json_decode(base64_decode($_POST['old']), true);
            if (count($o) !== count($keys)) throw new Exception('Kunci data tidak valid');
            $w = implode(' AND ', array_map(fn($k) => qi($k) . '=?', $keys));
            bindrun($db, 'DELETE FROM ' . qi($t) . ' WHERE ' . $w, array_values($o));
            $msg = 'Data berhasil dihapus.';
            $act = 'list';
        }
        if ($_POST['do'] === 'save') {
            $edit = $_POST['mode'] === 'edit';
            $v = [];
            foreach ($cols as $c) {
                if (isset($auto[$t]) && $auto[$t] === $c) continue;
                $v[$c] = trim($_POST[$c] ?? '');
                if ($v[$c] === '') throw new Exception('Semua kolom wajib diisi.');
            }
            if ($edit) {
                $o = json_decode(base64_decode($_POST['old']), true);
                $set = implode(',', array_map(fn($c) => qi($c) . '=?', array_keys($v)));
                $w = implode(' AND ', array_map(fn($k) => qi($k) . '=?', $keys));
                bindrun($db, 'UPDATE ' . qi($t) . ' SET ' . $set . ' WHERE ' . $w, array_merge(array_values($v), array_values($o)));
                $msg = 'Data berhasil diperbarui.';
            } else {
                $c = array_keys($v);
                bindrun($db, 'INSERT INTO ' . qi($t) . '(' . implode(',', array_map('qi', $c)) . ') VALUES(' . implode(',', array_fill(0, count($c), '?')) . ')', array_values($v));
                $msg = 'Data berhasil ditambahkan.';
            }
            $act = 'list';
        }
    } catch (Throwable $e) {
        $err = 'Gagal memproses data: ' . $e->getMessage();
        $act = ($_POST['do'] === 'save') ? ($_POST['mode'] === 'edit' ? 'edit' : 'add') : 'list';
    }
}
if ($act === 'edit' && !$rec) $err = 'Data tidak ditemukan.';
$rows = $db->query('SELECT * FROM ' . qi($t) . ' ORDER BY 1')->fetch_all(MYSQLI_ASSOC);
function val($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function labelcol($v)
{
    return ucwords(str_replace('_', ' ', $v));
}
$displayCols = array_values(array_filter($cols, fn($c) => !(isset($auto[$t]) && $auto[$t] === $c)));
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Praktikum Modul 5 Database</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Praktikum Modul 5: Database</h1><span>Data Master dan Transaksi</span>
    </header>
    <div class="wrap">
        <nav><b>MENU DATA</b><?php foreach ($defs as $n => $c): ?><a class="<?= $t === $n ? 'on' : '' ?>" href="?t=<?= val($n) ?>"><?= val($labels[$n]) ?></a><?php endforeach; ?></nav>
        <main>
            <div class="top">
                <div><small>DATABASE PENJUALAN</small>
                    <h2>Data Master <?= val($labels[$t]) ?></h2>
                </div><a class="btn" href="?t=<?= val($t) ?>&a=add">Tambah Data</a>
            </div>
            <?php if ($err): ?><p class="error"><?= val($err) ?></p><?php endif; ?><?php if ($msg): ?><p class="ok"><?= val($msg) ?></p><?php endif; ?>
            <?php if (in_array($act, ['add', 'edit']) && ($act !== 'edit' || $rec)): ?>
                <section class="form-panel">
                    <h3><?= $act === 'edit' ? 'Edit Data Master ' . val($labels[$t]) : 'Tambah Data Master ' . val($labels[$t]) . ' Baru' ?></h3>
                    <form method="post">
                        <div class="form-layout"><input type="hidden" name="do" value="save"><input type="hidden" name="mode" value="<?= val($act) ?>"><?php if ($act === 'edit'): ?><input type="hidden" name="old" value="<?= val(base64_encode(json_encode($old))) ?>"><?php endif; ?><div class="grid">
                                <?php foreach ($cols as $c): if (isset($auto[$t]) && $auto[$t] === $c) continue;
                                    $v = $rec[$c] ?? ''; ?><label><span class="field-label"><?= val(labelcol($c)) ?></span>
                                        <?php if ($c === 'jenis_kelamin'): ?><select name="<?= $c ?>" required>
                                                <option value="">Pilih</option>
                                                <option value="L" <?= $v === 'L' ? 'selected' : '' ?>>L</option>
                                                <option value="P" <?= $v === 'P' ? 'selected' : '' ?>>P</option>
                                            </select>
                                        <?php elseif ($c === 'metode'): ?><select name="<?= $c ?>" required><?php foreach (['Tunai', 'Transfer', 'EDC'] as $x): ?><option <?= $v === $x ? 'selected' : '' ?>><?= $x ?></option><?php endforeach; ?></select>
                                        <?php elseif ($c === 'pelanggan_id' || $c === 'supplier_id' || $c === 'transaksi_id' || $c === 'barang_id'):
                                            $ref = $c === 'pelanggan_id' ? 'pelanggan' : ($c === 'supplier_id' ? 'supplier' : ($c === 'barang_id' ? 'barang' : 'transaksi'));
                                            $rr = $db->query('SELECT * FROM ' . qi($ref) . ' ORDER BY 1')->fetch_all(MYSQLI_ASSOC);
                                            $idcol = $pk[$ref][0]; ?>
                                            <select name="<?= $c ?>" required>
                                                <option value="">Pilih <?= val($ref) ?></option><?php foreach ($rr as $r): $txt = $r['nama'] ?? $r['nama_barang'] ?? ('ID ' . $r[$idcol]); ?><option value="<?= val($r[$idcol]) ?>" <?= ((string)$v === (string)$r[$idcol]) ? 'selected' : '' ?>><?= val($r[$idcol] . ' - ' . $txt) ?></option><?php endforeach; ?>
                                            </select>
                                        <?php elseif (str_starts_with($c, 'waktu_')): ?><input type="datetime-local" name="<?= $c ?>" value="<?= val($v ? str_replace(' ', 'T', substr($v, 0, 16)) : date('Y-m-d\TH:i')) ?>" required>
                                        <?php elseif (in_array($c, ['alamat', 'keterangan'])): ?><textarea name="<?= $c ?>" required><?= val($v) ?></textarea>
                                        <?php else: ?><input name="<?= $c ?>" value="<?= val($v) ?>" <?= in_array($c, ['harga', 'total', 'qty', 'stok']) ? 'type="number" step="any"' : 'type="text"' ?> required><?php endif; ?></label><?php endforeach; ?></div>
                            <p class="form-actions"><button class="btn"><?= $act === 'edit' ? 'Update' : 'Simpan' ?></button> <a class="btn muted" href="?t=<?= val($t) ?>">Batal</a></p>
                        </div>
                    </form>
                </section>
            <?php else: ?>
                <section>
                    <p>Menampilkan <b><?= count($rows) ?> data</b></p>
                    <div class="scroll">
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>No</th><?php foreach ($displayCols as $c): ?><th><?= val(labelcol($c)) ?></th><?php endforeach; ?><th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody><?php foreach ($rows as $i => $r): $kv = [];
                                        foreach ($keys as $k) $kv[$k] = $r[$k];
                                        $encoded = base64_encode(json_encode($kv)); ?><tr>
                                        <td><?= ($i + 1) ?></td><?php foreach ($displayCols as $c): ?><td><?= val($r[$c]) ?></td><?php endforeach; ?><td class="action-cell"><a class="btn-edit" href="?t=<?= val($t) ?>&a=edit&k=<?= rawurlencode($encoded) ?>">Edit</a>
                                            <form class="inline" method="post" onsubmit="return confirm('Anda yakin akan menghapus <?= val($labels[$t]) ?> ini?')"><input type="hidden" name="do" value="delete"><input type="hidden" name="old" value="<?= val($encoded) ?>"><button class="btn-delete">Hapus</button></form>
                                        </td>
                                    </tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </section><?php endif; ?>
            <footer>Modul 5 Database · Tampil · Tambah · Edit · Hapus</footer>
        </main>
    </div>
</body>

</html>
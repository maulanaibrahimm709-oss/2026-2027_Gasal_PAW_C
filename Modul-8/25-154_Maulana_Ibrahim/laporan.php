<?php
require_once __DIR__ . '/laporanHelper.php';

$mulai   = $_GET['tanggal_mulai']   ?? '';
$selesai = $_GET['tanggal_selesai'] ?? '';
$diminta = ($mulai !== '' || $selesai !== '');
$error   = '';
$data    = null;

if ($diminta) {
    if (!tanggalValid($mulai) || !tanggalValid($selesai)) {
        $error = 'Format tanggal tidak valid.';
    } elseif ($mulai > $selesai) {
        $error = 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.';
    } else {
        try {
            $data = ambilLaporan($conn, $mulai, $selesai);
        } catch (Exception $e) {
            $error = 'ERROR : ' . $e->getMessage();
        }
    }
}

$labels = $totals = [];
if ($data) {
    foreach ($data['rekap'] as $r) {
        $labels[] = $r['tgl'];
        $totals[] = (int)$r['total_harian'];
    }
}
?>
<h2>Rekap Laporan Penjualan
    <?php if ($data): ?>
        <small class="periode"><?= htmlspecialchars($mulai); ?> sampai <?= htmlspecialchars($selesai); ?></small>
    <?php endif; ?>
</h2>

<!-- Form filter rentang tanggal (tidak ikut tercetak) -->
<form method="get" action="index.php" class="filter no-print">
    <input type="hidden" name="url" value="/laporan">
    <label>Tanggal mulai :
        <input type="date" name="tanggal_mulai" value="<?= htmlspecialchars($mulai); ?>" required>
    </label>
    <label>Tanggal selesai :
        <input type="date" name="tanggal_selesai" value="<?= htmlspecialchars($selesai); ?>" required>
    </label>
    <button type="submit">Tampilkan</button>
</form>

<?php if ($error): ?>
    <p class="pesan pesan-error"><?= htmlspecialchars($error); ?></p>
<?php elseif (!$diminta): ?>
    <p class="pesan">Pilih tanggal mulai dan tanggal selesai, lalu klik <strong>Tampilkan</strong>.</p>
<?php elseif (empty($data['rekap'])): ?>
    <p class="pesan">Tidak ada transaksi pada rentang tanggal tersebut.</p>
<?php else: ?>

    <!-- Tombol Cetak & Excel (tidak ikut tercetak) -->
    <div class="aksi-laporan no-print">
        <button type="button" class="btn-cetak" onclick="window.print()">Cetak</button>
        <a class="btn-excel"
           href="?url=/api/laporan/excel&tanggal_mulai=<?= urlencode($mulai); ?>&tanggal_selesai=<?= urlencode($selesai); ?>">Excel</a>
    </div>

    <!-- Grafik batang -->
    <div class="grafik">
        <canvas id="grafikPenjualan"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('grafikPenjualan'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: <?= json_encode($totals); ?>,
                    backgroundColor: 'rgba(2, 132, 199, 0.65)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false, // grafik langsung penuh saat dicetak
                scales: {
                    x: { title: { display: true, text: 'Tanggal Transaksi' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Pendapatan (Rp)' },
                        ticks: { callback: function (v) { return v.toLocaleString('id-ID'); } }
                    }
                }
            }
        });
    </script>

    <!-- Tabel rekapitulasi -->
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($data['rekap'] as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= rupiah($row['total_harian']); ?></td>
                    <td><?= tglIndo($row['tgl']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Card ringkasan total kumulatif -->
    <div class="ringkasan">
        <div class="card">
            <div class="card-judul">Jumlah Pelanggan Unik</div>
            <div class="card-nilai"><?= $data['pelanggan']; ?> Orang</div>
        </div>
        <div class="card">
            <div class="card-judul">Total Pendapatan Keseluruhan</div>
            <div class="card-nilai"><?= rupiah($data['pendapatan']); ?></div>
        </div>
    </div>

<?php endif; ?>

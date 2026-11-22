<h2>Tambah Pelanggan</h2>
<?php tampilFlash(); ?>
<form method="post" action="?url=/api/pelanggan/add" autocomplete="off">
    <label>Nama Pelanggan :
        <input type="text" name="nama_pelanggan" placeholder="masukkan nama pelanggan..." required>
    </label>
    <label>Alamat :
        <input type="text" name="alamat" placeholder="masukkan alamat...">
    </label>
    <label>Telepon :
        <input type="text" name="telepon" placeholder="masukkan telepon...">
    </label>
    <button type="submit">Tambahkan</button>
</form>

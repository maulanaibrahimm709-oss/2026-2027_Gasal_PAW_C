<h2>Tambah Supplier</h2>
<?php tampilFlash(); ?>
<form method="post" action="?url=/api/supplier/add" autocomplete="off">
    <label>Nama Supplier :
        <input type="text" name="nama_supplier" placeholder="masukkan nama supplier..." required>
    </label>
    <label>Alamat :
        <input type="text" name="alamat" placeholder="masukkan alamat...">
    </label>
    <label>Telepon :
        <input type="text" name="telepon" placeholder="masukkan telepon...">
    </label>
    <button type="submit">Tambahkan</button>
</form>

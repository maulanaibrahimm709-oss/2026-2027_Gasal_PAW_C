<h2>Tambah User</h2>
<?php tampilFlash(); ?>
<form method="post" action="?url=/api/user/add" autocomplete="off">
    <label>Nama :
        <input type="text" name="nama" placeholder="masukkan nama..." required>
    </label>
    <label>Username :
        <input type="text" name="username" placeholder="masukkan username..." required>
    </label>
    <label>Password :
        <input type="password" name="password" minlength="6" autocomplete="new-password" placeholder="minimal 6 karakter..." required>
    </label>
    <label>Level :
        <select name="level" required>
            <option value="1" >1 - Owner</option>
            <option value="2" >2 - Kasir</option>
        </select>
    </label>
    <button type="submit">Tambahkan</button>
</form>

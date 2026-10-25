<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Uji GET</title></head>
<body>
<h1>Pengujian Method GET</h1>
<p>File ini dipakai untuk membandingkan GET dengan POST sesuai instruksi modul.</p>
<form action="processData.php" method="GET">
    <label for="surname">Surname:</label>
    <input type="text" name="surname" id="surname">
    <input type="submit" value="Kirim dengan GET">
</form>
<p>Catatan: processData.php utama memproses POST. Untuk mengamati GET, perhatikan nilai pada URL setelah tombol diklik. Pesan penolakan POST dari processData.php pada tahap ini memang menunjukkan bahwa file utama menunggu POST.</p>
</body>
</html>

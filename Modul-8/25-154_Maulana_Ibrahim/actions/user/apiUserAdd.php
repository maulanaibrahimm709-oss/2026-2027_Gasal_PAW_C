<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $level = (int)($_POST['level'] ?? 0);
    $err = '';
    if ($nama === '') $err = 'Nama wajib diisi.';
    elseif (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $username)) $err = 'Username 3-30 karakter (huruf, angka, titik, strip, underscore).';
    elseif (!in_array($level, [1, 2], true)) $err = 'Level tidak valid.';
    elseif (strlen($password) < 6) $err = 'Password minimal 6 karakter.';
    if ($err !== '') {
        setFlash('error', $err);
        header('Location: ?url=/user/add');
        exit;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare("INSERT INTO `user` (nama, username, password, level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nama, $username, $password, $level);
        $stmt->execute();
        setFlash('ok', 'User berhasil ditambahkan.');
        header('Location: ?url=/user');
        exit;
    } catch (mysqli_sql_exception $ex) {
        setFlash('error', $ex->getCode() == 1062 ? 'Username sudah dipakai.' : 'Gagal menyimpan data.');
        header('Location: ?url=/user/add');
        exit;
    }
}
